<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_report_id',
        'lokasi',
        'nama_kontak',
        'nomor_kontak',
        'laporan',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function marketingReport()
    {
        return $this->belongsTo(MarketingReport::class);
    }
}
