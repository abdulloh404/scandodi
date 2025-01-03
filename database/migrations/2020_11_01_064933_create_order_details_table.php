<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('item_id');
            $table->double('price')->default(0);
            $table->double('quantity')->default(0);
            $table->double('total')->default(0);
            $table->enum('status',['pending','approved','ready_for_delivery','delivered','rejected'])->default('pending');
            $table->double('discount')->default(0)->comment('calculated in amount');
            $table->double('tax_amount')->default(0)->comment('calculated total tax');
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
        Schema::dropIfExists('order_details');
    }
}
