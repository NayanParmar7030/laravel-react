<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LeadService;
use App\Http\Requests\StoreLeadRequest;

class LeadController extends Controller {

    protected $service;

    public function __construct(LeadService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getLeads();
    }

    public function store(StoreLeadRequest $request) {
        return $this->service->createLead($request->validated());
    }

    public function update(StoreLeadRequest $request, $id) {
        return $this->service->updateLead($id, $request->validated());
    }

    public function destroy($id) {
        return $this->service->deleteLead($id);
    }
}