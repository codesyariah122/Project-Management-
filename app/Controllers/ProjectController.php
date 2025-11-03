<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\FeatureModel;
use App\Models\TemplateModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProjectController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }
    // List project
    public function index()
    {
        $projectModel = new ProjectModel();
        $projects = $projectModel->findAll();
        return view('project_list', ['projects' => $projects]);
    }

    public function create()
    {
        $templateModel = new \App\Models\TemplateModel();
        $templates = $templateModel->findAll();

        $techStackMap = [
            'Skincare'          => 'PHP, CodeIgniter 4, MySQL, Tailwind CSS',
            'E-Commerce'        => 'PHP, Laravel, MySQL, Vue.js',
            'Food & Beverage'   => 'PHP, CodeIgniter 4, MySQL, Bootstrap',
            'Fashion'           => 'PHP, Laravel, MySQL, React',
            'Hotel'             => 'PHP, CodeIgniter 4, MySQL, jQuery',
            'Travel'            => 'PHP, Laravel, MySQL, Vue.js',
            'Event Management'  => 'PHP, Laravel, MySQL, React',
            'Education'         => 'PHP, CodeIgniter 4, MySQL, Tailwind CSS',
            'E-Learning'        => 'PHP, Laravel, MySQL, Vue.js',
            'Online Course'     => 'PHP, Laravel, MySQL, React',
            'LMS'               => 'PHP, CodeIgniter 4, MySQL, jQuery',
            'Training Center'   => 'PHP, Laravel, MySQL, Vue.js',
            'Corporate'         => 'PHP, Laravel, MySQL, React',
            'Consulting'        => 'PHP, Laravel, MySQL, Bootstrap',
            'Finance'           => 'PHP, CodeIgniter 4, MySQL, jQuery',
            'Law Firm'          => 'PHP, CodeIgniter 4, MySQL, Tailwind CSS',
            'Property'          => 'PHP, Laravel, MySQL, Vue.js',
            'Healthcare'        => 'PHP, CodeIgniter 4, MySQL, Bootstrap',
            'Government'        => 'PHP, CodeIgniter 4, MySQL, jQuery',
            'POS'               => 'PHP, Laravel, MySQL, React',
            'ERP'               => 'PHP, Laravel, MySQL, Vue.js',
            'CRM'               => 'PHP, Laravel, MySQL, React',
            'Startup'           => 'PHP, Laravel, MySQL, Vue.js',
            'Portfolio'         => 'PHP, CodeIgniter 4, MySQL, Tailwind CSS',
            'Community'         => 'PHP, CodeIgniter 4, MySQL, Bootstrap',
            'Other'             => 'PHP, CodeIgniter 4, MySQL',
            'WordPress'         => 'PHP, WordPress, MySQL, Elementor / Classic Editor'
        ];

        // echo "<pre>";
        // dd($templates);
        // echo "</pre>";
        // die;

        return view('project_form', [
            'title'         => 'Create Project',
            'templates'     => $templates,
            'templates_json' => json_encode($templates),  // ✅ tambahkan ini
            'techStackMap'  => json_encode($techStackMap)
        ]);
    }

    // Submit project baru
    public function store()
    {
        $projectModel  = new \App\Models\ProjectModel();
        $featureModel  = new \App\Models\FeatureModel();
        $templateModel = new \App\Models\TemplateModel();

        $data = [
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'business_type' => $this->request->getPost('business_type'),
            'template_id'   => $this->request->getPost('template_id'),
            'tech_stack'    => $this->request->getPost('tech_stack'),
            'budget'        => $this->request->getPost('budget'),
        ];

        // Simpan project
        $projectId = $projectModel->insert($data);

        // Ambil template dan fitur-fiturnya
        $templateId = $this->request->getPost('template_id');
        $template = $templateModel->find($templateId);

        if ($template && !empty($template['features_json'])) {
            $features = json_decode($template['features_json'], true);

            if (is_array($features)) {
                $featureModel = new \App\Models\FeatureModel();

                // Mulai dari tanggal hari ini
                $startDate = new \DateTime();

                foreach ($features as $f) {
                    // Jika fitur berupa string (bukan array)
                    if (is_string($f)) {
                        $featureName = $f;
                        $estimatedDays = 5;
                        $referenceLink = null;
                    } else {
                        $featureName = $f['feature_name'] ?? 'Untitled Feature';
                        $estimatedDays = isset($f['estimated_days']) ? (int) $f['estimated_days'] : 5;
                        $referenceLink = $f['reference_link'] ?? null;
                    }

                    $endDate = clone $startDate;
                    $endDate->modify("+{$estimatedDays} days");

                    $featureModel->insert([
                        'project_id'      => $projectId,
                        'feature_name'    => $featureName,
                        'estimated_days'  => $estimatedDays,
                        'reference_link'  => $referenceLink,
                        'start_date'      => $startDate->format('Y-m-d'),
                        'end_date'        => $endDate->format('Y-m-d'),
                        'created_at'      => date('Y-m-d H:i:s'),
                    ]);

                    $startDate = clone $endDate;
                }
            }
        }

        // ======== AUTO-GENERATE PROJECT TEAM ========
        $roleModel = new \App\Models\ProjectRoleModel();
        $teamModel = new \App\Models\ProjectTeamModel();

        // Definisi fase kerja (bisa nanti dijadikan table terpisah kalau mau)
        $phases = [
            ['days' => 10, 'roles' => ['Project Manager', 'UI/UX Designer', 'Backend Developer', 'Frontend Developer']],
            ['days' => 15, 'roles' => ['Backend Developer', 'Project Manager', 'QA Engineer']],
            ['days' => 15, 'roles' => ['Frontend Developer', 'UI/UX Designer', 'Backend Developer', 'QA Engineer', 'Project Manager']],
            ['days' => 10, 'roles' => ['Frontend Developer', 'Backend Developer', 'QA Engineer', 'Project Manager']],
            ['days' => 5,  'roles' => ['Project Manager', 'QA Engineer', 'Backend Developer', 'Frontend Developer']],
            ['days' => 5,  'roles' => ['Project Manager', 'Backend Developer', 'QA Engineer']],
        ];

        // Hitung total hari per role
        $total_days_per_role = [];
        foreach ($phases as $phase) {
            foreach ($phase['roles'] as $role) {
                if (!isset($total_days_per_role[$role])) {
                    $total_days_per_role[$role] = 0;
                }
                $total_days_per_role[$role] += $phase['days'];
            }
        }

        // Ambil semua role dari DB
        $roles = $roleModel->findAll();
        $rate_per_role = [];
        foreach ($roles as $r) {
            $rawRate = $r['rate_per_day'];

            // Ambil dua angka dari format seperti "Rp 1.500.000 – Rp 3.000.000"
            if (preg_match_all('/([\d.]+)/', $rawRate, $matches)) {
                $minRate = isset($matches[1][0]) ? (int) str_replace('.', '', $matches[1][0]) : 0;
                $maxRate = isset($matches[1][1]) ? (int) str_replace('.', '', $matches[1][1]) : $minRate;
                $avgRate = ($minRate + $maxRate) / 2; // rata-rata
            } else {
                $avgRate = 0;
            }

            $rate_per_role[$r['role_name']] = $avgRate;
        }

        // Masukkan otomatis ke project_teams
        foreach ($total_days_per_role as $role => $days) {
            $rate = $rate_per_role[$role] ?? 0;
            $totalCost = $days * $rate;

            $teamModel->insert([
                'project_id'   => $projectId,
                'member_name'  => 'Auto-Assigned',
                'role'         => $role,
                'cost_per_day' => $rate,
                'total_days'   => $days,
                'total_cost'   => $totalCost,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to("/projects/timeline/$projectId");
    }

    // Edit project
    public function edit($id)
    {
        $projectModel = new ProjectModel();
        $templateModel = new TemplateModel();

        $project = $projectModel->find($id);
        $templates = $templateModel->findAll();

        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Project not found.');
        }

        return view('project_edit', [
            'project' => $project,
            'templates' => $templates
        ]);
    }

    // Update project
    public function update($id)
    {
        $projectModel = new ProjectModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'business_type' => $this->request->getPost('business_type'),
            'template_id' => $this->request->getPost('template_id'),
            'tech_stack'    => $this->request->getPost('tech_stack'), // ✅ tambahan
        ];

        $projectModel->update($id, $data);

        return redirect()->to('/projects')->with('success', 'Project updated successfully!');
    }

    // Delete project
    public function delete($id)
    {
        $projectModel = new ProjectModel();
        $featureModel = new FeatureModel();

        // Hapus semua feature yang terkait
        $featureModel->where('project_id', $id)->delete();
        $projectModel->delete($id);

        return redirect()->to('/projects')->with('success', 'Project deleted successfully!');
    }


    // Lihat timeline
    public function timeline($projectId)
    {
        $featureModel = new \App\Models\FeatureModel();
        $teamModel = new \App\Models\ProjectTeamModel();
        $projectModel = new \App\Models\ProjectModel();
        $roleModel = new \App\Models\ProjectRoleModel();

        $project  = $projectModel->find($projectId);
        $features = $featureModel->where('project_id', $projectId)->findAll();
        $teams    = $teamModel->where('project_id', $projectId)->findAll();
        $roles    = $roleModel->findAll();

        // Fase proyek (sementara manual)
        $phases = [
            ['name' => 'Analisis & Desain', 'days' => 10, 'roles' => ['Project Manager', 'UI/UX Designer', 'Backend Developer', 'Frontend Developer']],
            ['name' => 'Backend Development', 'days' => 15, 'roles' => ['Backend Developer', 'Project Manager', 'QA Engineer']],
            ['name' => 'Frontend Development', 'days' => 15, 'roles' => ['Frontend Developer', 'UI/UX Designer', 'Backend Developer', 'QA Engineer', 'Project Manager']],
            ['name' => 'Integrasi & Testing', 'days' => 10, 'roles' => ['Frontend Developer', 'Backend Developer', 'QA Engineer', 'Project Manager']],
            ['name' => 'Uji Coba & Pelatihan', 'days' => 5, 'roles' => ['Project Manager', 'QA Engineer', 'Backend Developer', 'Frontend Developer']],
            ['name' => 'Implementasi & Go-Live', 'days' => 5, 'roles' => ['Project Manager', 'Backend Developer', 'QA Engineer']],
        ];

        // Hitung total hari per role
        $total_days_per_role = [];
        foreach ($phases as $phase) {
            foreach ($phase['roles'] as $role) {
                if (!isset($total_days_per_role[$role])) {
                    $total_days_per_role[$role] = 0;
                }
                $total_days_per_role[$role] += $phase['days'];
            }
        }

        // Buat map rate role dari DB
        $rate_per_role = [];
        foreach ($roles as $r) {
            $rawRate = $r['rate_per_day'];

            // Ambil angka dari format seperti "Rp 1.500.000 – Rp 3.000.000"
            if (preg_match_all('/([\d.]+)/', $rawRate, $matches)) {
                $minRate = isset($matches[1][0]) ? (int) str_replace('.', '', $matches[1][0]) : 0;
                $maxRate = isset($matches[1][1]) ? (int) str_replace('.', '', $matches[1][1]) : $minRate;
                $avgRate = ($minRate + $maxRate) / 2;
            } else {
                $avgRate = (int) str_replace('.', '', $rawRate);
            }

            $rate_per_role[$r['role_name']] = $avgRate;
        }

        // Hitung total biaya per role
        $total_hpp = 0;
        $role_cost_data = [];
        foreach ($total_days_per_role as $role => $days) {
            $rate = $rate_per_role[$role] ?? 0;
            $cost = $days * $rate;

            $role_cost_data[$role] = [
                'days' => $days,
                'rate' => $rate,
                'cost' => $cost,
                'rate_formatted' => 'Rp ' . number_format($rate, 0, ',', '.'),
                'cost_formatted' => 'Rp ' . number_format($cost, 0, ',', '.'),
            ];

            $total_hpp += $cost;
        }

        return view('projects/overview', [
            'project' => $project,
            'features' => $features,
            'teams' => $teams,
            'role_cost_data' => $role_cost_data,
            'total_hpp' => $total_hpp,
            'total_hpp_formatted' => 'Rp ' . number_format($total_hpp, 0, ',', '.')
        ]);
    }



    public function updateTimeline()
    {
        $data = $this->request->getJSON(true);

        if (!$data || empty($data['id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        $featureModel = new \App\Models\FeatureModel();

        $featureModel->update($data['id'], [
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => true]);
    }


    // Export Excel
    public function exportExcel($projectId)
    {
        $featureModel = new FeatureModel();
        $features = $featureModel->where('project_id', $projectId)->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Feature');
        $sheet->setCellValue('B1', 'Start Date');
        $sheet->setCellValue('C1', 'End Date');
        $sheet->setCellValue('D1', 'Reference URL');

        $row = 2;
        foreach ($features as $f) {
            $sheet->setCellValue("A$row", $f['feature_name']);
            $sheet->setCellValue("B$row", $f['start_date']);
            $sheet->setCellValue("C$row", $f['end_date']);
            $sheet->setCellValue("D$row", $f['reference_link']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "project_{$projectId}_timeline.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // Form tambah template project baru
    public function createTemplate()
    {
        return view('template/create');
    }

    // Submit template baru
    public function storeTemplate()
    {
        $templateModel = new \App\Models\TemplateModel();

        // Ambil data umum
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        // Ambil data fitur yang dikirim dalam bentuk array
        $featureNames = $this->request->getPost('feature_name');
        $estimatedDays = $this->request->getPost('estimated_days');
        $referenceLinks = $this->request->getPost('reference_link');

        $features = [];

        // Buat array dari input fitur
        if (!empty($featureNames)) {
            for ($i = 0; $i < count($featureNames); $i++) {
                $features[] = [
                    'feature_name' => $featureNames[$i] ?? '',
                    'estimated_days' => isset($estimatedDays[$i]) ? (int)$estimatedDays[$i] : 0,
                    'reference_link' => $referenceLinks[$i] ?? ''
                ];
            }
        }

        // Ubah ke JSON
        $features_json = json_encode($features, JSON_PRETTY_PRINT);

        // Simpan ke database
        $templateModel->insert([
            'name' => $name,
            'description' => $description,
            'features_json' => $features_json
        ]);

        return redirect()->to('/projects/create')->with('success', 'Template berhasil dibuat!');
    }

    // List semua template
    public function listTemplates()
    {
        $templateModel = new \App\Models\TemplateModel();
        $templates = $templateModel->findAll();

        return view('template/index', ['templates' => $templates]);
    }

    // Edit template
    public function editTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();
        $template = $templateModel->find($id);

        if (!$template) {
            return redirect()->to('/templates')->with('error', 'Template not found.');
        }

        return view('template/edit', ['template' => $template]);
    }

    // Update template
    public function updateTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();

        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        $featureNames = $this->request->getPost('feature_name');
        $estimatedDays = $this->request->getPost('estimated_days');
        $referenceLinks = $this->request->getPost('reference_link');

        $features = [];

        if (!empty($featureNames)) {
            for ($i = 0; $i < count($featureNames); $i++) {
                $features[] = [
                    'feature_name' => $featureNames[$i] ?? '',
                    'estimated_days' => isset($estimatedDays[$i]) ? (int)$estimatedDays[$i] : 0,
                    'reference_link' => $referenceLinks[$i] ?? ''
                ];
            }
        }

        $features_json = json_encode($features, JSON_PRETTY_PRINT);

        $templateModel->update($id, [
            'name' => $name,
            'description' => $description,
            'features_json' => $features_json
        ]);

        return redirect()->to('/templates')->with('success', 'Template updated successfully!');
    }

    // Delete template
    public function deleteTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();
        $templateModel->delete($id);

        return redirect()->to('/templates')->with('success', 'Template deleted successfully!');
    }


    public function team($projectId)
    {
        $teamModel = new \App\Models\ProjectTeamModel();
        $teams = $teamModel->where('project_id', $projectId)->findAll();

        return view('projects/team_list', [
            'teams' => $teams,
            'projectId' => $projectId
        ]);
    }

    public function addTeam($projectId)
    {
        $roleModel = new \App\Models\ProjectRoleModel();
        $roles = $roleModel->findAll();

        return view('projects/team_form', [
            'projectId' => $projectId,
            'roles' => $roles
        ]);
    }

    public function storeTeam()
    {
        $teamModel = new \App\Models\ProjectTeamModel();

        $teamModel->insert([
            'project_id'  => $this->request->getPost('project_id'),
            'member_name' => $this->request->getPost('member_name'),
            'role'        => $this->request->getPost('role'),
            'cost_per_day' => $this->request->getPost('cost_per_day'),
            'total_days'  => $this->request->getPost('total_days')
        ]);

        return redirect()->to('/projects/team/' . $this->request->getPost('project_id'));
    }
}
