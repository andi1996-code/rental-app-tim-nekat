<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'email',
        'password',
        'password_hash',
        'role',
        'user_type',
        'full_name',
        'phone_number',
        'date_of_birth',
        'driver_license_number',
        'driver_license_image',
        'profile_image',
        'is_verified',
        'is_active',
        'managed_location_id',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_hash',
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
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the password for authentication.
     * Laravel looks for 'password' but we use 'password_hash'
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name of the password attribute.
     * This tells Laravel to use 'password_hash' instead of 'password'
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Get the user's name for Filament.
     * Filament uses this method to display the user's name in the UI.
     */
    public function getFilamentName(): string
    {
        return $this->full_name ?? $this->email;
    }

    /**
     * Get the name attribute (accessor).
     * This maps the 'name' attribute to 'full_name' for Filament compatibility.
     */
    public function getNameAttribute(): string
    {
        return $this->full_name ?? $this->email;
    }

    /**
     * Determine if the user can access the Filament panel.
     * Only admin and super_admin users can access the admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow admin and super_admin to access the panel
        return in_array($this->user_type, ['admin', 'super_admin']) && $this->is_active;
    }

    // Relationships
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'user_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'admin_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function managedLocation()
    {
        return $this->belongsTo(Location::class, 'managed_location_id');
    }
}
