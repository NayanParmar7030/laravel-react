<?php

namespace App\Services;
use App\Repositories\LeadRepositoryInterface;

class LeadService {

    protected $repo;

    public function __construct(LeadRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function getLeads() {
        return $this->repo->getAll();
    }

    public function createLead($data) {
        return $this->repo->create($data);
    }

    public function updateLead($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function deleteLead($id) {
        return $this->repo->delete($id);
    }
}