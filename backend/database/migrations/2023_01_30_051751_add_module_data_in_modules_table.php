<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleDataInModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            'Shipment' => [
                'All Shipment' => ['Pending', 'Shipments', 'Completed', 'Track Shipment', 'Details'],
                'Shipment Information' => ['Shipment Info', 'Purchase Orders', 'Documents', 'Bills', 'Notes', 'Milestones']
            ],
            'Drayage' => [
                'All Drayage' => ['Status', 'Download Report']
            ],
            'Purchase Order' => [
                'All Purchase Order' => ['All', 'Open', 'In Progress', 'Booking', 'In Transit', 'Delivered', 'Details'],
                'Purchase Order Information' => ['Products', 'History', 'Shipments'],
            ],
            'Sales Orders' => [
                'All Purchase Order' => ['All', 'Open', 'In Progress', 'Booking', 'In Transit', 'Delivered'],
                'Sales Order Information' => ['Product', 'History', 'Shipments']
            ],
            'Products' => [
                'All Product' => ['Archived', 'Import', 'Export', 'Manage Category'],
                'Product Information' => ['Warehouse breakdown', 'Projection Report']
            ],
            'Warehouse' => [
                'All Warehouse',
                'Warehouse Information' => ['Inventory', 'Inbound', 'Outbound', 'Inventory Report']
            ],
            'Contact' => [
                'All Contact' => ['Vendors', 'Buyers', 'Warehouse Customers']
            ],
            'ACH Statements' => [
                'All ACH Statements'
            ],
            'Reports' => ['Company Reports', 'Personalized Reports'],
            'Billing' => [
                'All Billing' => ['Unpaid', 'Paid', 'All Bills', 'Manage Payment Method', 'Payment History', 'Clear All Due']
            ],
            'Accounting' => [
                'Items',
                'Invoice',
                'Customers',
                'Bills',
                'Vendors',
                'Transactions',
                'Manual Journal Entry',
                'Chart of Accounts' => ['Assets', 'Liabilities', 'Expenses', 'Income', 'Equity', ],
                'Accounts',
                'Reports',
                'Reconciliations',
                'Settings' => ['QuickBooks', 'Company', 'Localisation', 'Invoice', 'Default', 'Categories', 'Currencies', 'Taxes']
            ],
            'Settings' => [
                'Company Profile' => ['Users', 'User Groups'],
                'Payment Methods' => ['Credit Cards', 'ACH Accounts']
            ]
        ];

        foreach ($data as $key => $value) {
            $moduleName = $key;
            $moduleDescription = "Ability to view, add, update and delete $key.";
            $constName1 = str_replace(' ', '_', strtoupper($key));
            $id = \DB::table('modules')->insertGetId(
                ['module_name' => $moduleName, 'module_description' => $moduleDescription, 'const_name' => $constName1, 'is_display_view' => 1, 'is_display_add' => 1, 'is_display_update' => 1, 'is_display_delete' => 1]
            );
            foreach ($value as $key1 => $value1) {
                $moduleName = is_array($value1) ? $key1 : $value1;
                $moduleDescription = "";
                $constName2 = $constName1 . '_' .str_replace(' ', '_', strtoupper($moduleName));

                $idSub = \DB::table('modules')->insertGetId(
                    ['parent_id' => $id, 'module_name' => $moduleName, 'module_description' => $moduleDescription, 'const_name' => $constName2, 'is_display_view' => 1, 'is_display_add' => 1, 'is_display_update' => 1, 'is_display_delete' => 1]
                );

                if(is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        $moduleName = is_array($value2) ? $key2 : $value2;
                        $moduleDescription = "";
                        $constName3 = $constName2 . '_' . str_replace(' ', '_', strtoupper($moduleName));
                        $idSubSub = \DB::table('modules')->insertGetId(
                            ['parent_id' => $idSub,'module_name' => $moduleName, 'module_description' => $moduleDescription, 'const_name' => $constName3, 'is_display_view' => 1, 'is_display_add' => 1, 'is_display_update' => 1, 'is_display_delete' => 1]
                        );
                    }
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            //
        });
    }
}
