<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductVariationsTable extends Migration
{
    public function up()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7769771')->references('id')->on('products');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->foreign('variation_id', 'variation_fk_7769786')->references('id')->on('variations');
        });
    }
}
