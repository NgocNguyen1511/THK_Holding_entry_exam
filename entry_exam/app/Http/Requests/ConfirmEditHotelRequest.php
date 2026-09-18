<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmEditHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'integer', 'exists:hotels,hotel_id'],
            'hotel_name' => ['required', 'string', 'max:255'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
            'file_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'temp_file_path' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('hotel.validation.hotel_exists'),
            'hotel_id.exists' => __('hotel.validation.hotel_exists'),
            'hotel_name.required' => __('hotel.validation.hotel_name_required'),
            'hotel_name.max' => __('hotel.validation.hotel_name_max'),
            'prefecture_id.required' => __('hotel.validation.prefecture_required'),
            'prefecture_id.exists' => __('hotel.validation.prefecture_exists'),
            'file_path.image' => __('hotel.validation.file_image'),
            'file_path.mimes' => __('hotel.validation.file_mimes'),
            'file_path.max' => __('hotel.validation.file_max'),
        ];
    }
}
