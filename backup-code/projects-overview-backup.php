<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold mb-6 text-center text-blue-600">
    🚀 Project Overview: <?= esc($project['name']) ?>
</h1>

<!-- Description -->
<div class="text-center text-gray-600 mb-8"><?= $project['description'] ?></div>

<!-- Toolbar -->
<div class="flex justify-between items-center mb-4">
    <div class="space-x-2">
        <button id="dayView" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Day</button>
        <button id="weekView" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Week</button>
        <button id="monthView" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">Month</button>
    </div>
    <div class="space-x-2">
        <a href="/projects/team/add/<?= $project['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">+ Add Team</a>
        <a href="/projects/exportExcel/<?= $project['id'] ?>" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">📊 Export Excel</a>
        <button id="exportBtn" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">📸 Export PNG</button>
    </div>
</div>

<!-- Gantt Container -->
<div id="gantt" class="border rounded-lg bg-white shadow-md w-full overflow-x-auto" style="height: 500px;"></div>

<!-- Timeline Table -->
<h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-800">📋 Project Timeline & Team</h2>

<?php if (empty($features)): ?>
    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded">
        Belum ada fitur yang terdaftar untuk project ini.
    </div>
<?php else: ?>
    <table class="min-w-full border border-gray-300 mt-2">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Feature</th>
                <th class="border px-4 py-2">Start</th>
                <th class="border px-4 py-2">End</th>
                <th class="border px-4 py-2">Progress</th>
                <th class="border px-4 py-2">Reference</th>
                <th class="border px-4 py-2">Team Members</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($features as $f):
                // Ambil tim yang sesuai role atau project_id jika ada
                $assignedTeam = array_filter($teams, fn($t) => true); // sementara semua tim ditampilkan, bisa disesuaikan role nanti
            ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= esc($f['feature_name']) ?></td>
                    <td class="border px-4 py-2"><?= esc($f['start_date']) ?></td>
                    <td class="border px-4 py-2"><?= esc($f['end_date']) ?></td>
                    <td class="border px-4 py-2 text-center"><?= rand(10, 90) ?>%</td>
                    <td class="border px-4 py-2">
                        <?php if (!empty($f['reference_link'])): ?>
                            <a href="<?= esc($f['reference_link']) ?>" target="_blank" class="text-blue-500 underline">View</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="border px-4 py-2">
                        <?php foreach ($assignedTeam as $t): ?>
                            <div class="text-sm">
                                <b><?= esc($t['member_name']) ?></b> (<?= esc($t['role']) ?>)
                            </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Team Breakdown -->
<h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-800">👥 Project Team</h2>

<?php if (empty($teams)): ?>
    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded">
        Belum ada anggota tim untuk project ini.
    </div>
<?php else: ?>
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
                    <td class="border px-4 py-2"><?= esc($t['member_name']) ?></td>
                    <td class="border px-4 py-2"><?= esc($t['role']) ?></td>
                    <td class="border px-4 py-2 text-right">Rp <?= number_format($t['cost_per_day'], 0, ',', '.') ?></td>
                    <td class="border px-4 py-2 text-center"><?= esc($t['total_days']) ?></td>
                    <td class="border px-4 py-2 text-right font-semibold text-green-700">Rp <?= number_format($t['total_cost'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="bg-gray-100 font-bold">
                <td colspan="4" class="text-right px-4 py-2">Grand Total</td>
                <td class="border px-4 py-2 text-right text-green-700">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-800">💰 Project HPP Summary</h2>
    <table class="min-w-full border border-gray-300 mt-2">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2 text-center">Total Hari</th>
                <th class="border px-4 py-2 text-right">Rate/Hari</th>
                <th class="border px-4 py-2 text-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($role_cost_data as $role => $d): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= esc($role) ?></td>
                    <td class="border px-4 py-2 text-center"><?= esc($d['days']) ?></td>
                    <td class="border px-4 py-2 text-right">Rp <?= number_format($d['rate'], 0, ',', '.') ?></td>
                    <td class="border px-4 py-2 text-right font-semibold text-green-700">Rp <?= number_format($d['cost'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="bg-gray-100 font-bold">
                <td colspan="3" class="text-right px-4 py-2">Total HPP Project</td>
                <td class="border px-4 py-2 text-right text-green-700">Rp <?= number_format($total_hpp, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

<?php endif; ?>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<!-- html2canvas untuk export -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const features = <?= json_encode($features) ?>;

        if (!features || features.length === 0) {
            document.getElementById('gantt').innerHTML = `
            <div class="p-8 text-center text-gray-500">
                Belum ada fitur yang terdaftar untuk project ini.
            </div>`;
            return;
        }

        const tasks = features.map((f, i) => ({
            id: f.id,
            name: f.feature_name,
            start: f.start_date,
            end: f.end_date,
            progress: Math.floor(Math.random() * 80) + 10,
            dependencies: i > 0 ? features[i - 1].id : null,
            custom_class: "bar-" + i
        }));

        let gantt = new Gantt("#gantt", tasks, {
            view_mode: "Day",
            date_format: "YYYY-MM-DD",
            language: "en",
            custom_popup_html: function(task) {
                const ref = features.find(f => f.id == task.id)?.reference_link || "#";
                return `
                <div class="p-3 bg-white rounded shadow text-sm border">
                    <h5 class="font-semibold text-blue-600">${task.name}</h5>
                    <p class="text-gray-600 mt-1">📅 ${task.start} → ${task.end}</p>
                    <p class="mt-1">Progress: <b>${task.progress}%</b></p>
                    <a href="${ref}" target="_blank" class="text-blue-500 underline mt-2 block">🔗 View Reference</a>
                </div>`;
            }
        });

        // Switch View Mode
        document.getElementById('dayView').addEventListener('click', () => gantt.change_view_mode('Day'));
        document.getElementById('weekView').addEventListener('click', () => gantt.change_view_mode('Week'));
        document.getElementById('monthView').addEventListener('click', () => gantt.change_view_mode('Month'));

        // Export PNG
        document.getElementById('exportBtn').addEventListener('click', () => {
            html2canvas(document.getElementById('gantt'), {
                    backgroundColor: '#ffffff'
                })
                .then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'project-timeline.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
        });
    });
</script>

<style>
    .bar-0 .bar {
        fill: #3B82F6;
    }

    .bar-1 .bar {
        fill: #10B981;
    }

    .bar-2 .bar {
        fill: #F59E0B;
    }

    .bar-3 .bar {
        fill: #EF4444;
    }

    .bar-4 .bar {
        fill: #8B5CF6;
    }

    .bar-5 .bar {
        fill: #EC4899;
    }
</style>
<?= $this->endSection() ?>