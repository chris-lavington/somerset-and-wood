<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreSwitcher\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Switcher
 */
class Switcher
{
    /**
     * @var \MageWorx\GeoIP\Helper\Customer
     */
    private $geoIpHelperCustomer;

    /**
     * @var \MageWorx\StoreSwitcher\Model\Switcher
     */
    private $modelSwitcher;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $response;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * Switcher constructor.
     *
     * @param \MageWorx\GeoIP\Helper\Customer $geoIpHelperCustomer
     * @param \MageWorx\StoreSwitcher\Model\Switcher $modelSwitcher
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        \MageWorx\GeoIP\Helper\Customer $geoIpHelperCustomer,
        \MageWorx\StoreSwitcher\Model\Switcher $modelSwitcher,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    ) {
        $this->geoIpHelperCustomer = $geoIpHelperCustomer;
        $this->modelSwitcher       = $modelSwitcher;
        $this->response            = $response;
        $this->storeRepository     = $storeRepository;
    }

    /**
     * @param \Magento\Framework\App\FrontControllerInterface $subject
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function beforeDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if (!$this->modelSwitcher->isAllowed($request)) {
            return;
        }

        $customerStoreCode = $this->modelSwitcher->getCustomerStoreCode();
        if (!$customerStoreCode) {
            return;
        }

        $storeCookie = $this->geoIpHelperCustomer->getCookie('geoip_store_code');

        if (!$storeCookie || $request->getParam('geoip_country', false)) {
            try {
                /** @var \Magento\Store\Model\Store $store */
                $store = $this->storeRepository->get($customerStoreCode);
            } catch (NoSuchEntityException $e) {
                return;
            }
            setcookie('geoip_store_code', $customerStoreCode, time() + (86400 * 365));
            setcookie('store', $customerStoreCode, time() + (86400 * 365));
            $params = $request->getParams();
            if (empty($params)) {
                $params['_query'] = ['___store' => $customerStoreCode];
            } else {
                $params['_query']['___store'] = $customerStoreCode;
            }
            $this->response->setRedirect($store->getUrl('', $params));

            return;
        } elseif ($request->getParam('___store', false)) {
            $requestStore = $request->getParam('___store', false);
            setcookie('geoip_store_code', $requestStore, time() + (86400 * 365));
            setcookie('store', $requestStore, time() + (86400 * 365));
        }
    }
}