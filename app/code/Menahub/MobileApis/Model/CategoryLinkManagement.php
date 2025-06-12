<?php

namespace Menahub\MobileApis\Model;

use Menahub\MobileApis\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class CategoryLinkManagement implements CategoryLinkManagementInterface
{
    protected $productRepositoryInterface;
    protected $collectionFactory;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        CollectionFactory $collectionFactory

    ) {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->collectionFactory = $collectionFactory;
    }

    public function getProducts($pageSize = 10, $currentPage = 1)
    {
        
        $products = $this->collectionFactory->create();
        $pageSize = $products->getPageSize($pageSize);
        $currentPage = $products->getCurPage($currentPage);
     
        // $items = [];
        // foreach ($products as $product) {
            return $products->getData();
        // }
    }
}

