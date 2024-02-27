<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                AgentSeeder::class,
                CategoriesSeeder::class,
                ShiflOfficeSeeder::class,

                SuperAdminTableSeeder::class,
                UsersTableSeeder::class,
                SuppliersTableSeeder::class,
                CustomersTableSeeder::class,
                ItemSeeder::class,

                BillSeeder::class,
                CarrierSeeder::class,
                CardSeeder::class,
                BuyerSeeder::class,
                ColoaderSeeder::class,
                CountrySeeder::class,
                StateSeeder::class,
                CitySeeder::class,

                DiscrepancyLogSeeder::class,

                EtaLogSeeder::class,
                EmailReportScheduleSeeder::class,
                MailBoxInboundEmailSeeder::class,

                IncotermSeeder::class,
                ShipmentSeeder::class,
                InvoiceSeeder::class,
                TokenSeeder::class,
                DocumentSeeder::class,
                PaymentMethodSeeder::class,
                PurchaseOrderSeeder::class,

                QuickbookSeeder::class,
                ServiceSeeder::class,
                ContainerSeeder::class,
                StatementSeeder::class,
                TerminalSeeder::class,
                VendorSeeder::class,
                WaitingListSeeder::class,
                WebHookCallSeeder::class,

                CruxContainerSeeder::class,



                TemporarySeeder::class,
                TruckerSeeder::class,

                NoteSeeder::class,
                //
    //              OtpSeeder::class,
               //
             //   CustomerAdminSeeder::class,
               // CustomersTableSeeder::class,
                CustomSeeder::class,
                RolesPermissionsTableSeeder::class,
                IndexRatesSeeder::class,
                NewRolesPermissionsTableSeeder::class,
                ModelHasRoleSeeder::class,
                WarehouseSeeder::class
            ]
        );
    	//$this->call(RolesPermissionsTableSeeder::class);
        //$this->call(SuperAdminTableSeeder::class);
         //   $this->call(IndexRates::class);
        // $this->call(NewRolesPermissionsTableSeeder::class);
    }
}
