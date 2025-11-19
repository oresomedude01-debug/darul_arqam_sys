<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'session',
        'term',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->orderBy('start_date');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')->orderBy('start_date', 'desc');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'upcoming' => 'badge-info',
            'ongoing' => 'badge-success',
            'completed' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
