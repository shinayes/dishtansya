<?php

namespace App\Http\Requests;

class ProductRequest extends BaseRequest
{
    const ROUTE_POST_CREATE_PRODUCT = 'product_create_record';
    const ROUTE_POST_UPDATE_PRODUCT = 'product_update_record';
    const ROUTE_POST_ORDER_PRODUCT = 'product_order';
    
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
        $route = $this->route()->action;

        $return = [];

        switch ($this->method()) {
            case parent::POST_METHOD:
                if ($route['name'] === self::ROUTE_POST_CREATE_PRODUCT) {
                    $return = [
                        'name' => 'required|max:100',
                        'available_stock' => 'required|integer',
                    ];
                } elseif ($route['name'] === self::ROUTE_POST_UPDATE_PRODUCT) {
                    $return = [
                        'id' => 'required|exists:products,id|integer',
                        'name' => 'required|max:100',
                        'available_stock' => 'required|integer',
                    ];
                } elseif ($route['name'] === self::ROUTE_POST_ORDER_PRODUCT) {
                    $return = [
                        'id' => 'required|exists:products,id|integer',
                        'quantity' => 'required|integer'
                    ];
                }

                break;
            case parent::PATCH_METHOD:
                break;
        }
        return $return;
    }
}
