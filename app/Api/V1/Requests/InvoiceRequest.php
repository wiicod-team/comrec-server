<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use App\Invoice;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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
        $ign = null;
        if ($this->method() == 'PUT'){
            $ign =','. $this->route('bill');
        }
        $rules = [
            'amount' => 'required|numeric',
            'status' => Rule::in(Invoice::$Status),
            'payment_method'=>Rule::in(Invoice::$PaymentMethods),
            'payment_number'=>'required|max:255',
            'payment_id'=>'max:255',
            'payment_responds'=>'max:255',
            'user_id'=>'required|integer|exists:users,id',
        ];


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
