<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $arrRules = [
            'email' => 'required|email|unique:staffs',
            'name' => 'required|string|max:250',
            'phone' => 'numeric|digits_between:9,10'
        ];

        if ($this->method() == 'PUT') {
            $arrRules['email'] = 'required|email|unique:staffs,email,'.$this->route('staff');
        }

        return $arrRules;
    }

//    public function messages()
//    {
//        return [
//            'email.required' => 'Email is required!',
//            'password.required' => 'Password is required!',
//            'password.min' => 'Password is too short',
//        ];
//    }
}
