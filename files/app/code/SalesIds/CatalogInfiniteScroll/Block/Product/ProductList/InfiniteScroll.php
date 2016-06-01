<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Block\Product\ProductList;

use Magento\Catalog\Helper\Product\ProductList;
use Magento\Store\Model\ScopeInterface;
use Magento\MediaStorage\Helper\File\Storage\Database as FileStoreHelper;
use Magento\Framework\Filter\RemoveTags;
use SalesIds\CatalogInfiniteScroll\Helper\Data as DataHelper;

class InfiniteScroll extends \Magento\Framework\View\Element\Template
{
    /**
     * Config paths
     */
    const XML_PATH_PRIME_CACHE             = 'catalog/salesids_cataloginfinitescroll/prime_cache';
    const XML_PATH_SCROLL_MODE             = 'catalog/salesids_cataloginfinitescroll/scroll_mode';
    const XML_PATH_SCROLL_PAGE_LIMIT       = 'catalog/salesids_cataloginfinitescroll/scroll_page_limit';
    const XML_PATH_CONTINUE_BTN_TEXT       = 'catalog/salesids_cataloginfinitescroll/continue_btn_text';
    const XML_PATH_USE_CUSTOM_LOADER       = 'catalog/salesids_cataloginfinitescroll/use_custom_loader';
    const XML_PATH_LOADER_IMAGE_SRC        = 'catalog/salesids_cataloginfinitescroll/loader_image_src';
    const XML_PATH_SHOW_TEXT_LOADER        = 'catalog/salesids_cataloginfinitescroll/show_text_loader';
    const XML_PATH_TEXT_LOADER             = 'catalog/salesids_cataloginfinitescroll/text_loader';
    const XML_PATH_AJAX_REQUEST_TIMEOUT    = 'catalog/salesids_cataloginfinitescroll/ajax_request_timeout';
    const XML_PATH_SCROLL_TO_TOP_ENABLED   = 'catalog/salesids_cataloginfinitescroll/scroll_to_top_enabled';
    const XML_PATH_SCROLL_TO_TOP_DISTANCE  = 'catalog/salesids_cataloginfinitescroll/scroll_to_top_distance';
    const XML_PATH_SCROLL_TO_TOP_EASING    = 'catalog/salesids_cataloginfinitescroll/scroll_to_top_easing';
    const XML_PATH_SCROLL_TO_TOP_DURATION  = 'catalog/salesids_cataloginfinitescroll/scroll_to_top_duration';
    const XML_PATH_SCROLL_TO_TOP_OFFSET    = 'catalog/salesids_cataloginfinitescroll/scroll_to_top_offset';

    /**
     * Default loader image
     */
    const DEFAULT_LOADER_IMAGE_SRC         = 'SalesIds_CatalogInfiniteScroll::images/ajax-loader.gif';

    /**
     * Pager block
     *
     * @var Magento\Theme\Block\Html\Pager
     */
    protected $_pagerBlock;

    /**
     * File storage helper
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $_fileStorageHelper;

    /**
     * Remove tags from string
     *
     * @var \Magento\Framework\Filter\RemoveTags
     */
    protected $_removeTags;

    /**
     * Data helper
     *
     * @var \SalesIds\CatalogInfiniteScroll\Helper\Data
     */
    protected $_dataHelper;

    /**
     * Retrieve pager block
     *
     * @return \Magento\Theme\Block\Html\Pager|null
     */
    protected function _getPagerBlock()
    {
        if (null === $this->_pagerBlock) {
            $block = $this->getLayout()->getBlock('product_list_toolbar_pager');
            if ($block) {
                $this->_pagerBlock = $block;
            }
        }
        return $this->_pagerBlock;
    }

    /**
     * If DB file storage is on - find there, otherwise - just file_exists
     *
     * @param string $filename Relative path
     * @return bool
     */
    protected function _isFile($filename)
    {
        if ($this->_fileStorageHelper->checkDbUsage() && !$this->getMediaDirectory()->isFile($filename)) {
            $this->_fileStorageHelper->saveFileToFilesystem($filename);
        }
        return $this->getMediaDirectory()->isFile($filename);
    }

    /**
     * Check the availability to display the block
     *
     * @return bool
     */
    protected function _canDisplay()
    {
        if (!$this->_dataHelper->isEnabled()) {
            return false;
        }

        $pager = $this->_getPagerBlock();
        if (!$pager) {
            return false;
        }
        if (!$pager->getCollection()) {
            return false;
        }
        return true;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_canDisplay()) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * Initialize dependencies
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param FileStoreHelper $fileStorageHelper
     * @param RemoveTags $removeTags
     * @param DataHelper $dataHelper
     * @param array $data
     * @return void
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        FileStoreHelper $fileStorageHelper,
        RemoveTags $removeTags,
        DataHelper $dataHelper,
        array $data = []
    ) {
        $this->_fileStorageHelper = $fileStorageHelper;
        $this->_removeTags = $removeTags;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve whether the next page must be preloaded
     *
     * @return int
     */
    public function getPrimeCache()
    {
        return (int)$this->_scopeConfig->isSetFlag(
            self::XML_PATH_PRIME_CACHE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll mode
     *
     * @return string
     */
    public function getScrollMode()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll page limit
     *
     * @return int
     */
    public function getScrollPageLimit()
    {
        return (int)$this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_PAGE_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve continue button text
     *
     * @return string
     */
    public function getContinueBtnText()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_CONTINUE_BTN_TEXT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve whether the store owner wants to use a custom image loader
     *
     * @return int
     */
    public function getUseCustomLoader()
    {
        return (int)$this->_scopeConfig->isSetFlag(
            self::XML_PATH_USE_CUSTOM_LOADER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get loader image URL
     *
     * @return string
     */
    public function getLoaderImageSrc()
    {
        if (!$this->getUseCustomLoader()) {
            return $this->getViewFileUrl(self::DEFAULT_LOADER_IMAGE_SRC);
        }

        $folderName = \SalesIds\CatalogInfiniteScroll\Model\Config\Backend\Image\Loader::UPLOAD_DIR;
        $loaderImagePath = $this->_scopeConfig->getValue(
            self::XML_PATH_LOADER_IMAGE_SRC,
            ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $loaderImagePath;
        $loaderImageUrl = $this->_urlBuilder
            ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;

        if (null !== $loaderImagePath && $this->_isFile($path)) {
            $url = $loaderImageUrl;
        } else {
            $url = $this->getViewFileUrl(self::DEFAULT_LOADER_IMAGE_SRC);
        }
        return $url;
    }

    /**
     * Retrieve whether the store owner wants to show a text loader
     *
     * @return int
     */
    public function getShowTextLoader()
    {
        return (int)$this->_scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_TEXT_LOADER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve text loader
     *
     * @return string
     */
    public function getTextLoader()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_TEXT_LOADER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve AJAX request timeout
     *
     * @return int
     */
    public function getAjaxRequestTimeout()
    {
        return (int)$this->_scopeConfig->getValue(
            self::XML_PATH_AJAX_REQUEST_TIMEOUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve whether the scroll-to-top button is enabled
     *
     * @return int
     */
    public function getIsScrollToTopEnabled()
    {
        return (int)$this->_scopeConfig->isSetFlag(
            self::XML_PATH_SCROLL_TO_TOP_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll-to-top distance
     *
     * @return int
     */
    public function getScrollToTopDistance()
    {
        return (int)$this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_TO_TOP_DISTANCE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll-to-top easing
     *
     * @return string
     */
    public function getScrollToTopEasing()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_TO_TOP_EASING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll-to-top easing duration
     *
     * @return int
     */
    public function getScrollToTopDuration()
    {
        return (int)$this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_TO_TOP_DURATION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve scroll-to-top offset
     *
     * @return int
     */
    public function getScrollToTopOffset()
    {
        return (int)$this->_scopeConfig->getValue(
            self::XML_PATH_SCROLL_TO_TOP_OFFSET,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve current page URL
     *
     * @return string
     */
    public function getCurrentPageUrl()
    {
        $pager = $this->_getPagerBlock();

        if ($pager->isFirstPage()) {
            $pageUrl = $pager->getPageUrl(null);
        } else {
            $pageUrl = $pager->getPageUrl($pager->getCurrentPage());
        }
        return $this->_removeTags->filter($pageUrl);
    }

    /**
     * Retrieve next page URL
     *
     * @return string
     */
    public function getNextPageUrl()
    {
        $pager = $this->_getPagerBlock();
        if ($pager->isLastPage()) {
            return '';
        }
        return $this->_removeTags->filter($pager->getNextPageUrl());
    }

    /**
     * Retrieve total number of pages
     *
     * @return int
     */
    public function getPageTotalNum()
    {
        return $this->_getPagerBlock()->getTotalNum();
    }
}
