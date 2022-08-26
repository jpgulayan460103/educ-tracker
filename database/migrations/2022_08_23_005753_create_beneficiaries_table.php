<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('ext_name')->nullable();
            $table->unsignedBigInteger('school_level_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->unsignedBigInteger('composition_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('payout_id')->nullable();
            $table->unsignedBigInteger('swad_office_id')->nullable();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->string('school_name')->nullable();
            $table->string('sector_others')->nullable();
            $table->float('amount_granted', 15, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->string('uuid')->nullable();
            $table->timestamps();
            $table->fulltext('full_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
}
