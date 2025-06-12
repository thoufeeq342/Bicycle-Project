<?php

namespace Magezon\Deliverydate\Controller\Quote;

use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $quoteIdMaskFactory;
    protected $quoteRepository;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        parent::__construct($context);
        $this->quoteRepository = $quoteRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if ($post) {
            $cartId = $post['cartId'];
            $deliveryDate = $post['delivery_date'];
            $additionalPhoneNumber = $post['additional_phone_number'];
            $isCustomer = $post['is_customer'];

            if ($isCustomer === 'false') {
                $cartId = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id')->getQuoteId();
            }

            $quote = $this->quoteRepository->getActive($cartId);
            if (!$quote->getItemsCount()) {
                throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
            }

            $quote->setData('delivery_date', $deliveryDate);
            $quote->setData('additional_phone_number', $additionalPhoneNumber); // Save additional phone number
            $this->quoteRepository->save($quote);
        }
    }
}
