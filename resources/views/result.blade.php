<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Diagnosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
            padding: 40px 0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
        }
        .image-box {
            width: 300px;
            height: 300px;
            background: #fff;
            margin: 0 auto 30px;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 2px solid #3b82f6;
        }
        .image-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .result-box {
            background: #fff;
            width: 85%;
            margin: 20px auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            min-height: 100px;
        }
        .result-box h5 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .prob-table {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        .prob-table th {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            text-align: center;
            font-weight: 600;
            padding: 15px;
        }
        .prob-table td {
            text-align: center;
            padding: 12px;
            border-bottom: 1px solid #f1f3f4;
        }
        .prob-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 15px;
        }
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
        .badge.bg-warning {
            background-color: #ffc107 !important;
        }
        .badge.bg-info {
            background-color: #17a2b8 !important;
        }
        .badge.bg-success {
            background-color: #28a745 !important;
        }
        .back-btn {
            margin: 30px auto;
            text-align: center;
        }
        .back-btn .btn {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        .back-btn .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .back-btn .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .back-btn .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .back-btn .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }
        .alert {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #ffffff 100%);
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .symptom-list {
            list-style: none;
            padding-left: 0;
        }
        .symptom-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
            color: #555;
        }
        .symptom-list li:last-child {
            border-bottom: none;
        }
        .symptom-list li::before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
        }
        .diagnosis-list {
            list-style: none;
            padding-left: 0;
        }
        .diagnosis-list li {
            padding: 10px 0;
            color: #333;
            font-size: 16px;
        }
        .diagnosis-list li::before {
            content: "🔍";
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <!-- Gambar X-ray -->
    <div class="image-box">
        @if(isset($xrayPath) && $xrayPath)
            @php
                // Debug path untuk memastikan path benar
                $fullImagePath = asset('storage/' . $xrayPath);
                $fileExists = file_exists(public_path('storage/' . $xrayPath));
            @endphp
            
            @if($fileExists)
                <img src="{{ $fullImagePath }}" alt="X-ray Image">
            @else
                <div class="text-center p-4">
                    <span class="text-muted">Gambar X-ray tidak dapat dimuat</span><br>
                    <small class="text-danger">Path: storage/{{ $xrayPath }}</small><br>
                    <small class="text-info">Full URL: {{ $fullImagePath }}</small>
                </div>
            @endif
        @else
            <span class="text-muted">Citra X-ray tidak tersedia</span>
        @endif
    </div>

    <!-- Interpretasi Klinis -->
    <div class="result-box">
        <h5>Interpretasi Klinis</h5>
        @if(isset($diagnosis) && count($diagnosis))
            @if(count($diagnosis) == 1 && $diagnosis[0] == '')
                <div class="alert alert-info">
                    <strong>Informasi:</strong> Berdasarkan analisis, tidak ditemukan indikasi kuat terhadap penyakit tertentu. 
                    Namun, silakan lihat tabel probabilitas di bawah untuk detail lebih lanjut.
                </div>
            @else
                <ul class="diagnosis-list">
                    @foreach($diagnosis as $disease)
                        @if($disease != '')
                            <li><strong>{{ $disease }}</strong></li>
                        @endif
                    @endforeach
                </ul>
                @if(count($diagnosis) <= 3)
                    <div class="alert alert-warning mt-3">
                        <small><strong>Catatan:</strong> Diagnosis di atas adalah 3 kemungkinan tertinggi berdasarkan analisis AI. 
                        Konsultasikan dengan dokter untuk diagnosis yang akurat.</small>
                    </div>
                @endif
            @endif
        @else
            <div class="alert alert-info">
                <strong>Informasi:</strong> Tidak ada diagnosis dengan tingkat kepercayaan tinggi. 
                Silakan periksa tabel probabilitas untuk detail lebih lanjut.
            </div>
        @endif
    </div>

    <!-- Gejala yang dipilih -->
    @if(isset($selectedSymptoms) && count($selectedSymptoms))
        <div class="result-box">
            <h5>Gejala yang Dirasakan</h5>
            <ul class="symptom-list">
                @foreach($selectedSymptoms as $symptom)
                    <li>{{ ucwords(str_replace('_', ' ', $symptom)) }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="result-box">
            <h5>Gejala yang Dirasakan</h5>
            <p class="text-muted">Tidak ada gejala yang dipilih</p>
        </div>
    @endif

    <!-- Tabel Probabilitas -->
    @if(isset($probabilities) && is_array($probabilities) && count($probabilities))
        <div class="result-box">
            <h5>Probabilitas Tiap Penyakit</h5>
            <div class="table-responsive">
                <table class="table table-bordered prob-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penyakit</th>
                            <th>Probabilitas</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        // Urutkan probabilitas dari yang tertinggi
                        $sortedProbs = collect($probabilities)->sortDesc();
                    @endphp
                    @foreach($sortedProbs as $label => $score)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td><strong>{{ $label }}</strong></td>
                            <td>
                                <strong>{{ number_format($score * 100, 2) }}%</strong>
                                @if($score >= 0.5)
                                    <span class="badge bg-danger ms-2">Tinggi</span>
                                @elseif($score >= 0.3)
                                    <span class="badge bg-warning text-dark ms-2">Sedang</span>
                                @elseif($score >= 0.1)
                                    <span class="badge bg-info ms-2">Rendah</span>
                                @else
                                    <span class="badge bg-success ms-2">Sangat Rendah</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Tombol kembali -->
    <div class="back-btn">
        <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
        @auth
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Lihat Riwayat</a>
        @endauth
    </div>

    <!-- Disclaimer -->
    <div class="result-box">
        <div class="alert alert-warning">
            <h6><strong>Disclaimer:</strong></h6>
            <p class="mb-0"><small>
                Hasil diagnosis ini adalah prediksi dari sistem AI dan tidak menggantikan konsultasi medis profesional. 
                Selalu konsultasikan kondisi kesehatan Anda dengan dokter yang kompeten untuk diagnosis dan pengobatan yang tepat.
            </small></p>
        </div>
    </div>

</body>
</html>