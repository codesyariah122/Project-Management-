<?php
namespace App\Models;
use CodeIgniter\Model;

class TemplateModel extends Model {
    protected $table = 'project_templates';
    protected $allowedFields = ['name','description','features_json'];
}
