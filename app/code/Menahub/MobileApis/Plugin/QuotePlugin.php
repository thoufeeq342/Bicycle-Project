<?php

/**
 * Copyright © 2018 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Menahub\MobileApis\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Catalog\Api\ProductRepositoryInterfaceFactory as ProductRepository;
use Magento\Catalog\Helper\ImageFactory as ProductImageHelper;


class QuotePlugin
{

    /**
     * @var CartItemExtensionFactory
     */
    protected $cartItemExtension;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param CartItemExtensionFactory $cartItemExtension
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(CartItemExtensionFactory $cartItemExtension, ProductRepository $productRepository, ProductImageHelper $productImageHelper)
    {
        $this->productImageHelper = $productImageHelper;
        $this->cartItemExtension = $cartItemExtension;
        $this->productRepository = $productRepository;
    }

    /**
     * Add attribute values
     *
     * @param CartRepositoryInterface $subject ,
     * @param   $quote
     * @return  $quoteData
     */
    public function afterGet(CartRepositoryInterface $subject, $quote)
    {
        $quoteData = $this->setAttributeValue($quote);
        return $quoteData;
    }

    /**
     * set value of attributes
     *
     * @param   $product ,
     * @return  $extensionAttributes
     */
    private function setAttributeValue($quote)
    {
        $data = [];
        if ($quote->getItemsCount()) {
            foreach ($quote->getItems() as $item) {
                $data = [];
                $extensionAttributes = $item->getExtensionAttributes();
                if ($extensionAttributes === null) {
                    $extensionAttributes = $this->cartItemExtension->create();
                }
                $product = $this->productRepository->create()->get($item->getSku());
                $imageurl = $this->productImageHelper->create()->init($product, 'product_thumbnail_image')->setImageFile($product->getThumbnail())->getUrl();

                $extensionAttributes->setImage($imageurl);

                $item->setExtensionAttributes($extensionAttributes);
            }
        }

        return $quote;
    }

    public function afterGetList(CartRepositoryInterface $subject, CartSearchResultsInterface $searchResults)
    {
        foreach ($searchResults->getItems() as $entity) {
            foreach ($entity->getItems() as $singleItem) {
                $extensionAttributes = $singleItem->getExtensionAttributes();
                if ($extensionAttributes === null) {
                    $extensionAttributes = $this->cartItemExtension->create();
                }
                $product = $this->productRepository->create()->get($singleItem->getSku());
                $imageurl = $this->productImageHelper->create()->init($product, 'product_thumbnail_image')->setImageFile($product->getThumbnail())->getUrl();
                $extensionAttributes->setImage($imageurl);
                $singleItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $searchResults;
    }

    /**
     * Add attribute values
     *
     * @param CartRepositoryInterface $subject ,
     * @param   $quote
     * @return  $quoteData
     */
    public function afterGetActiveForCustomer(CartRepositoryInterface $subject, $quote)
    {
        $quoteData = $this->setAttributeValue($quote);
        return $quoteData;
    }

}