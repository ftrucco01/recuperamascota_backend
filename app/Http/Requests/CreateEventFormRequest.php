<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEventFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $whenRules = $this->isMethod('post') ? 'required|' : 'sometimes|';
    
        // Basic rules
        $rules = [
            'selected_packages' => 'array|exists:user_package,id',
            'establishment_id' => 'required|exists:establishments,id',
            'category_id' => 'required|exists:category_events,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'status' => [
                'sometimes',
                Rule::in(['active', 'cancelled', 'paused', 'postponed']),
                function ($attribute, $value, $fail) {
                    if ($value === 'cancelled' && $this->event && now()->timestamp > \Illuminate\Support\Carbon::parse($this->event->when)->timestamp) {
                        $fail('The event cannot be cancelled because it has already expired.');
                    }
                }
            ],
            'reward_ids' => 'nullable|array|exists:rewards,id',
            'date_opening' => 'required|date|after_or_equal:now',
            'date_closing' => 'required|date|after:date_opening',
        ];
    
        // If only the status is provided in the request
        if (count($this->all()) == 1 && $this->has('status')) {
            return [
                'status' => $rules['status']
            ];
        }
    
        return $rules;
    }
    
}
