<?php
namespace Thecoachsmb\OrderAttr\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class SetOrderAttribute implements ObserverInterface
{
    private $orderRepository;
    private $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getEvent()->getOrder();

            // Set default platform to 'web' if not provided
            $platform = $order->getData('order_platform') ?: 'Web';

            // Set platform and save order
            $order->setData('order_platform', $platform);
            $this->orderRepository->save($order);

            // Update sales_order_grid table
            $order->getResource()->getConnection()->update(
                $order->getResource()->getTable('sales_order_grid'),
                ['order_platform' => $platform],
                ['entity_id = ?' => $order->getId()]
            );
        } catch (\Exception $e) {
            $this->logger->error('Error in setting order platform: ' . $e->getMessage());
        }
    }
}
