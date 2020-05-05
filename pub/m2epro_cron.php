<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;

require dirname(__DIR__) . '/app/bootstrap.php';

if (php_sapi_name() === 'cli'){
    echo 'You cannot run this from the command line.';
    exit(1);
}

try {

    $params = $_SERVER;
    $params[StoreManager::PARAM_RUN_CODE] = 'admin';
    $params[Store::CUSTOM_ENTRY_POINT_PARAM] = true;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $params);

    /** @var \Ess\M2ePro\Model\Cron\Runner\Service\Pub\Application $app */
    $app = $bootstrap->createApplication(
        'Ess\M2ePro\Model\Cron\Runner\Service\Pub\Application', ['parameters' => []]
    );
    $bootstrap->run($app);

} catch (\Exception $e) {
    echo $e;
    exit(1);
}