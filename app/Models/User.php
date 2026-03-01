<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'bio',
        'verification_code',
        'email_verified_at',
        'is_admin',
        'admin_role', // Added
    ];

    public $translatable = ['bio'];

    /**
     * Role Helpers
     */
    public function isSuperAdmin() { return $this->is_admin && ($this->admin_role === 'super_admin' || !$this->admin_role); }
    public function isAccountant() { return $this->is_admin && $this->admin_role === 'accountant'; }
    public function isDataEntry() { return $this->is_admin && $this->admin_role === 'data_entry'; }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the full avatar URL
     * Dynamically generates URL from relative path
     */
    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return null;
        }

        // If already a full URL (backward compatibility)
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // Generate full URL from relative path
        return asset($this->avatar);
    }
}
