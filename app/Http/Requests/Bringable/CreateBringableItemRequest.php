<?php

namespace App\Http\Requests\Bringable;

use App\Library\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBringableItemRequest extends FormRequest
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
            'required' => 'required|numeric|min:-1',
            'acquired' => 'required|numeric|min:-1',
            'assigned_id' => 'nullable|exists:users,id',

        ];
    }
}
