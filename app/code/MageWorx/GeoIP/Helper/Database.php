<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\GeoIP\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;

/**
 * GeoIP DATABASE helper
 */
class Database extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Backend area code.
     *
     * @var string
     */
    const AREA_BACKEND = 'adminhtml';
    
    /**
     * XML config path database type
     */
    const XML_GEOIP_DATABASE_TYPE   = 'mageworx_geoip/geoip_database/database_type';
    
    /**
     * XML config path to database update info
     */
    const XML_GEOIP_UPDATE_DB       = 'mageworx_geoip/geoip_database/database_update';
    
    /**
     * URL database country update
     */
    const DB_COUNTRY_UPDATE_SOURCE  = 'https://download.maxmind.com/app/geoip_download';

    /**
     * URL database city update
     */
    const DB_CITY_UPDATE_SOURCE     = 'https://download.maxmind.com/app/geoip_download';

    /**
     * Path to the geoip lib
     */
    const DB_PATH = 'mageworx/geoip';

    /**
     * Suffix for archive backup file
     */
    const ARCHIVE_SUFFIX = '_backup_';

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Database constructor.
     *
     * @param Context $context
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Data $helperData
    ) {
        $this->helperData = $helperData;
        parent::__construct($context);
    }
    
    /**
     * Get DB Type to be used
     *
     * @return bool
     */
    public function isCityDbType()
    {
        $type = $this->_getRequest()->getParam('type', false);

        if ($type) {
            $currentType = $type;
        } else {
            $currentType = $this->scopeConfig->getValue(self::XML_GEOIP_DATABASE_TYPE);
        }

        $result = $currentType == \MageWorx\GeoIP\Model\Source\DbType::GEOIP_CITY_DATABASE;

        return $result;
    }
    
    /**
     * Get time of last DB update
     *
     * @return string
     */
    public function getLastUpdateTime()
    {
        $time = $this->scopeConfig->getValue(self::XML_GEOIP_UPDATE_DB);
        if (!$time) {
            return false;
        }
        return date('F d, Y / h:i', $time);
    }

    /**
     * Return source for database update
     *
     * @return string
     */
    public function getDbUpdateSource()
    {
        $baseUrl = $this->isCityDbType() ? self::DB_CITY_UPDATE_SOURCE : self::DB_COUNTRY_UPDATE_SOURCE;

        $urlParams = [
            'edition_id'  => $this->isCityDbType() ? 'GeoLite2-City' : 'GeoLite2-Country',
            'license_key' => $this->helperData->getLicenseKey(),
            'suffix'      => 'tar.gz',
        ];

        return $baseUrl . '?' . http_build_query($urlParams, '', '&');
    }
    
    /**
     * Return temp path of updating file
     *
     * @return string
     */
    public function getTempUpdateFile()
    {
        $dbPath = $this->getDatabasePath();
        return $dbPath . '_temp.tar.gz';
    }

    /**
     * Return full path to GeoIP database
     *
     * @return string
     */
    public function getDatabasePath()
    {
        $geoipLibPath = BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . self::DB_PATH;
        $cityPath = $geoipLibPath . DIRECTORY_SEPARATOR . 'GeoLite2-City.mmdb';
        $countryPath = $geoipLibPath . DIRECTORY_SEPARATOR . 'GeoLite2-Country.mmdb' ;
        
        return $this->isCityDbType() ? $cityPath : $countryPath ;
    }
}
