<?php

namespace App\Api\V1\Requests;



use App\Helpers\RuleHelper;
use App\User;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UserRequest
 * @package App\Api\V1\Requests
 * @bodyParam phone string required phone number of the user
 * @bodyParam remenber_token string token
 * @bodyParam password string required password of the user
 * @bodyParam alternative_phone string alternative phone number
 * @bodyParam email string required email of the user
 * @bodyParam google_oauth_id string id of google authentication
 * @bodyParam has_accept_disclaimer boolean has he accepted the disclaimer or not?
 * @bodyParam device_tokens array an array containing device tokens of user
 * @bodyParam settings array an array containing some data related to user
 * @bodyParam address_id int required id of the address
 * @bodyParam invite_by int id of the user who invite this user
 * @bodyParam status string status of the user values ( new , enabled, disabled)
 */
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $rules = [
            'username'=>'required|min:0|max:255|unique:users,username',
            'name'=>'min:0|max:255',
            'phone'=>'min:9|max:14',
            'email'=>'email|max:255',
            'type' => Rule::in(User::$Type),
            'status' => Rule::in(User::$Status),
            'has_reset_password'=>'boolean',
            'remenber_token'=>'min:0|max:100',
            'password'=>'required|min:6|max:255',
            'bvs_id'=>'min:0|max:255|unique:users,bvs_id',
            'settings'=>'array',


        ];
//        \Log::info("user_id:$this->route('users')");
        if($this->method()=='PUT'){
            $rules['username'].=','.$this->route('user');
            $rules['bvs_id'].=','.$this->route('user');
        }
        return RuleHelper::get_rules($this->method(),$rules);
    }
}
