<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'title',
        'description',
        'lokasi',
        'laporan',
        'masalah',
        'solusi',
        'photo_evidence',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'submitted_at' => 'datetime',
        'photo_evidence' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    /**
     * Get photo evidence with corrected URLs
     */
    public function getPhotoEvidenceWithUrlsAttribute()
    {
        if (!$this->photo_evidence) {
            return null;
        }

        $photos = $this->photo_evidence;

        if (is_array($photos)) {
            foreach ($photos as &$photo) {
                if (isset($photo['path'])) {
                    // Use Storage::url() for consistent URL generation
                    $photo['url'] = Storage::url($photo['path']);
                }
            }
        }

        return $photos;
    }
}
