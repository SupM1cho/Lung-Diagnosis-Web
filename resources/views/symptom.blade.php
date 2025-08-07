<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Gejala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        .gejala-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 85%;
            max-width: 900px;
            margin: 50px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .header-box {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
            padding: 20px 25px;
            text-align: center;
        }
        .header-box h5 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .checkbox-column {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 0 15px;
        }
        .checkbox-column label {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-size: 15px;
            font-weight: 500;
            color: #555;
        }
        .checkbox-column label:hover {
            background-color: #e3f2fd;
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        .checkbox-column input[type="checkbox"] {
            margin-right: 15px;
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
            cursor: pointer;
        }
        .checkbox-column label:has(input:checked) {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .submit-btn {
            float: right;
            margin-top: 30px;
            padding: 15px 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        .form-container {
            margin-top: 20px;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        @media (max-width: 768px) {
            .checkbox-column {
                margin-bottom: 20px;
            }
            .submit-btn {
                float: none;
                width: 100%;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="gejala-card">
        <div class="header-box">
            <h5>Silakan pilih gejala yang dirasakan</h5>
        </div>

        <form action="{{ url('/diagnosis') }}" method="POST" class="form-container">
            @csrf
            <div class="row">
                <div class="col-md-4 checkbox-column">
                    <label>
                        <input type="checkbox" name="symptoms[]" value="batuk">
                        Batuk
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="sesak_napas">
                        Sesak Napas
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="nyeri_dada">
                        Nyeri Dada
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="demam">
                        Demam
                    </label>
                </div>
                <div class="col-md-4 checkbox-column">
                    <label>
                        <input type="checkbox" name="symptoms[]" value="lemas">
                        Lemas
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="batuk_berdahak">
                        Batuk Berdahak
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="penurunan_bb">
                        Penurunan Berat Badan
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="mual">
                        Mual
                    </label>
                </div>
                <div class="col-md-4 checkbox-column">
                    <label>
                        <input type="checkbox" name="symptoms[]" value="berkeringat_malam">
                        Berkeringat di Malam Hari
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="batuk_darah">
                        Batuk Berdarah
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="dada_tertekan">
                        Dada Tertekan
                    </label>
                    <label>
                        <input type="checkbox" name="symptoms[]" value="napas_cepat">
                        Napas Cepat
                    </label>
                </div>
            </div>
            <div class="clearfix">
                <button type="submit" class="submit-btn">Lanjutkan Diagnosis</button>
            </div>
        </form>
    </div>
</body>
</html>