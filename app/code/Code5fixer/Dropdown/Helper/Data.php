<?php

namespace Code5fixer\Dropdown\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_GENERAL = 'Dropdown/general/';

    /**
     * Get configuration value by field name
     *
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getGeneralConfig($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
