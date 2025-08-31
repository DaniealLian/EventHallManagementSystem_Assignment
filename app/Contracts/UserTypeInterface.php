<?php

namespace App\Contracts;

use App\Models\User;

interface UserTypeInterface
{
    public function getRole(): string;
    public function getPermissions(): array;
    public function canManageHalls(): bool;
    public function canViewAllBookings(): bool;
    public function canApproveApplications(): bool;
    public function getDefaultAttributes(): array;
    public function createDatabaseRecord(array $data): User;
}