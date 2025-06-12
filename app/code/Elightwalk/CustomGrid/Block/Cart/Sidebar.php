<?php

namespace Elightwalk\CustomGrid\Block\Cart;

use Magento\Framework\View\Element\Template;

/**
 * Class Sidebar
 *
 * Represents the block for the cart sidebar.
 */
class Sidebar extends Template
{
    /**
     * Sidebar constructor.
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        // Call the parent constructor to initialize the block.
        parent::__construct($context, $data);
    }
}
