<?php

namespace App\Api\V1\Requests;



use App\Helpers\RuleHelper;
use Dingo\Api\Http\FormRequest;

/**
 * Class ReceiptRequest
 * @package App\Api\V1\Requests
 * @bodyParam pdf string required pdf file of the recipe
 * @bodyParam bill_id int required id of the related payment
 */
class ReceiptRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'note' => 'max:255',
            'bill_id'=>'required|integer|exists:bills,id',
            'user_id'=>'required|integer|exists:users,id',
        ];
        return RuleHelper::get_rules($this->method(),$rules);
    }
}
