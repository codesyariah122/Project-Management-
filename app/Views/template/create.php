<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto p-6 bg-white rounded">
    <h2 class="text-2xl font-bold mb-4">Create Template</h2>
    <form method="post" action="/templates/store" class="space-y-4">
        <div>
            <label class="block font-medium mb-1">Name:</label>
            <input type="text" name="name" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Description:</label>
            <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>

        <!-- Container fitur -->
        <div id="features-container" class="space-y-4">
            <div class="feature-item border p-4 rounded bg-gray-50 relative">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold">Feature #1</h3>
                    <button type="button" class="remove-feature text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Feature Name</label>
                        <input type="text" name="feature_name[]" required class="w-full border rounded px-2 py-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Estimated Days</label>
                        <input type="number" name="estimated_days[]" required class="w-full border rounded px-2 py-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Reference Link</label>
                        <input type="url" name="reference_link[]" placeholder="https://..." class="w-full border rounded px-2 py-1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol tambah fitur -->
        <button type="button" id="add-feature"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add Feature
        </button>

        <!-- Tombol submit -->
        <button type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Create Template
        </button>
    </form>
</div>

<script>
    const container = document.getElementById('features-container');
    const addBtn = document.getElementById('add-feature');

    // Tambah fitur baru
    addBtn.addEventListener('click', function() {
        const index = container.children.length + 1;

        const newFeature = document.createElement('div');
        newFeature.classList.add('feature-item', 'border', 'p-4', 'rounded', 'bg-gray-50', 'relative');
        newFeature.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold">Feature #${index}</h3>
                <button type="button" class="remove-feature text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Feature Name</label>
                    <input type="text" name="feature_name[]" required class="w-full border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-sm font-medium">Estimated Days</label>
                    <input type="number" name="estimated_days[]" required class="w-full border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-sm font-medium">Reference Link</label>
                    <input type="url" name="reference_link[]" placeholder="https://..." class="w-full border rounded px-2 py-1">
                </div>
            </div>
        `;
        container.appendChild(newFeature);
        updateFeatureNumbers();
    });

    // Hapus fitur
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
            updateFeatureNumbers();
        }
    });

    // Update penomoran setelah hapus/tambah
    function updateFeatureNumbers() {
        const items = container.querySelectorAll('.feature-item h3');
        items.forEach((title, i) => {
            title.textContent = `Feature #${i + 1}`;
        });
    }
</script>
<?= $this->endSection() ?>