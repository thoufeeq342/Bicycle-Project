<?php

namespace MagicToolbox\MagicZoom\Controller\Adminhtml\Settings;

use MagicToolbox\MagicZoom\Controller\Adminhtml\Settings;
use Magento\Framework\App\Cache\TypeListInterface;

class Save extends \MagicToolbox\MagicZoom\Controller\Adminhtml\Settings
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \MagicToolbox\MagicZoom\Helper\DataFactory $dataHelperFactory
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MagicToolbox\MagicZoom\Helper\DataFactory $dataHelperFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context, $resultPageFactory, $dataHelperFactory);
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Save action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $activeTab = $this->getRequest()->getParam('active_tab');
        $data = $this->getRequest()->getPostValue();
        $model = $this->_objectManager->create(\MagicToolbox\MagicZoom\Model\Config::class);
        $collection = $model->getCollection();

        if ($collection->count()) {
            foreach ($collection as $item) {
                $itemData = $item->getData();
                //NOTE: 0 - desktop, 1 - mobile
                $platform = (int)$itemData['platform'] ? 'mobile' : 'desktop';
                if (isset($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']])) {
                    if ($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']] !== $itemData['value']) {
                        $item->setValue($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']]);
                    }
                }
                $status = (int)$itemData['status'];
                if ($status < 2) {
                    $newStatus = isset($data['magictoolbox-switcher'][$platform][$itemData['profile']][$itemData['name']]) ? 1 : 0;
                    if ($status != $newStatus) {
                        $item->setStatus($newStatus);
                    }
                }
                $item->save();
            }
        }

        //NOTE: refresh 'Blocks HTML output'
        $this->cacheTypeList->cleanType('block_html');

        //NOTE: refresh 'Page Cache'
        $this->cacheTypeList->cleanType('full_page');

        $this->messageManager->addSuccess(__('You saved the settings.'));

        $resultRedirect->setPath('magiczoom/*/edit', ['active_tab' => $activeTab]);

        return $resultRedirect;
    }
}
