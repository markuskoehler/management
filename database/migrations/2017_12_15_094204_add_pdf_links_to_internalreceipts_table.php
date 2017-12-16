<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPdfLinksToInternalreceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('internalreceipt')->table('internal_receipts', function (Blueprint $table) {
            $table->string('unsigned_document')->nullable()->after('reason');
            $table->string('signed_document')->nullable()->after('unsigned_document');
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
            $table->dropColumn(['unsigned_document', 'signed_document']);
        });
    }
}
