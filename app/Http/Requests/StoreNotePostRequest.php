<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNotePostRequest extends FormRequest
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
            'folder_id' => ['required','nullable', 'numeric'],
            'private' => ['required', 'boolean'],
            'type' => ['required'],
            'title' => ['required', 'max:255'],
            'text' => ['required']
        ];
    }
}
