<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-2xl font-bold mb-4">
    <?= $role ? 'Edit Role' : 'Tambah Role Baru' ?>
</h2>

<form method="post" action="<?= $role ? '/roles/update/' . $role['id'] : '/roles/store' ?>" class="space-y-4 max-w-md">
    <div>
        <label class="block font-medium">Nama Role:</label>
        <input type="text" name="role_name" value="<?= esc($role['role_name'] ?? '') ?>" required
            class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-medium">Rate per Day (Rp):</label>
        <input type="text" id="rate_per_day" name="rate_per_day"
            value="<?= esc($role['rate_per_day'] ?? '') ?>"
            required
            placeholder="Contoh: 1.500.000 – 3.000.000"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
    </div>

    <button type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        <?= $role ? 'Update' : 'Simpan' ?>
    </button>
</form>

<!-- Auto-format ke Rupiah -->
<script>
    const rateInput = document.getElementById('rate_per_day');

    rateInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9–-]/g, ''); // Hapus semua karakter selain angka dan tanda –
        let parts = value.split(/[–-]/).map(v => v.trim()).filter(Boolean);

        if (parts.length === 1) {
            e.target.value = formatRupiah(parts[0]);
        } else if (parts.length === 2) {
            e.target.value = `${formatRupiah(parts[0])} – ${formatRupiah(parts[1])}`;
        }
    });

    function formatRupiah(angka) {
        if (!angka) return '';
        return 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>

<?= $this->endSection() ?>