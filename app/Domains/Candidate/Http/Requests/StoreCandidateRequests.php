<?php

namespace App\Domains\Candidate\Http\Requests;

use App\Domains\Auth\Http\DataTransferObject\UserRequestData;
use App\Domains\Auth\Models\User;
use App\Domains\Misc\DataTransferObjects\AddressData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * Class StoreUserRequest.
 */
class StoreCandidateRequests extends FormRequest
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
            'type' => ['required', Rule::in([User::TYPE_ADMIN, User::TYPE_USER])],
            'added_by' => ['required'],
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'middle_name' => ['required', 'max:100'],
            'birthday' => ['required'],
            'username' => ['required', 'max:255', Rule::unique('users','phone')],
            'email' => ['required', 'max:255', 'email', Rule::unique('users')],
            'password' => ['max:100', PasswordRules::register($this->email)],
            'active' => ['sometimes', 'in:1'],
            'email_verified' => ['sometimes', 'in:1'],
            'send_confirmation_email' => ['sometimes', 'in:1'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => [Rule::exists('roles', 'id')->where('type', $this->type)],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'id')->where('type', $this->type)],
            'address.region_id' => ['required', 'numeric'],
            'address.province_id' => ['required', 'numeric'],
            'address.city_municipality_id' => ['required','numeric'],
            'address.barangay_id' => ['required', 'numeric'],
            'position_id' => ['required'],
            'scope_id' => ['required']
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

    public function data()
    {
        return new UserRequestData([
            'address' => new AddressData($this->get('address')),
        ]);
    }
}
