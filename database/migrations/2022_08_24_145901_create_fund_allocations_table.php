<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payout_id')->nullable();
            $table->unsignedBigInteger('swad_office_id')->nullable();
            $table->float('allocated_amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_allocations');
    }
}
