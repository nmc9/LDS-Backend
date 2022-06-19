<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required',
            'location' => 'required|max:255',
            'start_datetime' => 'date_format:Y-m-d H:i:s',
            "end_datetime" => "date_format:Y-m-d H:i:s",
        ];
    }
}
