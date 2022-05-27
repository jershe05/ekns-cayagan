<?php

namespace App\Domains\PhoneLogs\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use F9Web\ApiResponseHelpers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * Class UpdateProfileRequest.
 */
class StoreMessagesRequest extends FormRequest
{
    use ApiResponseHelpers;
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
        return [
            'user_id' => ['required'],
            'message_list.*' => ['nullable'],
        ];
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 400));
    }
}
