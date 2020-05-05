<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreSwitcher\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Store Switcher Country helper
 */
class Country extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Country constructor.
     *
     * @param Context $context
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Convert country code to upper case
     *
     * @param string $countryCode
     * @return string
     */
    public function prepareCode($countryCode)
    {
        return strtoupper(trim($countryCode));
    }

    /**
     * Convert country codes from sql database to simple array
     *
     * @param string|array $countryCode
     * @return string|array
     */
    public function prepareCountryCode($countryCode)
    {
        if (!$countryCode) {
            return '';
        }

        if (is_array($countryCode)) {
            $countryCode = current($countryCode);
        }

        $data = $this->serializer->unserialize($countryCode);
        if (!$data) {
            return $countryCode;
        }

        return $data;
    }
}
