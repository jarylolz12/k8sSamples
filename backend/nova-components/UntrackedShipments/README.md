## About untracked-shipments

### Install Laravel and Laravel Nova with the following requirements and versions. You can have PHP version 7.4 though.
#### "php": "^7.2"
#### "laravel/framework": "^7.0"
#### "laravel/nova": "^3.5"

<!-- ### After Laravel and Nova installation. Copy database/migrations content into the main app's database/migration folder then run "php artisan migrate". -->

<!-- ### Copy Bill.php, Invoice.php, Shipment.php files into main app's app/ folder. -->

<!-- ### You can create your own Nova resources files based on the migration files. -->

### Under main app nova-components create a folder UntrackedShipments then copy all content of this repository into it.

<!-- ### Copy routes/web.php content to routes/web.php of the main app in any middleware and group. -->

<!-- ### Copy src/Http/Controllers/ProfitMonitorController.php to app/Http/Controllers of the main app. -->

### Add the below code to the main app's composer.json
#### {
####     "type": "path",
####     "url": "./nova-components/UntrackedShipments"
#### }

### run composer require tanvirofficials/untracked-shipments at the root of the main app.

### In the main app/Providers/NovaServiceProvider.php . Add/Update the following lines respectively.
###
#### use Tanvirofficials\UntrackedShipments\UntrackedShipments;
###
#### public function tools()
#### {
####     if (\Illuminate\Support\Facades\Auth::user()->hasRole('Super Admin')) {
####         return [
####              new UntrackedShipments,
####         ];
####     } else {
####         return [
####             new UntrackedShipments,
####         ];
####     }
#### }

### In the main app package.json add the following codes.
#### "build-untracked-shipments": "cd nova-components/UntrackedShipments && npm run dev",
#### "build-untracked-shipments-prod": "cd nova-components/UntrackedShipments && npm run prod"

### Please commit back only the files for the module and not the whole Laravel+Nova main system files. If you will create additional files like Controllers. Please copy them over to the same folders indicated in the instructions in reverse.
