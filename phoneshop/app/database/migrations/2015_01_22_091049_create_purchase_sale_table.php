<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseSaleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase-sale', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('transactions_id');
			$table->integer('articles_id');
			$table->float('tax');
			$table->float('qty');
			$table->float('price');
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
		Schema::drop('purchase-sale');
	}

}
