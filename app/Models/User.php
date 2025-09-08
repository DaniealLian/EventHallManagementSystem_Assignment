<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
            'manager_applied_at' => 'datetime',
        ];
    }

    // RELATIONSHIPS
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // FACTORY PATTERN METHODS
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

    // ROLE-BASED METHODS USING FACTORY PATTERN
    public function canManageHalls(): bool
    {
        return $this->getUserType()->canManageHalls();
    }

    public function canViewAllBookings(): bool
    {
        return $this->getUserType()->canViewAllBookings();
    }

    public function canApproveApplications(): bool
    {
        return $this->getUserType()->canApproveApplications();
    }

    // SIMPLE ROLE CHECKING METHODS
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // MANAGER APPLICATION METHODS
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

    // SCOPES
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // ACCESSORS
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
