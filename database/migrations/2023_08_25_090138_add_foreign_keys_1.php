<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->foreign('school_level_id')->references('id')->on('school_levels')->onDelete('cascade');;
            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');;
            $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('cascade');;
            $table->foreign('swad_office_id')->references('id')->on('swad_offices')->onDelete('cascade');;
            $table->index(['last_name', 'first_name', 'middle_name'], 'beneficiary_query_index');
        });
        Schema::table('bio_parents', function (Blueprint $table) {
            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');;
            $table->index(['last_name', 'first_name', 'middle_name'], 'bio_parent_query_index');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');;
            $table->foreign('client_sector_id')->references('id')->on('client_sectors')->onDelete('cascade');;
            $table->foreign('sector_other_id')->references('id')->on('sector_others')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('psgc_id')->references('id')->on('psgcs')->onDelete('cascade');;
            $table->foreign('swad_office_id')->references('id')->on('swad_offices')->onDelete('cascade');;
            $table->index(['last_name', 'first_name', 'middle_name'], 'client_query_index');
        });
        Schema::table('compositions', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('cascade');;
            $table->foreign('swad_office_id')->references('id')->on('swad_offices')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('school_level_id')->references('id')->on('school_levels')->onDelete('cascade');
        });
        Schema::table('psgcs', function (Blueprint $table) {
            $table->foreign('swad_office_id')->references('id')->on('swad_offices')->onDelete('cascade');;
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('swad_office_id')->references('id')->on('swad_offices')->onDelete('cascade');;
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropForeign(['school_level_id']);
            $table->dropForeign(['composition_id']);
            $table->dropForeign(['payout_id']);
            $table->dropForeign(['swad_office_id']);
        });
        Schema::table('bio_parents', function (Blueprint $table) {
            $table->dropForeign(['composition_id']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropForeign(['sector_other_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['psgc_id']);
            $table->dropForeign(['client_sector_id']);
            $table->dropForeign(['swad_office_id']);
        });
        Schema::table('compositions', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->dropForeign(['payout_id']);
            $table->dropForeign(['swad_office_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['school_level_id']);
        });
        Schema::table('psgcs', function (Blueprint $table) {
            $table->dropForeign(['swad_office_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['swad_office_id']);
            $table->dropForeign(['office_id']);
        });
    }
}
