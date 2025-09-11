<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'is_super_admin',
        'permissions',
        'last_login_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_super_admin' => 'boolean',
            'is_active' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermission('manage_users') || $this->isSuperAdmin();
    }

    public function canManageHalls(): bool
    {
        return $this->hasPermission('manage_halls') || $this->isSuperAdmin();
    }

    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canApproveApplications(): bool
    {
        return $this->hasPermission('approve_applications') || $this->isSuperAdmin();
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by_admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('is_super_admin', true);
    }

    public function getFormattedPermissionsAttribute()
    {
        return collect($this->permissions ?? [])->map(function ($permission) {
            return ucwords(str_replace('_', ' ', $permission));
        })->implode(', ');
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
