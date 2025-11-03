<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectRoleModel extends Model
{
    protected $table = 'project_roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role_name', 'rate_per_day', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
