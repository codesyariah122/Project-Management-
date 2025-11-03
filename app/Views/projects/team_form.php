<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-2xl font-bold mb-4">Add Team Member</h2>

<form method="post" action="/projects/team/store" class="space-y-4 max-w-lg">
    <input type="hidden" name="project_id" value="<?= $projectId ?>">

    <div>
        <label class="block font-medium">Member Name:</label>
        <input type="text" name="member_name" required class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-medium">Role:</label>
        <select name="role" required class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="">-- Pilih Role --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= esc($r['role_name']) ?>">
                    <?= esc($r['role_name']) ?> (Rp <?= number_format($r['rate_per_day'], 0, ',', '.') ?>/hari)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="block font-medium">Cost per Day (Rp):</label>
        <input type="number" step="0.01" name="cost_per_day" required class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Otomatis terisi saat pilih role" id="cost_per_day">
    </div>

    <div>
        <label class="block font-medium">Total Days:</label>
        <input type="number" name="total_days" required class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Member</button>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const roleSelect = document.querySelector("select[name='role']");
        const costInput = document.getElementById("cost_per_day");

        const roles = <?= json_encode($roles) ?>;

        roleSelect.addEventListener("change", function() {
            const selected = roles.find(r => r.role_name === this.value);
            if (selected) {
                costInput.value = selected.rate_per_day;
            } else {
                costInput.value = '';
            }
        });
    });
</script>

<?= $this->endSection() ?>