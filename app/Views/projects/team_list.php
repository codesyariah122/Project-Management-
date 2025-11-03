<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">Project Team</h2>
<a href="/projects/team/add/<?= $projectId ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4 inline-block">+ Add Member</a>

<table class="min-w-full border border-gray-300 mt-2">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Role</th>
            <th class="border px-4 py-2">Cost/Day</th>
            <th class="border px-4 py-2">Days</th>
            <th class="border px-4 py-2">Total Cost</th>
        </tr>
    </thead>
    <tbody>
        <?php $grandTotal = 0;
        foreach ($teams as $t): $grandTotal += $t['total_cost']; ?>
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2"><?= $t['member_name'] ?></td>
                <td class="border px-4 py-2"><?= $t['role'] ?></td>
                <td class="border px-4 py-2 text-right">Rp <?= number_format($t['cost_per_day'], 0, ',', '.') ?></td>
                <td class="border px-4 py-2 text-center"><?= $t['total_days'] ?></td>
                <td class="border px-4 py-2 text-right font-semibold">Rp <?= number_format($t['total_cost'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="bg-gray-100 font-bold">
            <td colspan="4" class="text-right px-4 py-2">Grand Total</td>
            <td class="border px-4 py-2 text-right text-green-700">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
        </tr>
    </tbody>
</table>
<?= $this->endSection() ?>