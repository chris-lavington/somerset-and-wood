<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreSwitcher\Setup;

use \Magento\Framework\DB\DataConverter\SerializedToJson;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var \Magento\Framework\DB\FieldDataConverterFactory
     */
    protected $fieldDataConverterFactory;

    /**
     * @var \Magento\Framework\DB\Query\Generator
     */
    protected $queryGenerator;

    /**
     * @var \Magento\Framework\DB\Select\QueryModifierFactory
     */
    protected $queryModifierFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * UpgradeData constructor.
     *
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Framework\DB\FieldDataConverterFactory $fieldDataConverterFactory
     * @param \Magento\Framework\DB\Query\Generator $queryGenerator
     * @param \Magento\Framework\DB\Select\QueryModifierFactory $queryModifierFactory
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\DB\FieldDataConverterFactory $fieldDataConverterFactory,
        \Magento\Framework\DB\Query\Generator $queryGenerator,
        \Magento\Framework\DB\Select\QueryModifierFactory $queryModifierFactory
    ) {
        $this->moduleManager             = $moduleManager;
        $this->fieldDataConverterFactory = $fieldDataConverterFactory;
        $this->queryGenerator            = $queryGenerator;
        $this->queryModifierFactory      = $queryModifierFactory;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), '2.1.0', '<')) {
            $this->convertSerializedDataToJson($setup);
        }
    }

    /**
     * Upgrade to version 1.0.2, convert datafrom serialized to JSON format
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @return void
     */
    protected function convertSerializedDataToJson(\Magento\Framework\Setup\ModuleDataSetupInterface $setup)
    {
        if (!$this->moduleManager->isEnabled('Magento_Store')) {
            return;
        }

        $fieldDataConverter = $this->fieldDataConverterFactory->create(SerializedToJsonDataConverter::class);

        $fieldDataConverter->convert(
            $setup->getConnection(),
            $setup->getTable('store'),
            'store_id',
            'geoip_country_code'
        );
    }
}
