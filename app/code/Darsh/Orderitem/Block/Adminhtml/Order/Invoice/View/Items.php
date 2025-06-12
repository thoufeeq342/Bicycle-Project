<?php

namespace Darsh\Orderitem\Block\Adminhtml\Order\Invoice\View;

use Magento\Sales\Block\Adminhtml\Order\Invoice\View\Items as CoreItems;

class Items extends CoreItems
{
    /**
     * Get custom attribute for the item
     *
     * @param \Magento\Sales\Model\Order\Invoice\Item $item
     * @return string
     */
    public function getCustomAttribute($item)
    {
        // Example logic: Get SKU as custom attribute
        return $item->getOrderItem()->getSku();
    }
}
