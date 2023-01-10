<?php

namespace App\Http\Requests;

use App\Profession;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
                'profession_id' => Rule::exist('profession', 'id')->whereNull('deleted_at'),
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
                'profession_id' => $data['profession_id'] ?? null,
            ]);

            $user->profile()->create([
                'bio' => $data['bio'],//this->bio
                'twitter' => $data ['twitter'] ?? null,//this->twitter
            ]);
        });
    }
}
