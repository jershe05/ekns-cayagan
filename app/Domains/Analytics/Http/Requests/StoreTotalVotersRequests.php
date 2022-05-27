<?php

namespace App\Domains\Analytics\Http\Requests;

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
class StoreTotalVotersRequests extends FormRequest
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
            'address.barangay_code' => ['required', 'numeric'],
            'total_voters' => ['numeric', 'required']
        ];
    }

    
}
