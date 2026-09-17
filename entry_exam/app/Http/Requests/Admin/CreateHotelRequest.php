<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_name' => ['required', 'string', 'max:255'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'hotel_name' => 'Hotel Name',
            'prefecture_id' => 'Prefecture',
            'file' => 'Hotel Image',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hotel_name.required' => 'The hotel name is required.',
            'hotel_name.max' => 'The hotel name may not be greater than 255 characters.',
            'prefecture_id.required' => 'Please select a prefecture.',
            'prefecture_id.exists' => 'The selected prefecture does not exist.',
            'file.image' => 'The uploaded file must be an image.',
            'file.mimes' => 'The image must be a file of type: JPEG, PNG, JPG, WEBP.',
            'file.max' => 'The image size may not exceed 2MB.',
        ];
    }
}
