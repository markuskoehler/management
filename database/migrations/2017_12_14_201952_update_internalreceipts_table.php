<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInternalreceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('internalreceipt')->table('internal_receipts', function (Blueprint $table) {
            $table->dropColumn(['creditor_name', 'creditor_address_1', 'creditor_address_2', 'creditor_place']);
            $table->unsignedInteger('billomat_supplier_id')->after('serial_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('internalreceipt')->table('internal_receipts', function (Blueprint $table) {
            $table->string('creditor_name')->after('serial_no');
            $table->string('creditor_address_1')->after('creditor_name');
            $table->string('creditor_address_2')->after('creditor_address_1');
            $table->string('creditor_place')->after('creditor_address_2');
            $table->dropColumn('billomat_supplier_id');
        });
    }
}
