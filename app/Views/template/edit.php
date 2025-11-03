<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto p-6 bg-white rounded">
    <h2 class="text-2xl font-bold mb-4">Edit Template</h2>
    <form method="post" action="/templates/update/<?= $template['id'] ?>" class="space-y-4">
        <div>
            <label class="block font-medium mb-1">Name:</label>
            <input type="text" name="name" value="<?= esc($template['name']) ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Description:</label>
            <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2"><?= esc($template['description']) ?></textarea>
        </div>

        <!-- Container fitur -->
        <div id="features-container" class="space-y-4">
            <!-- fitur di-generate lewat JS -->
        </div>

        <!-- Tombol tambah fitur -->
        <button type="button" id="add-feature"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add Feature
        </button>

        <!-- Tombol submit -->
        <button type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Update Template
        </button>
    </form>
</div>

<script>
    const container = document.getElementById('features-container');
    const addBtn = document.getElementById('add-feature');

    // Data awal dari PHP (decode JSON template)
    const existingFeatures = <?= $template['features_json'] ? $template['features_json'] : '[]' ?>;

    // Render fitur awal
    existingFeatures.forEach((f, i) => {
        container.appendChild(createFeatureItem(i + 1, f.feature_name, f.estimated_days, f.reference_link));
    });

    if (existingFeatures.length === 0) {
        container.appendChild(createFeatureItem(1, '', '', ''));
    }

    addBtn.addEventListener('click', function() {
        const index = container.children.length + 1;
        container.appendChild(createFeatureItem(index, '', '', ''));
        updateFeatureNumbers();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
            updateFeatureNumbers();
        }
    });

    function createFeatureItem(index, name = '', days = '', link = '') {
        const div = document.createElement('div');
        div.classList.add('feature-item', 'border', 'p-4', 'rounded', 'bg-gray-50', 'relative');
        div.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold">Feature #${index}</h3>
                <button type="button" class="remove-feature text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Feature Name</label>
                    <input type="text" name="feature_name[]" value="${name}" required class="w-full border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-sm font-medium">Estimated Days</label>
                    <input type="number" name="estimated_days[]" value="${days}" required class="w-full border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-sm font-medium">Reference Link</label>
                    <input type="url" name="reference_link[]" value="${link}" placeholder="https://..." class="w-full border rounded px-2 py-1">
                </div>
            </div>
        `;
        return div;
    }

    function updateFeatureNumbers() {
        const items = container.querySelectorAll('.feature-item h3');
        items.forEach((title, i) => {
            title.textContent = `Feature #${i + 1}`;
        });
    }
</script>
<?= $this->endSection() ?>