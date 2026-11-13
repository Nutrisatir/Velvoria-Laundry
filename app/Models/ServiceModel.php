<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'category_id',
        'code',
        'name',
        'description',
        'unit',
        'price',
        'minimum_quantity',
        'estimated_hours',
        'is_express',
        'is_active',
        'image',
        'sort_order',
    ];

    protected $validationRules = [
        'category_id' => 'required|is_natural_no_zero',
        'code'        => 'required|max_length[30]|is_unique[services.code,id,{id}]',
        'name'        => 'required|max_length[150]',
        'unit'        => 'required|in_list[kg,pcs,pasang]',
        'price'       => 'required|is_natural_no_zero',
    ];

    public function getActive(): array
    {
        return $this->select('services.*, service_categories.name AS category_name, service_categories.slug AS category_slug')
                    ->join('service_categories', 'service_categories.id = services.category_id', 'left')
                    ->where('services.is_active', 1)
                    ->orderBy('services.sort_order', 'ASC')
                    ->findAll();
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->where('category_id', $categoryId)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function findByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}
