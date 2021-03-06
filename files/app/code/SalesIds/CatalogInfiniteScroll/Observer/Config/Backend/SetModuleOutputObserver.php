<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Observer\Config\Backend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use SalesIds\CatalogInfiniteScroll\Helper\Data as DataHelper;

class SetModuleOutputObserver implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var WriterInterface
     */
    protected $_configWriter;

    /**
     * Initialize dependencies
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_configWriter = $configWriter;
    }

    /**
     * Enable / disable module output
     *
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    protected function _enableDisableModuleOutput($scope, $scopeId = 0)
    {
        // Retrieve the module state (enabled or disabled)
        $value = $this->_scopeConfig->isSetFlag(
            DataHelper::XML_PATH_ENABLED,
            $scope,
            $scopeId
        );

        // Enable / disable module output
        $this->_configWriter->save(
            DataHelper::XML_PATH_DISABLE_OUTPUT,
            !$value,
            $scope,
            $scopeId
        );
    }

    /**
     * Enable / disable module output when the catalog section is saved
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();

        $websiteCode = $event->getWebsite();
        if ($websiteCode) {
            $this->_enableDisableModuleOutput(
                ScopeInterface::SCOPE_WEBSITES,
                $websiteCode
            );
            return $this;
        }

        $storeCode = $event->getStore();
        if ($storeCode) {
            $this->_enableDisableModuleOutput(
                ScopeInterface::SCOPE_STORES,
                $storeCode
            );
            return $this;
        }

        $this->_enableDisableModuleOutput(
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        return $this;
    }
}
