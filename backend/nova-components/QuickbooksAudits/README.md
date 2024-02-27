# Shifl Quickbooks Audits

Nova tool to identify mismatch Invoices and Bills

## Installations for Development

Step 1: Install Laravel and Laravel Nova with the following requirements and versions.

* "php": "^7.2"

* "laravel/framework": "^7.0"

* "laravel/nova": "^3.5"

Step 2: After Laravel and Nova installation, copy database/migrations content into the main app's database/migration folder then run "php artisan migrate".

Step 3: Copy the models below into your Laravel main app:
* Bill.php
* Invoice.php

Step 4: Create "QuickbooksAudits" folder into *nova-components* folder. Then paste this resource's files.

Step 5: Registering the QuickbooksAudits.
* Add below code to your Laravel main app *composer.json* repositories.
```
{
"type": "path",
"url": "./nova-components/QuickbooksAudits"
}
```
* then run "composer require shifl/quickbooks-audits" at your Laravel main app.
* then under the NovaServiceProvider.php file, import *use Shifl\QuickbooksAudits\QuickbooksAudits*, then register the QuickbooksAudits Tool  under tools method. Refer to the example below.

```
public function tools()
{
    if (\Illuminate\Support\Facades\Auth::user()->hasRole('Super Admin')) {
        return [
            new QuickbooksAudits,
        ];
    } else {
        return [
        new QuickbooksAudits,
        ];
    }
}
```

Note: Make sure you are connected to Quickbooks.


