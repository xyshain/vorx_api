<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShoreTypeInFundedStudentCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funded_student_course', function (Blueprint $table) {
            //
        });
        if(!Schema::hasColumn('funded_student_course', 'shore_type')){
            Schema::table('funded_student_course', function (Blueprint $table) {
                $table->enum('shore_type', ['ONSHORE', 'OFFSHORE'])->defult('ONSHORE')->after('course_fee_type');
                $table->integer('class_id')->nullable()->after('agent_id');
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
        if(Schema::hasColumn('funded_student_course', 'shore_type')){
            Schema::table('funded_student_course', function (Blueprint $table) {
                $table->dropColumn(['shore_type', 'class_id']);
            });
        }
    }
}
