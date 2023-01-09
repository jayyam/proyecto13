<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateUserRequest extends FormRequest
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
        return
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'bio' => 'required',
                'twitter' => ['nullable','url'],//hacer regex para validar url
            ];
    }

    public function messages()
    {
        return [
        'name.required' => 'El campo nombre es obligatorio',
        'email.required' => 'El campo email es obligatorio',
        'password.required' => 'El campo password es obligatorio',
        ];
    }

    public function createUser()
    {
        DB::transaction(function ()
        {
            $data =$this->validated();

            $user = User::create([
                'name' => $data['name'],//this->name
                'email' => $data['email'],//this->email
                'password' =>bcrypt($data['password']),//this->password
            ]);

            $user->profile()->create([
                'bio' => $data['bio'],//this->bio
                'twitter' => array_get($data, 'twitter'),//this->twitter
            ]);
        });
    }
}
