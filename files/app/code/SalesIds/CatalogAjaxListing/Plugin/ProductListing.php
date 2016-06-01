<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogAjaxListing\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filter\RemoveTags;
use Psr\Log\LoggerInterface;

class ProductListing
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ResponseInterface
     */
    protected $_response;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * Remove tags from string
     *
     * @var \Magento\Framework\Filter\RemoveTags $removeTags
     */
    protected $_removeTags;

    /**
     * Query parameters added for AJAX requests
     *
     * @var array
     */
    protected $_ajaxQueryParams = ['_', 'ajax'];

    /**
     * Initialize dependencies
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     * @param JsonFactory $resultJsonFactory
     * @param RemoveTags $removeTags
     * @return void
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger,
        JsonFactory $resultJsonFactory,
        RemoveTags $removeTags
    ) {
        $this->_request = $request;
        $this->_response = $response;
        $this->_logger = $logger;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_removeTags = $removeTags;
    }

    /**
     * Remove query parameters added for AJAX requests
     *
     * @return void
     */
    protected function _removeAjaxQueryParams()
    {
        /** @var \Zend\Stdlib\Parameters */
        $query = $this->_request->getQuery();
        if (count($query) > 0) {
            foreach ($this->_ajaxQueryParams as $queryParam) {
                $query->set($queryParam, null);
            }
        }
    }

    /**
     * Build a JSON response
     *
     * @param array $data Response data
     * @return \Magento\Framework\Controller\Result\Json
     */
    protected function _jsonResponse($data)
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJsonFactory->create();
        $resultJson->setHeader('Content-type', 'application/json', true);
        $resultJson->setData($data);
        return $resultJson;
    }

    /**
     * Retrieve current page URL
     *
     * @param \Magento\Theme\Block\Html\Pager $pager
     * @return string
     */
    protected function _getCurrentPageUrl($pager)
    {
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
     * @param \Magento\Theme\Block\Html\Pager $pager
     * @return string
     */
    protected function _getNextPageUrl($pager)
    {
        if ($pager->isLastPage()) {
            return '';
        }
        return $this->_removeTags->filter($pager->getNextPageUrl());
    }
}
