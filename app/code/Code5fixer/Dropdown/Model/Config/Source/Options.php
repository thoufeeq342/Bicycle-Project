<?php

namespace Code5fixer\Dropdown\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'Magento', 'label' => __('Magento')],
            ['value' => 'Wordpress', 'label' => __('Wordpress')],
            ['value' => 'PHP', 'label' => __('PHP')],
        ];
    }
}
