<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'name',
        'brand',
        'type',
        'stock',
        'price',
        'low_stock_threshold',
        'service_point_id',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class, 'service_point_id');
    }

}
