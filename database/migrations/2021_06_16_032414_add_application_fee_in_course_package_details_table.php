<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicationFeeInCoursePackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('course_package_details', 'application_fee')){
            Schema::table('course_package_details', function (Blueprint $table) {
                $table->decimal('application_fee', 11,2)->nullable();
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
        if(Schema::hasColumn('course_package_details', 'application_fee')){
            Schema::table('course_package_details', function (Blueprint $table) {
                $table->dropColumn(['application_fee']);
            });
        }
    }
}
