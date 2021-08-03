<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFundedContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasColumns('funded_student_contact_details',['currently_in_australia','home_country_res_addr'])){
            Schema::table('funded_student_contact_details', function (Blueprint $table) {
                //
                $table->boolean('currently_in_australia')->after('state_id')->nullable();
                $table->string('home_country_res_addr')->after('currently_in_australia')->nullable();
    
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
        Schema::table('funded_student_contact_details', function (Blueprint $table) {
            $table->dropColumn(['currently_in_australia','home_country_res_addr']);
        });
    }
}
