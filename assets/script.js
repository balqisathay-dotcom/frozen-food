// Konfirmasi sebelum menghapus data
document.addEventListener('submit', function (event) {
    var form = event.target;
    if (form.classList.contains('form-hapus')) {
        var pesan = form.getAttribute('data-pesan') || 'Yakin ingin menghapus data ini?';
        if (!window.confirm(pesan)) {
            event.preventDefault();
        }
    }
});
