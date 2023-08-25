<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportItem extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'id_report');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_product');
    }
}
