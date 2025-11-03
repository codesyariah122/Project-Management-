<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectTeamModel extends Model
{
    protected $table = 'project_teams';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'project_id',
        'member_name',
        'role',
        'cost_per_day',
        'total_days',
        'total_cost',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
