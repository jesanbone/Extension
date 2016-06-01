<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Model\Config\Source;

class Easing implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Easings
     *
     * @var array
     */
    protected $_easings = [
        'linear',
        'easeInQuad',
        'easeOutQuad',
        'easeInOutQuad',
        'easeInCubic',
        'easeOutCubic',
        'easeInOutCubic',
        'easeInQuart',
        'easeOutQuart',
        'easeInOutQuart',
        'easeInQuint',
        'easeOutQuint',
        'easeInOutQuint',
        'easeInExpo',
        'easeOutExpo',
        'easeInOutExpo',
        'easeInSine',
        'easeOutSine',
        'easeInOutSine',
        'easeInCirc',
        'easeOutCirc',
        'easeInOutCirc',
        'easeInElastic',
        'easeOutElastic',
        'easeInOutElastic',
        'easeInBack',
        'easeOutBack',
        'easeInOutBack',
        'easeInBounce',
        'easeOutBounce',
        'easeInOutBounce'
    ];

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'swing',
                'label' => __('Default (%1)', 'swing')
            ]
        ];

        foreach ($this->_easings as $easing) {
            $options[] = [
                'value' => $easing,
                'label' => $easing
            ];
        }

        return $options;
    }
}
