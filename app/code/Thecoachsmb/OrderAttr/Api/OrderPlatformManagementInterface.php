<?php

namespace Thecoachsmb\OrderAttr\Api;

interface OrderPlatformManagementInterface
{
    /**
     * Update the platform field of an order.
     *
     * @param int $orderId
     * @param string $platform
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updatePlatform($orderId, $platform);
}
