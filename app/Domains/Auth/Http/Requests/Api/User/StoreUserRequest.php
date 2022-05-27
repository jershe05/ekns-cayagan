<?php

namespace App\Domains\Auth\Http\Requests\Api\User;

use App\Domains\Auth\Http\DataTransferObject\UserRequestData;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\DataTransferObject\Backend\ScopeRequestData;
use App\Domains\Misc\DataTransferObjects\AddressData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends FormRequest
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
        return [
            'added_by' => ['nullable'],
            'type' => ['required'],
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required','max:100'],
            'middle_name' => ['required','max:100'],
            'birthday' => ['required'],
            'gender' => ['required'],
            'referred_by' => ['nullable'],
            'organization_id' => ['nullable'],
            'candidate_id' => ['nullable'],
            'email' => ['nullable', 'max:255', 'email', Rule::unique('users')],
            'active' => ['nullable'],
            'username' => ['required', 'max:255', Rule::unique('users','phone')],
            'roles' => ['sometimes', 'array'],
            'email_verified_at' => ['nullable'],
            'roles.*' => [Rule::exists('roles', 'id')->where('type', $this->type)],
            'password' => ['nullable'],
            'address.region_code' => ['required', 'numeric'],
            'address.province_code' => ['required', 'numeric'],
            'address.city_code' => ['required','numeric'],
            'address.barangay_code' => ['required', 'numeric'],
            'scope.scope_id' => ['required'],
            'scope.region_code' => ['nullable', 'numeric'],
            'scope.province_code' => ['nullable', 'numeric'],
            'scope.city_code' => ['nullable','numeric'],
            'scope.barangay_code' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this user type.'),
            'permissions.*.exists' => __('One or more permissions were not found or are not allowed to be associated with this user type.'),
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 400));
    }

    public function address()
    {
        return new AddressData($this->get('address'));
    }

    public function leaderAddress()
    {
        return new AddressData($this->get('scope'));
    }

    public function scope()
    {
        return  new ScopeRequestData($this->get('scope'));
    }
}
