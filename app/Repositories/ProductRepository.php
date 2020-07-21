<?php

namespace App\Repositories;

use App\Exceptions\ValidationResponseException;
use App\Models\Product;

class ProductRepository extends BaseRepository
{
    /**
     * ProductRepository constructor.
     */
    public function __construct()
    {
        $this->setModel(Product::class);
    }

    const VALID_CREATE_PARAMS = [
        'name',
        'available_stock'
    ];

    const VALID_UPDATE_PARAMS = [
        'id',
        'name',
        'available_stock'
    ];

    const VALID_ORDER_PARAMS = [
        'id',
        'quantity'
    ];

    /**
     * @return mixed
     */
    public function getAllProducts()
    {
        return $this->all()
            ->toArray();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getSpecificProductByID(int $id)
    {
        return $this->find($id)->toArray();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createProduct($request)
    {
        $params = $request->only(self::VALID_CREATE_PARAMS);

        return $this->create($params);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function updateProduct($request)
    {
        $params = $request->only(self::VALID_UPDATE_PARAMS);

        $id = $params['id'];
        unset($params['id']);

        return $this->update($id, $params);
    }

    /**
     * @param $request
     * @return mixed
     * @throws ValidationResponseException
     */
    public function orderProduct($request)
    {
        $params = $request->only(self::VALID_ORDER_PARAMS);

        $id = $params['id'];
        unset($params['id']);
        $params['quantity'] = (int)$params['quantity'];

        $product = $this->find($id);
        if (!($product->available_stock >= $params['quantity'])) {
            throw new ValidationResponseException('Failed to order this product due to unavailability of the stock', ['quantity' => 'Cannot order product greater than stocks!']);
        }

        $product->available_stock = $product->available_stock - $params['quantity'];
        return $product->save();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteProduct($id)
    {
        if ($model = $this->find($id)) {
            return $model->destroy($id);
        }

        return false;
    }
}
