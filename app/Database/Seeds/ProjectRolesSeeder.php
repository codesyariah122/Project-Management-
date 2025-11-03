<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role_name' => 'Project Manager', 'rate_per_day' => 500],
            ['role_name' => 'Frontend Developer', 'rate_per_day' => 300],
            ['role_name' => 'Backend Developer', 'rate_per_day' => 350],
            ['role_name' => 'UI/UX Designer', 'rate_per_day' => 250],
            ['role_name' => 'QA Engineer', 'rate_per_day' => 200],
        ];

        foreach ($roles as $role) {
            $this->db->table('project_roles')->insert([
                'role_name'   => $role['role_name'],
                'rate_per_day' => $role['rate_per_day'],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
        }
    }
}
