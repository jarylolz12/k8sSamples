<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'company_name' => $faker->text(30),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'admin_user_id'  => rand(1, 4),
        'managers' => $faker->safeEmail,
        'sale_persons'=>'test@gmail.com',
        //       'sale_persons'  => $faker->randomElement(['Ran', 'Sales', 'rep']),
        //        'managers' => $faker->name,
        //        'sale_persons' => rand(1, 110),
        //        'booking_email_recipients' => '["ari@bestataglance.com", "leah@bestataglance.com", "desiree@zapimports.com"]',
        //        'loi' => 'customers/81179f359120099142e62e20adbd0f48.pdf',
        //        'offices_managers' => '[{"office_id": 1,"manager_id": 119},{"office_id": 2,"manager_id": 41},{"office_id": 3,"manager_id": 120},{"office_id": 4,"manager_id": 150 },{"office_id": 5, "manager_id": 40}]',
        //        'qbo_customer'=> '{"customer":{"Id":"25","DisplayName":"Yoki Shoes","FullyQualifiedName":"Yoki Shoes","Balance":"64980.00","PrimaryEmailAddr":{"Id":null,"Address":"devi@yokigroup.com","Default":null,"Tag":null},"GivenName":null,"FamilyName":null,"BillAddr":null},"company":"shifl Inc","realm_id":123146157027444}'
    ];
});
