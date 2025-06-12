<?php

namespace Elightwalk\CustomGrid\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const PRICE_SHIPPING_BAR = 'shippingbar/shippingsection/shipping_bar';

    /**
     * Get the maximum price for the shipping bar.
     *
     * @return int|string|null
     */
    public function getPriceForShippingBar()
    {
        return $this->scopeConfig->getValue(
            self::PRICE_SHIPPING_BAR,
            ScopeInterface::SCOPE_STORE
        );
    }
}
