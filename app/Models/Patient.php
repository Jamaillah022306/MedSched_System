<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // Tell Laravel the real primary key column
    protected $primaryKey = 'patient_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}