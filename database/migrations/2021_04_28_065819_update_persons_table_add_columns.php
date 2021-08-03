<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePersonsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasColumns('persons',['singleName','first_name','family_name','middle_name'])){
            Schema::table('persons', function (Blueprint $table) {
                $table->string('singleName')->after('lastname')->nullable();
                $table->string('first_name')->after('singleName')->nullable();
                $table->string('family_name')->after('first_name')->nullable();
                $table->string('middle_name')->after('family_name')->nullable();
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
        Schema::table('persons', function (Blueprint $table) {
            //
            $table->dropColumn(['singleName','first_name','family_name','middle_name']);
        });
    }
}
