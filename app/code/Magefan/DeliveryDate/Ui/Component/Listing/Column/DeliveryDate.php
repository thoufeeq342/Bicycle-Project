<?php
namespace Magefan\DeliveryDate\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;

class DeliveryDate extends Column
{
   protected $_orderRepository;
   protected $_searchCriteria;

   public function __construct(ContextInterface $context, UiComponentFactory 
$uiComponentFactory, OrderRepositoryInterface $orderRepository, 
SearchCriteriaBuilder $criteria, array $components = [], array $data = [])
   {
       $this->_orderRepository = $orderRepository;
       $this->_searchCriteria = $criteria;
       parent::__construct($context, $uiComponentFactory, $components, $data);
   }

   public function prepareDataSource(array $dataSource)
   {
       if (isset($dataSource['data']['items'])) {
           foreach ($dataSource['data']['items'] as & $item) {

               $order = $this->_orderRepository->get($item["entity_id"]);
               $date = $order->getData("delivery_date");
               $item[$this->getData('name')] = $date;
           }
       }

       return $dataSource;
   }
}