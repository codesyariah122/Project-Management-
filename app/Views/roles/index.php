<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="text-3xl font-bold mb-6 text-blue-600">💼 Role Management</h1>

<a href="/roles/add" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
    + Tambah Role
</a>

<table class="min-w-full border border-gray-300 mt-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2">Role</th>
            <th class="border px-4 py-2">Rate per Day (Rp)</th>
            <th class="border px-4 py-2 w-32">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $r): ?>
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2"><?= esc($r['role_name']) ?></td>
                <td class="border px-4 py-2 text-right"><?= esc($r['rate_per_day']) ?></td>
                <td class="border px-4 py-2 text-center space-x-3">
                    <a href="/roles/edit/<?= $r['id'] ?>"
                        class="text-blue-500 hover:text-blue-700"
                        title="Edit Role">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

                    <a href="/roles/delete/<?= $r['id'] ?>"
                        class="text-red-500 hover:text-red-700"
                        title="Hapus Role"
                        onclick="return confirmDelete(event, '<?= esc($r['role_name']) ?>', this.href)">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Hapus dengan konfirmasi SweetAlert
    function confirmDelete(e, name, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin hapus?',
            text: `Role "${name}" akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // SweetAlert untuk pesan sukses/error
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= session()->getFlashdata('error') ?>',
            showConfirmButton: false,
            timer: 2500
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>