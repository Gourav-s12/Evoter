<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('election_id');

            $table->foreign('election_id')
                    ->references('id')
                    ->on('elections')
                    ->onDelete('cascade');
            
            $table->unsignedBigInteger('nominee_id');

            $table->foreign('nominee_id')
                    ->references('id')
                    ->on('nominees')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voters');
    }
}
