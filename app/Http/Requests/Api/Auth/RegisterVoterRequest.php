<?php

namespace App\Http\Requests\Api\Auth;

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
class RegisterVoterRequest extends FormRequest
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
            'added_by' => ['required', 'numeric'],
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required','max:100'],
            'middle_name' => ['required','max:100'],
            'birthday' => ['required'],
            'gender' => ['required'],
            'phone'    => ['required', 'numeric'],
            'household_id' => ['nullable', 'integer'],
            'address.scope_id' => ['nullable'],
            'address.region_code' => ['required', 'numeric'],
            'address.province_code' => ['required', 'numeric'],
            'address.city_code' => ['required','numeric'],
            'address.barangay_code' => ['required', 'numeric'],
            'address.zone_no' => ['required', 'numeric'],
            'precinct_id' => ['required', 'numeric']
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 400));
    }

    public function data()
    {
        return new VoterRequestData([
            'added_by' => $this->get('added_by'),
            'first_name' => $this->get('first_name'),
            'last_name' => $this->get('last_name'),
            'middle_name' => $this->get('middle_name'),
            'birthday' => $this->get('birthday'),
            'gender' => $this->get('gender'),
            'phone'    => $this->get('phone'),
            'precinct_id' => $this->get('precinct_id'),
            'household_id' => $this->get('household_id'),
            'address' => new AddressData($this->get('address')),
        ]);
    }
}
