<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
            margin: 0; 
            padding: 0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .back-home-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 45px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            color: white;
            text-decoration: none;
            margin: 0 0 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .back-home-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        .back-home-btn svg {
            transition: transform 0.3s ease;
        }
        .back-home-btn:hover svg {
            transform: translateX(-2px);
        }
        .sidebar {
            width: 240px; 
            height: 100vh;
            background: #fff;
            padding: 30px 20px; 
            position: fixed;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            border-right: 1px solid #e9ecef;
        }
        .profile-pic {
            width: 80px; 
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%; 
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .nav-link {
            background: #f8f9fa;
            margin: 15px 0; 
            padding: 15px 20px;
            border-radius: 12px;
            text-align: center; 
            color: #333;
            text-decoration: none; 
            display: block;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-weight: 500;
        }
        .nav-link:hover { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            border-color: #2563eb;
        }
        .nav-link.btn {
            border: none;
            width: 100%;
        }
        .main-content { 
            margin-left: 260px; 
            padding: 40px;
        }
        .content-box {
            background: #fff;
            border-radius: 20px; 
            padding: 40px;
            min-height: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .content-box h5 {
            color: #333;
            font-weight: bold;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
        }
        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .list-group-item {
            border: none;
            border-radius: 12px !important;
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6 !important;
        }
        .list-group-item:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        .list-group-item strong {
            color: #3b82f6;
            font-size: 16px;
        }
        .no-history {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }
        .mb-3 label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="{{ url('/') }}" class="back-home-btn" title="Kembali ke Beranda">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"></path>
            <path d="m19 12H5"></path>
        </svg>
    </a>
    <div class="profile-pic">
        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
    </div>
    <a href="{{ route('user.dashboard', ['view' => 'history']) }}" class="nav-link">Riwayat Diagnosis</a>
    <a href="{{ route('user.dashboard', ['view' => 'settings']) }}" class="nav-link">Profil Pengguna</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-link btn">Keluar</button>
    </form>
</div>

<div class="main-content">
    <div class="content-box">
        @if($view === 'settings')
            <h5>Pengaturan Profil</h5>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" class="form-control" placeholder="Masukkan nama lengkap">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" class="form-control" placeholder="Masukkan email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        @elseif($view === 'history')
            <h5>Riwayat Diagnosis</h5>
            @if($diagnoses->isEmpty())
                <div class="no-history">
                    <h6>Tidak ada riwayat diagnosis</h6>
                    <p class="mb-0">Belum ada diagnosis yang pernah dilakukan. Silakan lakukan diagnosis terlebih dahulu.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Mulai Diagnosis</a>
                </div>
            @else
                <div class="list-group">
                    @foreach($diagnoses as $diagnosis)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('d M Y, H:i') }}</strong><br>
                                    <div class="mt-2">
                                        <span class="badge bg-primary me-2">Penyakit:</span>
                                        {{ $diagnosis->labels->pluck('label_name')->join(', ') ?: 'Tidak terdeteksi' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary me-2">Gejala:</span>
                                        {{ $diagnosis->symptoms->pluck('symptom_name')->join(', ') ?: 'Tidak ada gejala' }}
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center">
                <h5>Halaman tidak ditemukan</h5>
                <p class="text-muted">Konten yang Anda cari tidak tersedia.</p>
                <a href="{{ route('user.dashboard', ['view' => 'history']) }}" class="btn btn-primary">Kembali ke Riwayat</a>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>