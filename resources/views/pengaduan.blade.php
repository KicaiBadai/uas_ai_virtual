<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaduan - AM Merchandise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #f3e8ff, #e0e7ff);}
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center text-purple-800">Formulir Pengaduan</h2>
    <form id="complaint-form" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" required>
            <div class="invalid-feedback">Nama lengkap diperlukan.</div>
        </div>
        <div class="mb-3">
            <label for="receipt" class="form-label">Nomor Resi</label>
            <input type="text" class="form-control" id="receipt" pattern = "AM[0-9]{6}"required>
            <div class="invalid-feedback">Nomor resi diperlukan dengan format yang benar contoh:AM123456.</div>
        </div>
        <div class="mb-3">
            <label for="whatsapp" class="form-label">Nomor WhatsApp (62xxxx)</label>
            <input type="tel" class="form-control" id="whatsapp" pattern="^62[0-9]{8,13}$" required>
            <div class="invalid-feedback">Masukkan nomor WhatsApp dengan format 62xxxxxxxxxx.</div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Masalah</label>
            <textarea class="form-control" id="description" rows="4" required></textarea>
            <div class="invalid-feedback">Deskripsi diperlukan.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kirim Pengaduan</button>
    </form>
</div>

<script>
    // Bootstrap validation
    (() => {
        const form = document.getElementById('complaint-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            const name = document.getElementById('name').value.trim();
            const receipt = document.getElementById('receipt').value.trim();
            const whatsapp = document.getElementById('whatsapp').value.trim();
            const description = document.getElementById('description').value.trim();
            const adminWa = '6281234567890'; // admin number placeholder
            const message = `Halo Admin AM Merchandise,%0A%0ASaya ingin mengajukan pengaduan.%0A%0ANama: ${encodeURIComponent(name)}%0ANomor Resi: ${encodeURIComponent(receipt)}%0ANomor WhatsApp: ${encodeURIComponent(whatsapp)}%0ADeskripsi: ${encodeURIComponent(description)}`;
            const waUrl = `https://wa.me/${adminWa}?text=${message}`;
            window.open(waUrl, '_blank');
        });
    })();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
