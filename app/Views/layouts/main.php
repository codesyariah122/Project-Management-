<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Project Timeline' ?></title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    <!-- Frappe Gantt CSS -->
    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.6.1/dist/frappe-gantt.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        accent: '#10b981',
                        lightgray: '#f3f4f6'
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.tiny.cloud/1/vpr4rpywlvgro4c0lioaqr8ypb6t65emfbhhmkitaf903005/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body class="bg-gray-50 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="w-64 bg-white shadow-lg flex flex-col p-4 fixed h-full transition-transform duration-300 z-40">
        <div class="mb-6">
            <a href="/projects" class="text-xl font-bold text-primary">Project Manager</a>
        </div>

        <nav class="flex flex-col space-y-3">
            <!-- Projects -->
            <a href="/projects" class="flex items-center gap-2 text-gray-700 hover:text-primary">
                <i class="fa-solid fa-folder"></i> Projects
            </a>

            <!-- Create Project -->
            <a href="/projects/create" class="flex items-center gap-2 text-gray-700 hover:text-primary">
                <i class="fa-solid fa-plus"></i> Create Project
            </a>

            <!-- Templates -->
            <div class="border-t border-gray-200 my-3"></div>
            <a href="/templates" class="flex items-center gap-2 text-gray-700 hover:text-primary">
                <i class="fa-solid fa-layer-group"></i> Templates
            </a>
            <a href="/templates/create" class="flex items-center gap-2 text-gray-700 hover:text-primary">
                <i class="fa-solid fa-puzzle-piece"></i> Add Template
            </a>

            <!-- Roles -->
            <div class="border-t border-gray-200 my-3"></div>
            <a href="/roles" class="flex items-center gap-2 text-gray-700 hover:text-primary">
                <i class="fa-solid fa-users"></i> Roles
            </a>
        </nav>
    </aside>

    <!-- Main content -->
    <div id="mainContent" class="flex-1 ml-64 transition-all duration-300 relative">
        <main class="p-6">
            <!-- Floating Toggle Button inside card -->
            <button id="toggleSidebar"
                class="absolute top-4 left-2 w-12 h-12 flex items-center justify-center 
           bg-white shadow-md rounded-full text-gray-700 hover:text-primary 
           transition z-20">
                <i class="fa-solid fa-angles-left text-lg"></i>
            </button>
            <div class="w-full max-w-6xl mx-auto bg-white shadow rounded-lg p-6 relative overflow-hidden">
                <!-- Dynamic content -->
                <div class="mt-10">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://unpkg.com/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = toggleBtn.querySelector('i');

        let sidebarOpen = true;

        toggleBtn.addEventListener('click', () => {
            sidebarOpen = !sidebarOpen;
            if (sidebarOpen) {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.add('ml-64');
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-angles-left');
            } else {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                toggleIcon.classList.remove('fa-angles-left');
                toggleIcon.classList.add('fa-bars');
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>