<?php

namespace App\Domains\Household\Http\Requests;

use App\Domains\Leader\DataTransferObject\Backend\ScopeRequestData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use F9Web\ApiResponseHelpers;
use App\Domains\Misc\DataTransferObjects\AddressData;
/**
 * Class UpdateProfileRequest.
 */
class AddHouseholdRequest extends FormRequest
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
            'household_name' => ['required'],
            'leader_id' => ['required', 'numeric', 'integer']
        ];
    }
}
