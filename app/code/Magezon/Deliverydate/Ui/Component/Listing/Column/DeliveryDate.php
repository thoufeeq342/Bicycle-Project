<?php
namespace Magezon\DeliveryDate\Ui\Component\Listing\Column;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class DeliveryDate extends Column
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        array $components = [],
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    try {
                        $order = $this->orderRepository->get($item['entity_id']);
                        $deliveryDate = $order->getExtensionAttributes() && $order->getExtensionAttributes()->getDeliveryDate()
                            ? $order->getExtensionAttributes()->getDeliveryDate()
                            : __('N/A');
                            // Get Additional Phone Number
                        $additionalPhone = $order->getExtensionAttributes() && $order->getExtensionAttributes()->getAdditionalPhone()
                        ? $order->getExtensionAttributes()->getAdditionalPhone()
                        : __('N/A');
                        $item[$this->getData('name')] = $deliveryDate;
                        $item[$this->getData('name') . '_additional_phone'] = $additionalPhone;
                    } catch (\Exception $e) {
                        $item[$this->getData('name') . '_delivery_date'] = __('Error');
                        $item[$this->getData('name') . '_additional_phone'] = __('Error');
                    }
                }
            }
        }

        return $dataSource;
    }
}
