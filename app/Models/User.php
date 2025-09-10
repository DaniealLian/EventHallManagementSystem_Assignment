<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'manager_status',
        'company_address',
        'company_name',
        'company_email',
        'experience',
        'manager_applied_at',
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
            'manager_applied_at' => 'datetime',
        ];
    }


    public function getUserType(): \App\Contracts\UserTypeInterface
    {
        $factory = app(\App\Contracts\UserFactoryInterface::class);
        return $factory->getUserType($this->role);
    }

    public function getPermissions(): array
    {
        return $this->getUserType()->getPermissions();
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getPermissions();
        return in_array('*', $permissions) || in_array($permission, $permissions);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function canApplyForManager(): bool
    {
        return $this->role === 'customer' && $this->manager_status === 'none';
    }

    public function hasManagerApplicationPending(): bool
    {
        return $this->manager_status === 'pending';
    }

    public function applyForManager(array $applicationData): void
    {
        $this->update([
            'manager_status' => 'pending',
            'company_address' => $applicationData['company_address'],
            'company_name' => $applicationData['company_name'],
            'company_email' => $applicationData['company_email'],
            'experience' => $applicationData['experience'] ?? null,
            'manager_applied_at' => now(),
        ]);
    }

    public function approveManagerApplication(): void
    {
        $this->update([
            'role' => 'manager',
            'manager_status' => 'approved',
        ]);
    }

    public function rejectManagerApplication(): void
    {
        $this->update([
            'manager_status' => 'rejected',
        ]);
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'customer' => 'bg-primary',
            'manager' => 'bg-success',
            'admin' => 'bg-danger',
        ];

        return $badges[$this->role] ?? 'bg-secondary';
    }

    public function getFormattedRoleAttribute()
    {
        return ucfirst($this->role);
    }
}
