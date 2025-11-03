<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">Edit Project</h2>
<form method="post" action="/projects/update/<?= $project['id'] ?>" class="space-y-4 max-w-lg">
    <div>
        <label class="block font-medium">Name:</label>
        <input type="text" name="name" value="<?= esc($project['name']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-medium">Description:</label>
        <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2"><?= esc($project['description']) ?></textarea>
    </div>

    <div>
        <label class="block font-medium">Business Type:</label>
        <select name="business_type" required class="w-full border border-gray-300 rounded px-3 py-2">
            <?php
            $types = ["Skincare", "E-Commerce", "Food & Beverage", "Hotel", "Education", "Corporate", "Startup", "Other"];
            foreach ($types as $type): ?>
                <option value="<?= $type ?>" <?= ($project['business_type'] == $type) ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="block font-medium">Template:</label>
        <select name="template_id" class="w-full border border-gray-300 rounded px-3 py-2">
            <?php foreach ($templates as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($project['template_id'] == $t['id']) ? 'selected' : '' ?>><?= $t['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Project</button>
</form>
<?= $this->endSection() ?>