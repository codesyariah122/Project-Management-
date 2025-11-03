<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">All Templates</h2>

<a href="/templates/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">
    + Add Template
</a>

<table class="min-w-full border border-gray-300 rounded-lg overflow-hidden shadow-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2 text-left">ID</th>
            <th class="border px-4 py-2 text-left">Name</th>
            <th class="border px-4 py-2 text-left">Description</th>
            <th class="border px-4 py-2 w-[160px] text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($templates as $t): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="border px-4 py-2"><?= $t['id'] ?></td>
                <td class="border px-4 py-2 font-medium"><?= esc($t['name']) ?></td>
                <td class="border px-4 py-2 text-gray-600"><?= esc($t['description']) ?></td>
                <td class="border px-4 py-2 text-center space-x-3">
                    <a href="/templates/edit/<?= $t['id'] ?>" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="/templates/delete/<?= $t['id'] ?>"
                        class="text-red-500 hover:text-red-600"
                        title="Delete"
                        onclick="return confirm('Are you sure you want to delete this template?');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    document.querySelectorAll('a[title="Delete"]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const url = btn.getAttribute('href');
            Swal.fire({
                title: "Hapus template ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                confirmButtonColor: "#e3342f",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>