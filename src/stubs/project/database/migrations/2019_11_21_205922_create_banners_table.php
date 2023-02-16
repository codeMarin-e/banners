<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('site_id');
            $table->unsignedBigInteger('banner_position_id')->index();
            $table->boolean('period_type')->default(false);
            $table->timestamp('period_from', 0)->nullable();
            $table->timestamp('period_to', 0)->nullable();
            $table->unsignedBigInteger('clicks')->default(0);
            $table->integer('ord')->default(0);
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('banner_position_id')->references('id')->on('banner_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
