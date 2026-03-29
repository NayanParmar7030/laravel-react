<?php

namespace App\Services;

use App\Jobs\RecordLeadAudit;
use App\Repositories\LeadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LeadService
{
    public function __construct(
        protected LeadRepositoryInterface $repo
    ) {}

    public function getLeads(): LengthAwarePaginator
    {
        $page = (int) request()->get('page', 1);

        if (Cache::supportsTags()) {
            return Cache::tags(['leads'])
                ->remember('leads.index.page.'.$page, 60, fn () => $this->repo->getAll());
        }

        return $this->repo->getAll();
    }

    public function createLead(array $data)
    {
        $lead = $this->repo->create($data);
        $this->flushLeadCache();
        RecordLeadAudit::dispatch($lead->id, 'created');

        return $lead;
    }

    public function updateLead(int $id, array $data)
    {
        $lead = $this->repo->update($id, $data);
        $this->flushLeadCache();
        RecordLeadAudit::dispatch($lead->id, 'updated');

        return $lead;
    }

    public function deleteLead(int $id): bool
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            $this->flushLeadCache();
            RecordLeadAudit::dispatch($id, 'deleted');
        }

        return $deleted;
    }

    protected function flushLeadCache(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags(['leads'])->flush();
        }
    }
}
