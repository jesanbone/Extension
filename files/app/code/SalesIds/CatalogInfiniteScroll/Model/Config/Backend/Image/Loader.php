<?php
/**
 * Copyright © 2016 SalesIds. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SalesIds\CatalogInfiniteScroll\Model\Config\Backend\Image;

class Loader extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * The tail part of directory path for uploading
     */
    const UPLOAD_DIR = 'salesids/catalog_infinite_scroll/loader';

    /**
     * Return path to directory for upload file
     *
     * @return string
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    /**
     * Makes a decision about whether to add info about the scope
     *
     * @return bool
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    /**
     * Getter for allowed extensions of uploaded files
     *
     * @return array
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }
}
