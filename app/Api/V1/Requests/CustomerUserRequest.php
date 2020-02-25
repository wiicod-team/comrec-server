<?php

namespace App\Api\V1\Requests;



use App\Helpers\RuleHelper;
use Dingo\Api\Http\FormRequest;

/**
 * @bodyParam customer_id int required  The Id of the customer.
 * @bodyParam produc_id int required The Id of the user.
 */

class CustomerUserRequest extends FormRequest
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
            'customer_id'=>'required|integer|exists:categories,id|unique_with:customer_users,user_id',
            'user_id'=>'required|integer|exists:users,id',
        ];
        return RuleHelper::get_rules($this->method(),$rules);
    }
}
