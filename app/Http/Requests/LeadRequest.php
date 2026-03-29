<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        if ($this->isMethod('post')) {
            return $user->can('create leads');
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $user->can('edit leads');
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $lead = $this->route('lead');
        $ignoreId = $lead instanceof Lead ? $lead->getKey() : $lead;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('leads', 'email')->ignore($ignoreId),
            ],
            'phone' => 'nullable|string|max:30',
            'status' => 'nullable|string|in:new,contacted,converted',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strip_tags((string) $this->input('name')),
            ]);
        }
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower((string) $this->input('email')),
            ]);
        }
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^\d+\-\s().]/', '', (string) $this->input('phone')) ?: null,
            ]);
        }
    }
}
