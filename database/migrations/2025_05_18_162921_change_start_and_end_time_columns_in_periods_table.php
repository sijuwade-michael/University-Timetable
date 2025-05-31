<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->time('start_time')->change();
            $table->time('end_time')->change();
        });
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('periods', function (Blueprint $table) {
        $table->timestamp('start_time')->change();
        $table->timestamp('end_time')->change();
    });
}
};
