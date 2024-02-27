<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderToOauthClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

//
//        Schema::table('table-name', function(Blueprint $table){
//            $table->renameColumn('old-name', 'new-name');
//        });


        Schema::table('oauth_clients', function (Blueprint $table) {
            if(!Schema::hasColumn('oauth_clients', 'provider')) ;
            {
                $table->string('provider')->after('secret')->nullable()->change();
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        if (Schema::hasColumn('oauth_clients', 'provider'))
        {
            Schema::table('oauth_clients', function (Blueprint $table)
            {
                $table->dropColumn('provider');
            });
        }
//
//
//        Schema::table('oauth_clients', function (Blueprint $table) {
//            $table->dropColumn('provider');
//        });
    }
}
