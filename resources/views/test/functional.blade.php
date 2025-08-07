<!DOCTYPE html>
<html>
<head>
    <title>Uji Fungsional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Hasil Uji Fungsional</h3>
    <p><strong>Waktu respons:</strong> {{ $duration }} detik</p>
    <p><strong>Gejala:</strong> {{ implode(', ', $symptoms) }}</p>

    <h4>Hasil Probabilitas</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Penyakit</th>
                <th>Skor (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($response['probabilities'] as $label => $score)
            <tr>
                <td>{{ $label }}</td>
                <td>{{ number_format($score * 100, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
