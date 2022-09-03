<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'current_password' => 'required|min:6|max:60',
            'new_password'     => 'required|min:6|max:60',
            'confirm_password' => 'same:new_password',
        ];

        return $rules;
    }
}
