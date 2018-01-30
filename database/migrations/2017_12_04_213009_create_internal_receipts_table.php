<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('internalreceipt')->create('internal_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('serial_no');
            $table->string('creditor_name');
            $table->string('creditor_address_1');
            $table->string('creditor_address_2');
            $table->string('creditor_place');
            $table->string('expenditure_type');
            $table->date('expenditure_date');
            $table->unsignedDecimal('expenditure_costs');
            $table->string('reason');
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
        Schema::connection('internalreceipt')->dropIfExists('internal_receipts');
    }
}
