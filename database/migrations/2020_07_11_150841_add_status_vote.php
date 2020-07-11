<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusVote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote', function (Blueprint $table) {
            $table->dropColumn('up');
            $table->dropColumn('down');
            $table->integer('status')->after('point');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vote', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
