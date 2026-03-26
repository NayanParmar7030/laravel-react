<?php

class LeadService {
    protected $leadRepo;

    public function __construct(LeadRepositoryInterface $leadRepo) {
        $this->leadRepo = $leadRepo;
    }

    public function createLead($data) {
        return $this->leadRepo->create($data);
    }
}