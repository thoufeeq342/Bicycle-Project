<?php
namespace Magefan\DeliveryDate\Observer;
class SaveToOrder implements \Magento\Framework\Event\ObserverInterface
{   
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $order = $event->getOrder();
        
        // Save delivery date
        $order->setData('delivery_date', $quote->getData('delivery_date'));

        // Save additional phone number
        $order->setData('additional_phone', $quote->getData('additional_phone'));
    }
}