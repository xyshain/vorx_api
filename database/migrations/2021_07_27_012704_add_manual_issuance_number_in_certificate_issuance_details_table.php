<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualIssuanceNumberInCertificateIssuanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('certificate_issuance_details', 'manual_issuance_number')){
            Schema::table('certificate_issuance_details', function (Blueprint $table) {
                $table->string('manual_issuance_number', 100)->nullable()->after('soa_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('certificate_issuance_details', 'manual_issuance_number')){
            Schema::table('certificate_issuance_details', function (Blueprint $table) {
                $table->dropColumn(['manual_issuance_number']);
            });
        }
    }
}
