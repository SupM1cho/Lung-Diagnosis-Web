<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Diagnosis;
use App\Models\DiagnosisLabel;
use App\Models\DiagnosisSymptom;


class DiagnosisController extends Controller
{
    // Mapping gejala dari form ke format yang dikenali Flask
    private $symptomMapping = [
        'batuk' => 'Batuk',
        'sesak_napas' => 'Sesak_napas',
        'nyeri_dada' => 'Nyeri_dada',
        'demam' => 'Demam',
        'lemas' => 'Kelelahan_penurunan_bb',
        'batuk_berdahak' => 'Batuk',
        'penurunan_bb' => 'Kelelahan_penurunan_bb',
        'mual' => 'Kembung_perut',
        'berkeringat_malam' => 'Kelelahan_penurunan_bb',
        'batuk_darah' => 'Batuk_darah',
        'dada_tertekan' => 'Nyeri_dada',
        'napas_cepat' => 'Sesak_napas'
    ];

    public function showSymptomForm(Request $request)
    {
        $xrayPath = $request->session()->get('xray_image_path');
        $xrayOriginalName = $request->session()->get('xray_original_name');

        if (!$xrayPath) {
            return redirect('/')->withErrors(['xray_image' => 'Silakan unggah X-ray terlebih dahulu.']);
        }

        return view('symptom', [
            'xrayPath' => $xrayPath,
            'xrayOriginalName' => $xrayOriginalName
        ]);
    }

    public function uploadXray(Request $request)
    {
        $request->validate([
            'xray_image' => 'required|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $image = $request->file('xray_image');
        $originalName = $image->getClientOriginalName();
        $path = $image->store('uploads', 'public');

        // Simpan path gambar dan nama asli di session
        $request->session()->put('xray_image_path', $path);
        $request->session()->put('xray_original_name', $originalName);

        // Arahkan ke form input gejala dengan pesan sukses
        return redirect('/symptom')->with('success', 'File X-ray berhasil diunggah: ' . $originalName);
    }

    public function processDiagnosis(Request $request)
    {
        Log::info('=== Starting Diagnosis Process ===');
        
        // Validasi input
        $request->validate([
            'symptoms' => 'nullable|array'
        ]);

        // Ambil path gambar X-ray dan nama asli dari session
        $path = $request->session()->get('xray_image_path');
        $originalName = $request->session()->get('xray_original_name');
        
        if (!$path || !Storage::disk('public')->exists($path)) {
            Log::error('X-ray file not found', ['path' => $path]);
            return redirect('/')->withErrors(['xray_image' => 'File X-ray tidak ditemukan.']);
        }

        // Ambil file path dan nama file
        $fullPath = storage_path("app/public/{$path}");
        $filename = basename($path);
        $originalSymptoms = $request->input('symptoms', []);
        
        // Mapping gejala ke format Flask
        $flaskSymptoms = [];
        foreach ($originalSymptoms as $symptom) {
            if (isset($this->symptomMapping[$symptom])) {
                $mappedSymptom = $this->symptomMapping[$symptom];
                if (!in_array($mappedSymptom, $flaskSymptoms)) {
                    $flaskSymptoms[] = $mappedSymptom;
                }
            }
        }

        Log::info('Symptoms mapping', [
            'original' => $originalSymptoms,
            'mapped' => $flaskSymptoms
        ]);

        try {
            // Siapkan multipart data dengan format yang benar
            $multipartData = [
                [
                    'name' => 'xray_image',
                    'contents' => file_get_contents($fullPath),
                    'filename' => $filename
                ]
            ];

            // Tambahkan setiap symptom sebagai field terpisah
            foreach ($flaskSymptoms as $symptom) {
                $multipartData[] = [
                    'name' => 'symptoms[]',
                    'contents' => $symptom
                ];
            }

            Log::info('Sending request to Flask API...');

            // Kirim ke Flask API dengan timeout yang cukup
            $response = Http::timeout(60)
                ->asMultipart()
                ->post('http://127.0.0.1:5000/predict', $multipartData);

            Log::info('Flask API Response', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                Log::error('Flask API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Gagal menerima respon dari model Python. Status: " . $response->status());
            }

            $result = $response->json();
            
            Log::info('Flask API Response Data', $result);

            // Validasi struktur response
            if (!isset($result['diagnosis']) || !isset($result['probabilities'])) {
                throw new \Exception("Format response dari Flask API tidak sesuai");
            }

            // Jika tidak ada diagnosis dengan threshold tinggi, ambil 3 teratas
            $diagnosis = $result['diagnosis'];
            if (empty($diagnosis)) {
                $probabilities = $result['probabilities'];
                arsort($probabilities);
                $diagnosis = array_slice(array_keys($probabilities), 0, 3);
                Log::info('No high-confidence diagnosis, using top 3', ['top3' => $diagnosis]);
            }

        } catch (\Exception $e) {
            Log::error('Diagnosis Processing Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'symptoms' => $flaskSymptoms,
                'path' => $path
            ]);
            
            return back()->withErrors(['model' => 'Gagal melakukan prediksi: ' . $e->getMessage()]);
        }

        // Simpan ke database jika user login
        $diagnosisId = null;
        if (Auth::check()) {
            try {
                Log::info('Saving to database for user: ' . Auth::id());
                
                $diagnosisRecord = Diagnosis::create([
                    'user_id' => Auth::id(),
                    'diagnosis_date' => now()->format('Y-m-d')
                ]);
                $diagnosisId = $diagnosisRecord->diagnosis_id; // Gunakan primary key yang benar

                // Simpan hasil diagnosis (semua probabilitas, bukan hanya yang tinggi)
                foreach ($result['probabilities'] as $label => $score) {
                    DiagnosisLabel::create([
                        'diagnosis_id' => $diagnosisId,
                        'label_name' => $label,
                        'label_cscore' => $score
                    ]);
                }

                // Simpan gejala asli yang dipilih user
                foreach ($originalSymptoms as $symptom) {
                    DiagnosisSymptom::create([
                        'diagnosis_id' => $diagnosisId,
                        'symptom_name' => $symptom
                    ]);
                }
                
                Log::info('Successfully saved to database');
                
            } catch (\Exception $e) {
                Log::error('Database Save Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                // Tetap lanjutkan ke tampilan hasil meskipun gagal save ke DB
            }
        }

        Log::info('=== Diagnosis Process Completed ===');

        // Debug path gambar
        $publicPath = public_path('storage/' . $path);
        $storageExists = Storage::disk('public')->exists($path);
        $publicExists = file_exists($publicPath);
        
        Log::info('Image path debug', [
            'session_path' => $path,
            'storage_exists' => $storageExists,
            'public_exists' => $publicExists,
            'public_path' => $publicPath,
            'asset_url' => asset('storage/' . $path)
        ]);

        // Tampilkan hasil ke view
        return view('result', [
            'xrayPath' => $path,
            'xrayUrl' => Storage::disk('public')->url($path), // Tambahkan URL langsung
            'xrayOriginalName' => $originalName, // Tambahkan nama file asli
            'diagnosis' => $diagnosis,
            'probabilities' => $result['probabilities'],
            'selectedSymptoms' => $originalSymptoms
        ]);
    }

    public function showResult()
    {
        return view('result');
    }

    public function userHistory()
    {
        $user = Auth::user();
        $diagnoses = $user->diagnoses()->with(['labels', 'symptoms'])->latest()->get();
    
        return view('user', [
            'diagnoses' => $diagnoses
        ]);
    }
    

    public function showUserDashboard(Request $request)
    {
        $user = Auth::user();
        $view = $request->query('view', 'history'); // default: riwayat

        $diagnoses = [];
        if ($view === 'history') {
            $diagnoses = Diagnosis::with(['labels', 'symptoms'])
                ->where('user_id', $user->user_id) // Gunakan primary key yang benar
                ->latest('diagnosis_date')
                ->get();
        }

        return view('user', [
            'view' => $view,
            'user' => $user,
            'diagnoses' => $diagnoses
        ]);
    }

    public function testFunctional()
    {
        $start = microtime(true);

        // Simulasi upload file X-ray
        $imagePath = storage_path('app/public/sample_1.png');
        if (!file_exists($imagePath)) {
            return "Gagal: Gambar contoh tidak ditemukan di storage/public/sample_xray.jpg";
        }

        $image = new \Illuminate\Http\UploadedFile(
            $imagePath,
            'sample_1.png',
            'image/jpeg',
            null,
            true
        );

        // Simulasi gejala
        $symptoms = ['Batuk', 'Sesak Napas', 'Demam'];

        // Kirim ke API Flask
        $response = Http::asMultipart()->post('http://127.0.0.1:5000/predict', [
            [
                'name'     => 'xray_image',
                'contents' => fopen($imagePath, 'r'),
                'filename' => 'sample_1.png',
            ],
            [
                'name'     => 'symptoms[]',
                'contents' => 'Batuk',
            ],
            [
                'name'     => 'symptoms[]',
                'contents' => 'Sesak Napas',
            ],
            [
                'name'     => 'symptoms[]',
                'contents' => 'Demam',
            ],
        ]);


        $duration = round((microtime(true) - $start), 3);

        if ($response->failed()) {
            return "Gagal: API tidak merespons.";
        }

        $data = $response->json();

        // Simpan ke CSV
        $records = [];
        foreach ($data['probabilities'] as $label => $score) {
            $records[] = [
                'File' => 'sample_1.png',
                'Gejala' => implode(', ', $symptoms),
                'Penyakit' => $label,
                'Skor (%)' => round($score * 100, 2)
            ];
        }

        $csvPath = storage_path('app/public/test-functional-result.csv');
        $handle = fopen($csvPath, 'w');
        fputcsv($handle, ['File', 'Gejala', 'Penyakit', 'Skor (%)']);
        foreach ($records as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);


        return view('test.functional', [
            'duration' => $duration,
            'symptoms' => $symptoms,
            'response' => $data
        ]);
    }

}