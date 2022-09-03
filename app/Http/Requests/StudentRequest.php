<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
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
        $routeName = Route::currentRouteName();

        if(in_array($routeName, ['student.store', 'student.update', 'coacher.store', 'coacher.update', 'class.register-student'])){
            $rules = [
                'name'      => 'required|string|between:2,255',
                'phone'     => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10|unique:users',
                'email'     => 'required|string|email|max:100',
                'birthday'  => 'nullable|date_format:Y-m-d|before:today',
                'gender'    => 'required|in:male,female',
                'avatar'    => 'image|mimes:jpeg,png,jpg,gif,svg',
            ];

            if ($routeName == 'student.update' || $routeName == 'coacher.update') {
                $rules['phone'] = 'required|numeric|regex:/(0)[0-9]{9}/|digits:10|unique:users,phone,' . $this->id;
            }

            return $rules;
        }

        if($routeName === 'student.index' || $routeName === 'coacher.index'){
            return [
                'name'     => 'nullable|string|max:250',
                'per_page' => 'nullable|numeric|min:1',
                'page'     => 'nullable|numeric|min:1',
            ];
        }

        if (in_array($routeName, ['student.register-class', 'student.attend'])) {
            return [
                'studio_id'    => [
                    Rule::exists('studios', 'id')->where(function ($query) {
                        return $query->where('status', 1);
                    }),
                ]
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => FALSE,
                'data' => (object)[
                    'error' => (new ValidationException($validator))->errors(),
                ],
                'message' => "Fail",
            ], 400)
        );
    }

}
