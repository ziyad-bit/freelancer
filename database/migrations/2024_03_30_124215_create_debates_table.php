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
		Schema::create('debates', function (Blueprint $table) {
			$table->id();
			$table->text('description');
			$table->enum('status', ['finished', 'unfinished'])->default('unfinished');
			$table->uuid('transaction_id');
			$table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('initiator_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('opponent_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('debates');
	}
};
