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
		Schema::create('messages', function (Blueprint $table) {
			$table->id();
			$table->string('text', 250);
			$table->string('file')->nullable();
			$table->tinyInteger('last')->default(1);
			$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('receiver_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('messages');
	}
};
