<?php
namespace Magezon\Deliverydate\Observer;
class SaveToOrder implements \Magento\Framework\Event\ObserverInterface
{   
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        // Transfer data from quote to order
        $order->setData('delivery_date', $quote->getData('delivery_date'));
        $order->setData('additional_phone_number', $quote->getData('additional_phone_number'));
    }
}