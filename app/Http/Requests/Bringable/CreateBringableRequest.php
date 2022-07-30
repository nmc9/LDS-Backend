<?php

namespace App\Http\Requests\Bringable;

use App\Library\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBringableRequest extends FormRequest
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
            'name' => 'required',
            'notes' => 'nullable',
            'importance' => ['required', Rule::in(Constants::BRINGABLE_LEVELS)],
            'assigned_id' => 'nullable|exists:users,id',
            'required' => 'required|numeric|min:-1',
            'acquired' => 'required|numeric|min:-1',
        ];
    }
}
