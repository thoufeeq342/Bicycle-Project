<?php
namespace Custom\Test\Api;

interface OrderDetailsRepositoryInterface
{
    /**
     * Get order by ID
     *
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrderById($orderId);
}