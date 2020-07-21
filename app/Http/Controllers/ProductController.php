<?php

namespace App\Http\Controllers;

use App\Exceptions\RepositoryRequestException;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends BaseController
{
    const GET_SPECIFIC_PRODUCT_MESSAGE = 'Specific Product.';
    const GET_ALL_PRODUCTS_MESSAGE = 'List of all products';

    const CREATE_PRODUCT_SUCCESS_MESSAGE = 'Product record successfully created.';
    const UPDATE_PRODUCT_SUCCESS_MESSAGE = 'Product record successfully updated.';
    const ORDER_PRODUCT_SUCCESS_MESSAGE = 'You have successfully ordered this product.';
    const ARCHIVE_PRODUCT_SUCCESS_MESSAGE = 'Product record successfully archived.';

    const CREATE_PRODUCT_FAILED_MESSAGE = 'Failed to create home image record with params -> "';
    const UPDATE_PRODUCT_FAILED_MESSAGE = 'Failed to update home image record with params -> "';
    const ORDER_PRODUCT_FAILED_MESSAGE = 'Failed to update home image record with params -> "';
    const ARCHIVE_PRODUCT_FAILED_MESSAGE = 'Failed to archive home image record with params -> "';

    protected $productRepository;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->sendResponseOk($this->productRepository->getAllProducts(), self::GET_ALL_PRODUCTS_MESSAGE);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return $this->sendResponseOk($this->productRepository->getSpecificProductByID($id), self::GET_SPECIFIC_PRODUCT_MESSAGE);
    }

    /**
     * @param ProductRequest $productRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     */
    public function create(ProductRequest $productRequest)
    {
        if ($this->productRepository->createProduct($productRequest)) {
            $this->flagAction = true;

            return $this->sendResponseOk([], self::CREATE_PRODUCT_SUCCESS_MESSAGE);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::CREATE_PRODUCT_FAILED_MESSAGE . print_r($productRequest->all(), true)
        );
    }

    /**
     * @param ProductRequest $productRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     */
    public function update(ProductRequest $productRequest)
    {
        if ($this->productRepository->updateProduct($productRequest)) {
            return $this->sendResponseOk([], self::UPDATE_PRODUCT_SUCCESS_MESSAGE);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::UPDATE_PRODUCT_FAILED_MESSAGE . print_r($productRequest->all(), true)
        );
    }

    /**
     * @param ProductRequest $productRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     * @throws \App\Exceptions\ValidationResponseException
     */
    public function order(ProductRequest $productRequest)
    {
        if ($this->productRepository->orderProduct($productRequest)) {
            return $this->sendResponseOk([], self::ORDER_PRODUCT_SUCCESS_MESSAGE);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::ORDER_PRODUCT_FAILED_MESSAGE . print_r($productRequest->all(), true)
        );
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     */
    public function destroy(int $id)
    {
        if ($this->productRepository->deleteProduct($id)) {
            return $this->sendResponseOk([], self::ARCHIVE_PRODUCT_SUCCESS_MESSAGE);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::ARCHIVE_PRODUCT_FAILED_MESSAGE . print_r(['id' => $id], true)
        );
    }
}
