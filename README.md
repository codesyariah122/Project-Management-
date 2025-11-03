# 🚀 Project Timeline & Role Management System

Sebuah aplikasi web berbasis **CodeIgniter 4** yang dirancang untuk mengelola proyek, peran tim, serta timeline pengembangan dengan tampilan modern dan ringan.  
Project ini mendukung manajemen feature, role, serta estimasi waktu dengan otomatisasi perhitungan durasi dan biaya.

---

## 🧱 Tech Stack

- **Backend**: [CodeIgniter 4](https://codeigniter.com/)
- **Database**: MySQL
- **Frontend**: TailwindCSS + SweetAlert2 + Font Awesome
- **Icons**: [Font Awesome 6.5.2](https://cdnjs.com/libraries/font-awesome)
- **Server**: PHP 8+, Apache/Nginx

---

## ⚙️ Fitur Utama

### 📋 1. Manajemen Proyek
- Tambah, edit, dan hapus proyek.
- Setiap proyek memiliki:
  - Nama proyek
  - Deskripsi singkat
  - Business Type → otomatis memilih template dan tech stack
  - Estimasi waktu pengerjaan
  - Tech Stack summary

### 🧩 2. Manajemen Role
- Kelola daftar role (seperti Developer, UI Designer, QA Tester).
- Setiap role memiliki:
  - **Nama Role**
  - **Rate per Day (Rp)** — mendukung format nominal dan rentang (contoh: `1500000 - 3000000`)
- Notifikasi berhasil atau gagal menggunakan **SweetAlert2**.

### 🕒 3. Timeline Management
- Tambahkan fitur (feature) per proyek.
- Hitung otomatis:
  - Durasi dari `start_date` ke `end_date`.
  - Total biaya berdasarkan `rate_per_day`.
- Tampilan data tabel rapi dengan border dan hover highlight.

### 📊 4. Project Overview Dashboard
- Menampilkan ringkasan proyek:
  - Nama proyek & deskripsi
  - Tech Stack
  - Daftar role dan rate
  - Timeline feature list
- Tampilan intuitif dengan Tailwind dan ikon dari Font Awesome.

---

## 📁 Struktur Folder

```bash
project-root/
├── app/
│ ├── Controllers/
│ │ ├── ProjectController.php
│ │ ├── RoleController.php
│ │ └── FeatureController.php
│ ├── Models/
│ │ ├── ProjectModel.php
│ │ ├── RoleModel.php
│ │ └── FeatureModel.php
│ ├── Views/
│ │ ├── layouts/
│ │ │ └── main.php
│ │ ├── projects/
│ │ │ ├── index.php
│ │ │ ├── add.php
│ │ │ ├── edit.php
│ │ │ └── overview.php
│ │ └── roles/
│ │ ├── index.php
│ │ ├── add.php
│ │ └── edit.php
├── public/
│ ├── css/
│ ├── js/
│ └── index.php
└── writable/
```  


---

## 🧠 Instalasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/your-username/project-timeline-ci4.git
cd project-timeline-ci4
```  

2️⃣ Install Dependencies

Pastikan Composer sudah terpasang.  

```bash
composer install
```  

3️⃣ Setup Environment

Buat file .env dari template:  

```bash
cp env .env
```  

Edit bagian database:  
```pgsql
database.default.hostname = localhost
database.default.database = project_timeline
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```  

4️⃣ Migrasi Database

Jalankan perintah berikut untuk membuat tabel:  

Atau jika kamu memakai SQL dump:  
```sql 
mysql -u root -p project_timeline < database.sql
```  
**Seeder Database**
```bash
php spark db:seed ProjectRolesSeeder
php spark db:seed ProjectTeamsSeeder
```

5️⃣ Jalankan Server  
```bash
php spark serve
```  

Akses melalui:
```bash
http://localhost:8080
```  

💬 Notifikasi & Interaksi

- SweetAlert2 digunakan untuk:

- Notifikasi sukses tambah/update role.

- Notifikasi error input form.

- Konfirmasi sebelum hapus data.

- Number input otomatis memformat nominal dengan Rp. di tampilan tabel.

🧭 Roadmap (Next Features)  

- Export timeline ke Excel/PDF
- Dashboard progress bar per proyek
- Upload dokumentasi per feature
- Integrasi login multi-role (Admin, PM, Developer)

👨‍💻 Author

Puji Ermanto
🛠️ Fullstack Developer | Software Engineer Freelance | CodeIgniter Enthusiast
🌐 https://pujiermanto-portfolio.vercel.app

📜 License

MIT License © 2025 Puji Ermanto


# CodeIgniter 4 Application Starter  



## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
