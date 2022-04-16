<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EditTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'user_id' => 'sometimes',
            'edit_title' => 'required|string',
            'edit_description' => 'string|nullable',
            'edit_due_date' => 'date_format:d-M-Y h:i A|nullable',
            'edit_is_completed' => 'sometimes|boolean'
        ];
    }
}
