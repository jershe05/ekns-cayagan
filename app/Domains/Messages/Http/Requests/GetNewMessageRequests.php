<?php

namespace App\Domains\Messages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use F9Web\ApiResponseHelpers;
use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Voter\DataTransferObject\VoterRequestData;
/**
 * Class UpdateProfileRequest.
 */
class GetNewMessageRequest extends FormRequest
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
            'leader_id' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 400));
    }
}
