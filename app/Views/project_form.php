<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">Create Project</h2>

<form method="post" action="/projects/store" class="space-y-4 max-w-lg">
    <div>
        <label class="block font-medium">Name:</label>
        <input type="text" name="name" required class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-medium">Description:</label>
        <textarea id="descriptionEditor" name="description" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
    </div>

    <div>
        <label class="block font-medium">Budget (Rp):</label>
        <input type="number" name="budget" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan nilai penawaran proyek">
    </div>

    <div>
        <label class="block font-medium">Business Type:</label>
        <select id="businessType" name="business_type" required class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="">-- Select Business Type --</option>

            <optgroup label="🏪 Lifestyle & Retail">
                <option value="Skincare">Skincare / Beauty</option>
                <option value="E-Commerce">E-Commerce / Online Store</option>
                <option value="Food & Beverage">Food & Beverage / Restaurant / Cafe</option>
                <option value="Fashion">Fashion / Apparel</option>
            </optgroup>

            <optgroup label="🏨 Hospitality & Services">
                <option value="Hotel">Hotel / Hospitality</option>
                <option value="Travel">Travel / Tour Agency</option>
                <option value="Event Management">Event Management / Wedding Organizer</option>
            </optgroup>

            <optgroup label="🎓 Education & E-Learning">
                <option value="Education">Education / School / University</option>
                <option value="E-Learning">E-Learning Platform</option>
                <option value="Online Course">Online Course / Academy</option>
                <option value="LMS">LMS System (Learning Management System)</option>
                <option value="Training Center">Training Center / Certification</option>
            </optgroup>

            <optgroup label="🏢 Professional & Corporate">
                <option value="Corporate">Corporate / Company Profile</option>
                <option value="Consulting">Consulting / Professional Services</option>
                <option value="Finance">Finance / Accounting / Tax</option>
                <option value="Law Firm">Law Firm / Legal Services</option>
                <option value="Property">Real Estate / Property Developer</option>
                <option value="Healthcare">Healthcare / Clinic / Hospital</option>
                <option value="Government">Government / Institution</option>
            </optgroup>

            <optgroup label="💻 Technology & Systems">
                <option value="POS">POS System / Retail Management</option>
                <option value="ERP">ERP / Business Management System</option>
                <option value="CRM">CRM / Customer Management</option>
                <option value="Startup">Startup / SaaS / Tech Platform</option>
            </optgroup>

            <optgroup label="🌐 Others">
                <option value="Portfolio">Portfolio / Personal Branding</option>
                <option value="Community">Community / Organization</option>
                <option value="Other">Other</option>
            </optgroup>
        </select>
    </div>


    <div>
        <label class="block font-medium">Template:</label>
        <select id="templateSelect" name="template_id" class="w-full border border-gray-300 rounded px-3 py-2">
            <?php foreach ($templates as $t): ?>
                <option value="<?= $t['id'] ?>" data-business="<?= $t['business_type'] ?? '' ?>">
                    <?= $t['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="block font-medium">Tech Stack:</label>
        <input type="text" name="tech_stack" id="techStack" readonly
            class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
            placeholder="Tech stack akan otomatis terisi">
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Create Project
    </button>
</form>

<script>
    tinymce.init({
        selector: '#descriptionEditor',
        height: 300,
        menubar: false,
        plugins: 'link image code lists',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
        apiKey: 'vpr4rpywlvgro4c0lioaqr8ypb6t65emfbhhmkitaf903005'
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const businessSelect = document.getElementById('businessType');
        const templateSelect = document.getElementById('templateSelect');
        const nameInput = document.querySelector('input[name="name"]');
        const techInput = document.getElementById('techStack');

        const templates = <?= $templates_json ?>;
        const techStackMap = <?= $techStackMap ?>;

        // 💡 Mapping kategori yang menggunakan template E-Commerce
        const ecommerceTypes = [
            'Consulting', 'Finance', 'Law Firm', 'Property', 'Healthcare',
            'Government', 'ERP', 'CRM', 'Portfolio', 'Community',
            'Travel', 'Event Management', 'Food & Beverage', 'Fashion'
        ];

        businessSelect.addEventListener('change', () => {
            const selectedType = businessSelect.value;
            let matched = false;

            // Reset pilihan template
            for (let option of templateSelect.options) {
                option.selected = false;
            }

            // 🔎 Tentukan kategori target
            let targetType = selectedType;
            if (ecommerceTypes.includes(selectedType)) {
                targetType = 'E-Commerce'; // fallback ke E-Commerce
            }

            // ✅ Pilih template berdasarkan kategori target
            for (let option of templateSelect.options) {
                const business = option.getAttribute('data-business');
                if (business && business.toLowerCase() === targetType.toLowerCase()) {
                    option.selected = true;
                    matched = true;

                    if (!nameInput.value.trim()) {
                        nameInput.value = option.text.trim();
                    }
                    break;
                }
            }

            // Jika tidak ada match sama sekali, reset
            if (!matched) {
                templateSelect.selectedIndex = 0;
            }

            // ✅ Isi otomatis tech stack sesuai business type asli (bukan fallback)
            techInput.value = techStackMap[selectedType] || '';
        });
    });
</script>



<?= $this->endSection() ?>