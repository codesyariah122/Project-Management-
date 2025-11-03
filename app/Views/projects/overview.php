<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="text-3xl font-bold mb-6 text-center text-blue-600">
    🚀 Project Overview: <?= esc($project['name']) ?>
</h1>

<div class="text-center text-gray-600 mb-8"><?= $project['description'] ?></div>

<!-- Tech Stack -->
<div class="text-center mb-10">
    <h3 class="text-lg font-semibold text-gray-700 mb-2">🧠 Tech Stack</h3>
    <div class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-lg border border-gray-300 shadow-sm">
        <?= esc($project['tech_stack']) ?>
    </div>
</div>

<!-- Toolbar -->
<div class="flex justify-between items-center mb-4">
    <div class="space-x-2">
        <button id="featureView" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Per Fitur</button>
        <button id="roleView" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">Per Role</button>
        <button id="dayView" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Day</button>
        <button id="weekView" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Week</button>
        <button id="monthView" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Month</button>
    </div>
    <div class="space-x-2">
        <a href="/projects/team/add/<?= $project['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">+ Add Team</a>
        <a href="/projects/exportExcel/<?= $project['id'] ?>" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">📊 Export Excel</a>
        <button id="exportBtn" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">📸 Export PNG</button>
    </div>
</div>

<!-- Gantt Chart Container -->
<div id="gantt" class="border rounded-lg bg-white shadow-md w-full overflow-x-auto" style="height: 500px;"></div>

<!-- Total Duration Label -->
<div id="totalDuration" class="text-center text-lg font-semibold text-gray-700 mt-4"></div>

<!-- Role Cost Breakdown -->
<div class="bg-white p-6 rounded-lg shadow mt-10 mb-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">💼 Role Cost Breakdown</h2>

    <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Role</th>
                <th class="border px-4 py-2 text-right">Total Days</th>
                <th class="border px-4 py-2 text-right">Rate / Day</th>
                <th class="border px-4 py-2 text-right">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($role_cost_data as $role => $data): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= esc($role) ?></td>
                    <td class="border px-4 py-2 text-right"><?= esc($data['days']) ?> hari</td>
                    <td class="border px-4 py-2 text-right"><?= esc($data['rate_formatted']) ?></td>
                    <td class="border px-4 py-2 text-right font-semibold text-green-700"><?= esc($data['cost_formatted']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-gray-100">
            <tr>
                <th colspan="3" class="border px-4 py-2 text-right font-bold">TOTAL HPP</th>
                <th class="border px-4 py-2 text-right font-bold text-blue-700">
                    <?= esc($total_hpp_formatted) ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Features List -->
<div class="bg-gray-50 p-4 rounded-lg border border-gray-300 mb-6">
    <h2 class="text-lg font-semibold mb-2 text-gray-700">🧩 Project Features</h2>
    <ul class="list-disc pl-6 text-gray-700">
        <?php foreach ($features as $f): ?>
            <li><?= esc($f['feature_name']) ?> (<?= esc($f['estimated_days']) ?> hari)</li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const features = <?= json_encode($features) ?>;
        const roleData = <?= json_encode($role_cost_data) ?>;
        const durationLabel = document.getElementById("totalDuration");

        let gantt;
        const ganttEl = document.getElementById("gantt");

        // Hitung total durasi dari fitur
        function calcFeatureDays() {
            return features.reduce((sum, f) => sum + Number(f.estimated_days || 0), 0);
        }

        // Hitung total durasi dari role
        function calcRoleDays() {
            return Object.values(roleData).reduce((sum, d) => sum + Number(d.days || 0), 0);
        }

        function updateTotalLabel(mode) {
            const total = mode === "feature" ? calcFeatureDays() : calcRoleDays();
            const colorClass = mode === "feature" ?
                "bg-blue-100 text-blue-700 border border-blue-300" :
                "bg-purple-100 text-purple-700 border border-purple-300";

            durationLabel.innerHTML = `
        <div class="inline-block px-4 py-2 rounded-lg mt-3 ${colorClass}">
            🕓 Total Durasi (${mode === "feature" ? "Per Fitur" : "Per Role"}): 
            <span class="font-semibold">${total} Hari</span>
        </div>
    `;
        }


        function renderFeatureView() {
            if (!features || features.length === 0) {
                ganttEl.innerHTML = `<div class="p-8 text-center text-gray-500">Belum ada fitur yang terdaftar untuk project ini.</div>`;
                durationLabel.innerHTML = "";
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

            gantt = new Gantt("#gantt", tasks, {
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

            updateTotalLabel("feature");
        }

        function renderRoleView() {
            const today = new Date();
            const startBase = today.toISOString().split("T")[0];

            const tasks = Object.entries(roleData).map(([role, data], i) => {
                const start = new Date(today);
                const end = new Date(today);
                end.setDate(today.getDate() + data.days);

                return {
                    id: role.replace(/\s+/g, "_"),
                    name: role,
                    start: start.toISOString().split("T")[0],
                    end: end.toISOString().split("T")[0],
                    progress: 100,
                    custom_class: "role-bar-" + i
                };
            });

            gantt = new Gantt("#gantt", tasks, {
                view_mode: "Day",
                date_format: "YYYY-MM-DD",
                language: "en",
                custom_popup_html: function(task) {
                    const data = roleData[task.name];
                    return `
                        <div class="p-3 bg-white rounded shadow text-sm border">
                            <h5 class="font-semibold text-indigo-600">${task.name}</h5>
                            <p class="text-gray-600 mt-1">📅 ${task.start} → ${task.end}</p>
                            <p>Durasi: <b>${data.days} hari</b></p>
                            <p>Rate: <b>${data.rate_formatted}</b></p>
                            <p>Total Cost: <b>${data.cost_formatted}</b></p>
                        </div>`;
                }
            });

            updateTotalLabel("role");
        }

        // Default view: per fitur
        renderFeatureView();

        // Button event handlers
        document.getElementById("featureView").addEventListener("click", renderFeatureView);
        document.getElementById("roleView").addEventListener("click", renderRoleView);
        document.getElementById("dayView").addEventListener("click", () => gantt.change_view_mode("Day"));
        document.getElementById("weekView").addEventListener("click", () => gantt.change_view_mode("Week"));
        document.getElementById("monthView").addEventListener("click", () => gantt.change_view_mode("Month"));

        // Export PNG
        document.getElementById("exportBtn").addEventListener("click", () => {
            html2canvas(document.getElementById("gantt"), {
                backgroundColor: "#ffffff"
            }).then(canvas => {
                const link = document.createElement("a");
                link.download = "project-timeline.png";
                link.href = canvas.toDataURL("image/png");
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

    .role-bar-0 .bar {
        fill: #2563EB;
    }

    .role-bar-1 .bar {
        fill: #059669;
    }

    .role-bar-2 .bar {
        fill: #D97706;
    }

    .role-bar-3 .bar {
        fill: #DC2626;
    }

    .role-bar-4 .bar {
        fill: #7C3AED;
    }

    .role-bar-5 .bar {
        fill: #DB2777;
    }
</style>

<?= $this->endSection() ?>