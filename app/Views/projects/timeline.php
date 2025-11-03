<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold mb-6 text-center text-blue-600">📊 Project Timeline</h1>

<!-- Toolbar -->
<div class="flex justify-between items-center mb-4">
    <div class="space-x-2">
        <button id="dayView" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Day</button>
        <button id="weekView" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Week</button>
        <button id="monthView" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">Month</button>
    </div>
    <div class="space-x-2">
        <button id="exportPng" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">📸 Export PNG</button>
        <button id="exportPdf" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">📄 Export PDF</button>
    </div>
</div>

<!-- Gantt Container -->
<div id="gantt" class="border rounded-lg bg-white shadow-md w-full overflow-x-auto" style="height: 600px;"></div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<!-- Frappe Gantt -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<!-- html2canvas + jsPDF -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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

        // === Inisialisasi Frappe Gantt ===
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
            },
            on_date_change: function(task, start, end) {
                // === Ketika user drag & drop bar ===
                updateTaskDate(task.id, start, end);
            }
        });

        function formatDate(date) {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        // === Fungsi Update via AJAX ===
        function updateTaskDate(taskId, start, end) {
            const payload = {
                id: taskId,
                start_date: formatDate(start),
                end_date: formatDate(end)
            };

            console.log("🌀 Update Task:", payload);

            fetch('<?= base_url("/projects/updateTimeline") ?>', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    console.log("✅ Response:", data);
                    if (data.success) {
                        showToast("✅ Timeline berhasil diperbarui!");
                    } else {
                        showToast("⚠️ Gagal memperbarui timeline.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("❌ Terjadi kesalahan koneksi.");
                });
        }

        // === Toast Notification ===
        function showToast(msg) {
            const toast = document.createElement("div");
            toast.textContent = msg;
            toast.className = "fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded shadow-lg animate-bounce";
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // === Switch View Mode ===
        document.getElementById('dayView').addEventListener('click', () => gantt.change_view_mode('Day'));
        document.getElementById('weekView').addEventListener('click', () => gantt.change_view_mode('Week'));
        document.getElementById('monthView').addEventListener('click', () => gantt.change_view_mode('Month'));

        // === Export PNG ===
        document.getElementById('exportPng').addEventListener('click', () => {
            const ganttElement = document.getElementById('gantt');
            html2canvas(ganttElement, {
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'project-timeline.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });

        // === Export PDF ===
        document.getElementById('exportPdf').addEventListener('click', async () => {
            const {
                jsPDF
            } = window.jspdf;
            const ganttElement = document.getElementById('gantt');
            const canvas = await html2canvas(ganttElement, {
                backgroundColor: '#ffffff',
                scale: 2
            });
            const imgData = canvas.toDataURL('image/png');

            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: 'a4'
            });
            const pageWidth = pdf.internal.pageSize.getWidth();

            // Header
            pdf.setFontSize(18);
            pdf.text('📊 Project Timeline', pageWidth / 2, 15, {
                align: 'center'
            });

            const imgWidth = pageWidth - 20;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, 'PNG', 10, 25, imgWidth, imgHeight);
            pdf.save('project-timeline.pdf');
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

    .gantt-container {
        overflow-x: auto;
    }
</style>
<?= $this->endSection() ?>