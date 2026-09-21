<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'hotel_name' => ['required', 'string', 'max:255'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
        ];

        if (request()->hasFile('file_path')) {
            $rules['file_path'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        } else {
            $rules['file_path'] = ['nullable', 'string'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
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
