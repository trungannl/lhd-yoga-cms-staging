<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudioRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'schedule' => $this->schedule ? array_map('intval', $this->schedule) : [],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $routeName = Route::currentRouteName();

        if(in_array($routeName, ['class.store', 'class.update'])){
            return [
                'name'        => 'required|string|max:250',
                'description' => 'string',
                'address'     => 'string|max:250',
                'status'      => 'integer|in:0,1',
                'start_date'  => 'nullable|date_format:Y-m-d',
                'end_date'    => 'nullable|date_format:Y-m-d|after:start_date',
                'start_time'  => 'nullable|date_format:H:i:s',
                'end_time'    => 'nullable|date_format:H:i:s',
                'price'       => 'nullable|numeric|min:0|not_in:0',
                'schedule'    => 'nullable|array',
                'schedule.*'  => 'integer|in:0,1',
                'coach_id'    => [
                    'nullable',
                    Rule::exists('users', 'id')->where(function ($query) {
                        return $query->where('is_coacher', 1);
                    }),
                ],
                'image'       => 'image|mimes:jpeg,png,jpg,gif,svg',
            ];
        }

        if(in_array($routeName, ['class.index', 'class.student'])){
            return [
                'name'     => 'nullable|string|max:250',
                'per_page' => 'nullable|numeric|min:1',
                'page'     => 'nullable|numeric|min:1',
            ];
        }

        if($routeName === 'class.add-student'){
            $studioID = $this->id;
            return [
                'phone_student'    => [
                    'required',
                    Rule::exists('users', 'phone')->where(function ($query) {
                        return $query->where('is_student', 1);
                    }),
                    Rule::unique('studio_user', 'user_id')->where(function ($query) use($studioID) {
                        return $query->where('studio_id', $studioID);
                    }),
                ],
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
        if ($this->expectsJson()) {
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

        parent::failedValidation($validator);
    }

}
