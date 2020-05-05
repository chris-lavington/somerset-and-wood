<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * "My Gallery" link
 */
namespace SomersetWood\MyGallery\Plugin;

/**
 * Class Link
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Link 
extends \Magento\Framework\View\Element\Html\Link
{
  
    public function beforeGetHref()
    {
        return $this->getUrl('my-gallery');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function beforeGetLabel()
    {
        return __('My Gallery');
    }
}
