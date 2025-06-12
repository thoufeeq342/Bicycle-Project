<?php
namespace BSS\Deliverydate\Observer;
class SaveToOrder implements \Magento\Framework\Event\ObserverInterface
{   
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $order = $event->getOrder();
        $order->setData('delivery_date', $quote->getData('delivery_date'));
        // Transfer the additional phone number
        $order->setData('additional_phone_number', $quote->getData('additional_phone_number'));
    }
}