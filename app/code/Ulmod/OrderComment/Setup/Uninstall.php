<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\OrderComment\Setup;

use Ulmod\OrderComment\Model\Data\OrderComment;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->dropColumn(
            $setup->getTable('quote'),
            OrderComment::COMMENT_FIELD_NAME
        );

        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order'),
            OrderComment::COMMENT_FIELD_NAME
        );

        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order_grid'),
            OrderComment::COMMENT_FIELD_NAME
        );

        $setup->endSetup();
    }
}
