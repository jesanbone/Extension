<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Model\Config\Source;

class ScrollMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Scroll modes
     *
     * @var array
     */
    protected $_modes = [
        'auto'       => 'Automatic',
        'auto_up_to' => 'Automatic up to X pages, then manual',
        'auto_each'  => 'Automatic each X pages',
        'manual'     => 'Manual'
    ];

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->_modes as $mode => $label) {
            $options[] = [
                'value' => $mode,
                'label' => __($label)
            ];
        }

        return $options;
    }
}
