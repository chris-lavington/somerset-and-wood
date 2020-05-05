<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreSwitcher\Model;

use Magento\Framework\Serialize\SerializerInterface;

/**
 * Store Switcher obserever
 */
class Observer implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Observer constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * Prepare store data after load
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return \MageWorx\StoreSwitcher\Model\Observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $storeModel = $observer->getEvent()->getObject();
        if (!$storeModel) {
            return $this;
        }

        if (!($storeModel instanceof \Magento\Store\Model\Store\Interceptor)) {
            return $this;
        }
        
        if (!is_array($storeModel->getGeoipCountryCode())) {
            $storeModel->setGeoipCountryCode(
                $this->serializer->unserialize($storeModel->getGeoipCountryCode())
            );
        }
        
        return $this;
    }
}
