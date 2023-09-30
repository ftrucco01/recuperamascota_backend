<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\UserEstablishmentBanned;

class UserEstablishmentBannedCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */

     public function rules(): array
     {
         return [
             'user_id' => [
                 'required',
                 'numeric',
                 'exists:users,id',
                 function ($attribute, $value, $fail) {
                     if ($this->has('establishment_id') && 
                         UserEstablishmentBanned::where('user_id', $value)
                                                ->where('establishment_id', $this->get('establishment_id'))
                                                ->exists()) {
                         $fail('The combination of user and establishment is not unique.');
                     }
                 },
             ],
             'establishment_id' => 'required|numeric|exists:establishments,id',
         ];
     }
}
