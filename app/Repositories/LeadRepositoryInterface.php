<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function getAll(): LengthAwarePaginator;

    public function create(array $data): Lead;

    public function find(int $id): Lead;

    public function update(int $id, array $data): Lead;

    public function delete(int $id): bool;
}
