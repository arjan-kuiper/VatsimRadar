<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePositionstatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positionstats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cycles')->default(0);
            $table->timestamps();
        });

        // Insert the zero record by default
        DB::table('positionstats')->insert(
            array(
                'id' => 1,
                'cycles' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positionstats');
    }
}
