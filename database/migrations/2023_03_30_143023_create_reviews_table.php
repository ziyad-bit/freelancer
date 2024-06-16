<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews', function (Blueprint $table) {
			$table->id();
			$table->tinyInteger('rate', false, true);
			$table->foreignId('giver_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('reviews');
	}
};
