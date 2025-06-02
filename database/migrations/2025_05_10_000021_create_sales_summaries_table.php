<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesSummariesTable extends Migration
{
    public function up()
    {
        Schema::create('sales_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->date('sale_date');
            $table->decimal('total_sales', 15, 2);
            $table->decimal('total_tax', 15, 2);
            $table->integer('total_quantity');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_summaries');
    }
}