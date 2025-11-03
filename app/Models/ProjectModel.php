<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $allowedFields = ['name', 'description', 'business_type', 'template_id', 'tech_stack', 'budget'];
}
