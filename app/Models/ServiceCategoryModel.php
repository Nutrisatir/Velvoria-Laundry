<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceCategoryModel extends Model
{
    protected $table            = 'service_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'slug' => 'required|max_length[120]|is_unique[service_categories.slug,id,{id}]',
    ];

    public function getActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
