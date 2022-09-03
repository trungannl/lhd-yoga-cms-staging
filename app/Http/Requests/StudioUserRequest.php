<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudioUserRequest extends FormRequest
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

        if(in_array($routeName, ['class.add-register-student', 'class.update-register-student'])){
            return [
                'studio_id'    => [
                    Rule::exists('studios', 'id')->where(function ($query) {
                        return $query->where('status', 1);
                    }),
                ],
                'student_id'    => [
                    Rule::exists('users', 'id')->where(function ($query) {
                        return $query->where('is_student', 1);
                    }),
                ],
                'start_date'  => 'required|date_format:Y-m-d',
                'end_date'    => 'required|date_format:Y-m-d|after:start_date',
                'number_of_sessions'   => 'required|numeric|min:1',
                'price'   => 'required|numeric|min:0',
                'approve'   => 'integer|in:0,1',
                'is_paid'   => 'integer|in:0,1',
            ];
        }

        if(in_array($routeName, ['class.list-register-student'])){
            return [
                'name'     => 'nullable|string|max:250',
                'per_page' => 'nullable|numeric|min:1',
                'page'     => 'nullable|numeric|min:1',
            ];
        }
    }

    /**
     * custom response when validation has fails
     *
     * @param Validator $validator
     * @return void
     */
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
