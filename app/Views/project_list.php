<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">All Projects</h2>

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
        <?php foreach ($projects as $p): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="border px-4 py-2"><?= $p['id'] ?></td>
                <td class="border px-4 py-2 font-medium"><?= esc($p['name']) ?></td>
                <td class="border px-4 py-2 text-gray-600"><?= $p['description'] ?></td>
                <td class="border px-4 py-2 text-center space-x-3 w-[160px]">
                    <!-- View -->
                    <a href="/projects/timeline/<?= $p['id'] ?>"
                        class="text-blue-500 hover:text-blue-700 transition"
                        title="View Timeline">
                        <i class="fa-solid fa-eye"></i>
                    </a>

                    <!-- Edit -->
                    <a href="/projects/edit/<?= $p['id'] ?>"
                        class="text-yellow-500 hover:text-yellow-600 transition"
                        title="Edit Project">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

                    <!-- Export -->
                    <a href="/projects/exportExcel/<?= $p['id'] ?>"
                        class="text-green-500 hover:text-green-600 transition"
                        title="Export Excel">
                        <i class="fa-solid fa-file-excel"></i>
                    </a>

                    <!-- Delete -->
                    <a href="#"
                        class="text-red-500 hover:text-red-600 transition delete-project"
                        data-id="<?= $p['id'] ?>"
                        title="Delete Project">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-project').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const projectId = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This project will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ED2D56',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/projects/delete/${projectId}`;
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>