<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = $this->user ? $this->user->id : null;
        
        $emailRules = [
            'required', 'string', 'email', 'max:255',
            Rule::unique('users')->ignore($userId, 'id'),
        ];

        $rules = [
            'name'          =>   ['required', 'string', 'max:255'],
            'surnames'      =>   ['required', 'string', 'max:255'],
            'email'         =>  $emailRules
        ];

        // Only add password rules if the password is present in the request.
        if ($this->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:5', 'confirmed'];
        }

        return $rules;
    }
}
