<?php

namespace App\Domains\Files\Http\Requests;

use App\Domains\Leader\DataTransferObject\Backend\ScopeRequestData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use F9Web\ApiResponseHelpers;
use App\Domains\Misc\DataTransferObjects\AddressData;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * Class UpdateProfileRequest.
 */
class StoreImageRequest extends FormRequest
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
            'file' => ['required','image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'user_id' => ['required'],
            'description' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 400));
    }

}
