<?php
namespace BSS\Deliverydate\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\DB\Ddl\Table;

class AddDeliveryDateAndPhone implements SchemaPatchInterface
{
    private $moduleDataSetup;

    public function __construct(\Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $tableName = $this->moduleDataSetup->getTable('sales_order_address');

        if ($this->moduleDataSetup->getConnection()->isTableExists($tableName)) {
            // Add 'delivery_date' column
            $this->moduleDataSetup->getConnection()->addColumn(
                $tableName,
                'delivery_date',
                [
                    'type' => Table::TYPE_DATE,
                    'nullable' => true,
                    'comment' => 'Delivery Date',
                ]
            );

            // Add 'additional_phone_number' column
            $this->moduleDataSetup->getConnection()->addColumn(
                $tableName,
                'additional_phone_number',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 15,
                    'comment' => 'Additional Phone Number',
                ]
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}