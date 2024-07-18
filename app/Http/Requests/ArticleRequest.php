<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title'=> $this->method() == 'POST' ? 
            ['required', 'max:20', 'unique:articles,title'] :
            ['required', 'max:20', Rule::unique('articles', 'title')->ignore($this->article)],
            'content'=> ['required'],
            'category'=>['sometimes', 'nullable', 'exists:categories,id'],
        ];
    }

    // public function attributes()
    // {
    //     return [
    //         'title' => 'super titre',
    //     ];
    // }

    // public function messages()
    // {
    //     return [
    //         'title.required' => 'Et le titre ?',
    //         'content.required' => 'T\'as pas oublié un truc?',
    //     ];
    // }
}
