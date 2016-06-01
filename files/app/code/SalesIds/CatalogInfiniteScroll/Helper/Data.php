<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Config paths
     */
    const XML_PATH_ENABLED        = 'catalog/salesids_cataloginfinitescroll/enabled';
    const XML_PATH_DISABLE_OUTPUT = 'advanced/modules_disable_output/SalesIds_CatalogInfiniteScroll';

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if (!$this->isModuleOutputEnabled()) {
            return false;
        }
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
