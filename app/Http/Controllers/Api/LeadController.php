<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function __construct(
        protected LeadService $service
    ) {}

    public function index(): JsonResponse
    {
        $paginator = $this->service->getLeads();

        return $this->success(
            LeadResource::collection($paginator)->resolve(),
            'Leads retrieved'
        );
    }

    public function show(Lead $lead): JsonResponse
    {
        return $this->success(
            (new LeadResource($lead))->resolve(),
            'Lead retrieved'
        );
    }

    public function store(LeadRequest $request): JsonResponse
    {
        $lead = $this->service->createLead($request->validated());

        return $this->success(
            (new LeadResource($lead))->resolve(),
            'Lead created',
            201
        );
    }

    public function update(LeadRequest $request, Lead $lead): JsonResponse
    {
        $lead = $this->service->updateLead($lead->id, $request->validated());

        return $this->success(
            (new LeadResource($lead))->resolve(),
            'Lead updated'
        );
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $this->service->deleteLead($lead->id);

        return $this->success([], 'Lead deleted');
    }
}
