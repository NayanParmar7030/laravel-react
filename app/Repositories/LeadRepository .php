<?php

use App\Models\Lead;
use App\Repositories\LeadRepositoryInterface;


class LeadRepository implements LeadRepositoryInterface {

    public function getAll() {
        return Lead::latest()->paginate(10);
    }

    public function create(array $data) {
        return Lead::create($data);
    }

    public function find($id) {
        return Lead::findOrFail($id);
    }

    public function update($id, array $data) {
        $lead = $this->find($id);
        $lead->update($data);
        return $lead;
    }

    public function delete($id) {
        return Lead::destroy($id);
    }
}