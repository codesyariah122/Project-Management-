<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ProjectTeamsSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        // Ambil semua project_id yang ada di tabel projects
        $projectIds = $this->db->table('projects')->select('id')->get()->getResultArray();
        $projectIds = array_column($projectIds, 'id');

        if (empty($projectIds)) {
            echo "Tidak ada project di tabel projects, seeder dibatalkan.\n";
            return;
        }

        $roles = [
            'Project Manager' => 500,
            'Frontend Developer' => 300,
            'Backend Developer' => 350,
            'UI/UX Designer' => 250,
            'QA Engineer' => 200,
        ];

        for ($i = 1; $i <= 10; $i++) {
            $projectId = $projectIds[array_rand($projectIds)]; // ambil project_id random dari yang ada
            $roleName = array_rand($roles);
            $costPerDay = $roles[$roleName];
            $totalDays = rand(1, 10);
            $totalCost = $costPerDay * $totalDays;

            $this->db->table('project_teams')->insert([
                'project_id'    => $projectId,
                'member_name'   => $faker->name,
                'role'          => $roleName,
                'cost_per_day'  => $costPerDay,
                'total_days'    => $totalDays,
                'total_cost'    => $totalCost,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        echo "Seeder ProjectTeams berhasil dijalankan.\n";
    }
}
