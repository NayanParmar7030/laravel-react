<?php

use App\Models\Lead;

class LeadRepository implements LeadRepositoryInterface {
    public function getAll() {
        return Lead::latest()->get();
    }

    public function create(array $data) {
        return Lead::create($data);
    }
}