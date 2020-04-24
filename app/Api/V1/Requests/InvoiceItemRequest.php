<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use App\InvoiceItem;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceItemRequest extends FormRequest
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

        $rules = [
            'quantity' => 'required|numeric',
            'price_was' => 'numeric',
            'invoice_id'=>'required|integer|exists:invoices,id',
            'concern_id'=>'required|required_with:concern_type|integer|morph_exists:concern_type',
            'concern_type'=>Rule::in(InvoiceItem::$Concerns),

        ];



        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
