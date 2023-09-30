<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSocialMediaFormRequest extends FormRequest
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
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'establishment_id' => 'required|numeric|exists:establishments,id',
            'user_name' => 'required|string|max:255',
            'type' => 'required|string',
            'followers' => 'required|numeric',
            'url' => 'required|url'
        ];
    }
}
