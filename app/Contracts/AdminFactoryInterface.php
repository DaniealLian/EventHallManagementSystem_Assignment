<?php

namespace App\Contracts;

use App\Models\Admin;

interface AdminFactoryInterface
{
    public function updateAdmin(Admin $admin, array $data): bool;
    public function getAdminType(string $type): AdminTypeInterface;
}

interface AdminTypeInterface
{
    public function getType(): string;
    public function getPermissions(): array;
    public function isSuperAdmin(): bool;
    public function getDefaultAttributes(): array;
    public function createDatabaseRecord(array $data): Admin;
}
