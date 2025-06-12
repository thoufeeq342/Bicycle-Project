<?php

namespace Code5fixer\Dropdown\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Code5fixer\Dropdown\Helper\Data;

class Config extends Action
{
    protected $helper;

    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        // Retrieve configuration value
        $enabled = $this->helper->getGeneralConfig('enable');
        $displayText = $this->helper->getGeneralConfig('display_text');

        if ($enabled) {
            echo "Configuration Enabled. Display Course: " . $displayText;
        } else {
            echo "Configuration Disabled.";
        }
    }
}
