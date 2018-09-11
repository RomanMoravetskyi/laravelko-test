<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_basket', function (Blueprint $table) {
            $table->increments('id', true);
            $table->integer('productId')->unsigned();
            $table->integer('basketId')->unsigned();
            $table->integer('productQuantity')->default(0);

            $table->foreign('productId')->references('id')->on('products');
            $table->foreign('basketId')->references('id')->on('basket')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_baskets', function (Blueprint $table) {
            $table->dropForeign('role_user_role_id_foreign');
            $table->dropForeign('role_user_user_id_foreign');
            $table->dropPrimary();
        });

        Schema::dropIfExists('product_baskets');
    }
}
