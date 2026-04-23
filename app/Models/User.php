<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $fillable = [
        'first_name', 
        'last_name',  
        'email',
        'password_hash',
        'role',
        'contact_number', 
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Tell Laravel to use password_hash column for auth.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Tell Laravel to use user_id as the auth identifier.
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'user_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool   { return $this->role === 'admin';   }
    public function isDoctor(): bool  { return $this->role === 'doctor';  }
    public function isPatient(): bool { return $this->role === 'patient'; }
}