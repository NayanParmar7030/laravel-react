<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{
    public function getAll(): LengthAwarePaginator
    {
        return Lead::query()
            ->latest('created_at')
            ->paginate(15);
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function find(int $id): Lead
    {
        return Lead::findOrFail($id);
    }

    public function update(int $id, array $data): Lead
    {
        $lead = $this->find($id);
        $lead->update($data);

        return $lead->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) Lead::destroy($id);
    }
}
