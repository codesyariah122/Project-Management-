<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureModel extends Model
{
    protected $table = 'project_features';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'project_id',
        'feature_name',
        'estimated_days',
        'reference_link',
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true; // 🔥 penting
}
