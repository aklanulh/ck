<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'lokasi',
        'total_locations',
        'locations_data',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'locations_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketingLocations()
    {
        return $this->hasMany(MarketingLocation::class);
    }

    public function getLocationsAttribute()
    {
        return json_decode($this->locations_data, true) ?? [];
    }

    public function setLocationsAttribute($value)
    {
        $this->attributes['locations_data'] = json_encode($value);
    }

    // Scope untuk query berdasarkan status
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate);
    }
}
