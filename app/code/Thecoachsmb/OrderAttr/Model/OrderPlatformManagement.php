<?php

namespace Thecoachsmb\OrderAttr\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Thecoachsmb\OrderAttr\Api\OrderPlatformManagementInterface;
use Magento\Framework\Exception\LocalizedException;

class OrderPlatformManagement implements OrderPlatformManagementInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @inheritDoc
     */
    public function updatePlatform($orderId, $platform)
    {
        try {
            // Load the order by ID
            $order = $this->orderRepository->get($orderId);

            // Update the platform field
            $order->setData('order_platform', $platform);

            // Save the order
            $this->orderRepository->save($order);

            return true;
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
    }
}
