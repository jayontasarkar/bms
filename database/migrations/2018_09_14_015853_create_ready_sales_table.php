<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadySalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ready_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('memo')->unique();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->text('ready_sale_details')->nullable();
            $table->float('total_discount')->nullable()->default(0);
            $table->timestamp('sales_date')->nullable();
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
        Schema::dropIfExists('ready_sales');
    }
}
