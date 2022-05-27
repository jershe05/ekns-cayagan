<?php

namespace App\Domains\Voter\Http\Requests\Backend;

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
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required','max:100'],
            'middle_name' => ['required','max:100'],
            'birthday' => ['required'],
            'gender' => ['required'],
            'email' => ['nullable'],
            'phone'    => ['required', 'numeric'],
            'address.scope_id' => ['nullable'],
            'address.region_code' => ['required', 'numeric'],
            'address.province_code' => ['required', 'numeric'],
            'address.city_code' => ['required','numeric'],
            'address.barangay_code' => ['required', 'numeric'],
            'precinct_id' => ['nullable']
        ];
    }

    public function data()
    {
        return new VoterRequestData([
            'first_name' => $this->get('first_name'),
            'last_name' => $this->get('last_name'),
            'middle_name' => $this->get('middle_name'),
            'birthday' => $this->get('birthday'),
            'gender' => $this->get('gender'),
            'phone'    => $this->get('phone'),
            'email' => $this->get('email'),
            'precinct_id' => $this->get('precinct_id'),
            'address' => new AddressData($this->get('address')),
        ]);
    }
}
