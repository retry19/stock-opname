<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reportItems(): HasMany
    {
        return $this->hasMany(ReportItem::class, 'id_report');
    }
}
