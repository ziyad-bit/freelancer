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
		Schema::create('offers', function (Blueprint $table) {
			$table->id();
			$table->text('content');
			$table->smallInteger('price', false, true);
			$table->enum('approval', ['approved', 'refused']);
			$table->enum('finished', ['finished', 'unfinished'])->default('unfinished');
			$table->tinyInteger('num_of_days', false, true);
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('offers');
	}
};