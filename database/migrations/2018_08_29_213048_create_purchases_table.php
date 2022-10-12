<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('memo')->unique();
            $table->unsignedInteger('vendor_id');
            $table->float('total_balance', 14, 2)->default(0);
            $table->boolean('type')->nullable()->default(false);
            $table->float('total_paid', 14, 2)->nullable()->default(0);
            $table->float('total_discount', 14, 2)->nullable()->default(0);
            $table->timestamp('purchase_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
