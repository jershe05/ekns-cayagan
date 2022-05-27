<?php

namespace App\Domains\Leader\Http\Requests;

use App\Domains\Leader\DataTransferObject\Backend\ScopeRequestData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use F9Web\ApiResponseHelpers;
use App\Domains\Misc\DataTransferObjects\AddressData;
/**
 * Class UpdateProfileRequest.
 */
class RegisterLeaderRequest extends FormRequest
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
            'user_id'  => ['required'],
            'province' => ['nullable'],
            'city'     => ['nullable'],
            'barangay' => ['nullable'],
            'zone'     => ['nullable'],
            'region'   => ['nullable'],
            'phone'    => ['required']
        ];
    }

}
