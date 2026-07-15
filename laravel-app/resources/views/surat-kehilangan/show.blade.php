<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4">Detail Surat</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>No. Surat:</strong> {{ $suratKehilangan->nomer_surat }}</p>
            <p><strong>No. Polisi:</strong> {{ $suratKehilangan->nopo }}</p>
            <p><strong>Merk:</strong> {{ $suratKehilangan->merk }}</p>
            <p><strong>BPKB:</strong> {{ $suratKehilangan->bpkb }}</p>
            <p><strong>Polres:</strong> {{ $suratKehilangan->polres }}</p>
        </div>
    </div>
</div>
</body>
</html>
