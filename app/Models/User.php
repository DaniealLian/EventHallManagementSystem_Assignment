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
        'manager_application_reason',
        'manager_applied_at',
        'manager_reviewed_at',
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
            'manager_applied_at'=>'datetime',
            'manager_reviewed_at'=>'datetime',
        ];
    }

    public function isCustomer(){
        return $this->role === 'customer';
    }

    public function isManager(){
        return $this->role === 'manager';
    }

    public function canApplyForManager(){
        return $this->role === 'customer' && $this->manager_status === 'none';
    }

    public function hasManagerApplicationPending(){
        return $this->manager_status === 'pending';
    }

    public function applyForManager($reason){
        $this->update([
            'manager-status' => 'pending',
        'manager_application_reason' => $reason,
        'manager_applied_at' => now(),
        ]);
    }

    public function approveManagerApplication(){
        $this->update([
            'role' => 'manager',
            'manager_status' => 'approved',
            'manager_reviewed_at' => now(),
        ]);
    }

    public  function rejectManagerApplication(){
        $this->update([
            'manager_status' => 'rejected',
            'manager_reviewed_at' => now(),
        ]);
    }
}
