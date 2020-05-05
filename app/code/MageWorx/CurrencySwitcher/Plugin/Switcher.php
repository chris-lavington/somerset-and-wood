<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\CurrencySwitcher\Plugin;

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
     * @var \MageWorx\CurrencySwitcher\Model\Switcher
     */
    private $modelSwitcher;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageWorx\GeoIP\Helper\Country
     */
    protected $geoIpHelperCountry;

    /**
     * @var \MageWorx\GeoIP\Model\Geoip
     */
    protected $geoIpModelGeoip;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * Switcher constructor.
     *
     * @param \MageWorx\GeoIP\Helper\Customer $geoIpHelperCustomer
     * @param \MageWorx\GeoIP\Helper\Country $geoIpHelperCountry
     * @param \MageWorx\GeoIP\Model\Geoip $geoIpModelGeoip
     * @param \MageWorx\StoreSwitcher\Model\Switcher $modelSwitcher
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \MageWorx\GeoIP\Helper\Customer $geoIpHelperCustomer,
        \MageWorx\GeoIP\Helper\Country $geoIpHelperCountry,
        \MageWorx\GeoIP\Model\Geoip $geoIpModelGeoip,
        \MageWorx\CurrencySwitcher\Model\Switcher $modelSwitcher,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->geoIpHelperCustomer = $geoIpHelperCustomer;
        $this->geoIpHelperCountry  = $geoIpHelperCountry;
        $this->geoIpModelGeoip     = $geoIpModelGeoip;
        $this->modelSwitcher       = $modelSwitcher;
        $this->storeManager        = $storeManager;
        $this->checkoutSession     = $checkoutSession;
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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {

        if (!$this->modelSwitcher->isAllowed()) {
            return;
        }

        $currencyCookie = $this->geoIpHelperCustomer->getCookie('currency_code');
        $mageStore      = $this->storeManager->getStore();
        $geoipCountry   = $request->getParam('geoip_country');
        $currency       = null;

        if ($mageStore->getCurrentCurrencyCode() == $currencyCookie && !$geoipCountry) {
            return;
        }

        if ($this->geoIpHelperCountry->checkCountryCode($geoipCountry)) {
            $currency = $this->modelSwitcher->getCurrency($geoipCountry);
        } elseif ($currencyCookie) {
            $currency = $currencyCookie;
        } else {
            $geoip    = $this->geoIpModelGeoip->getCurrentLocation();
            $currency = $this->modelSwitcher->getCurrency($geoip->getCode());
        }
        if ($currency && ($mageStore->getCurrentCurrencyCode() != $currency)) {
            $mageStore->setCurrentCurrencyCode($currency);

            if ($this->checkoutSession->hasQuote()) {
                $this->checkoutSession->getQuote()
                                      ->collectTotals()
                                      ->save();
            }
        } else {
            $this->geoIpHelperCustomer->setCookie('currency_code', $mageStore->getCurrentCurrencyCode());
        }

        return;
    }
}