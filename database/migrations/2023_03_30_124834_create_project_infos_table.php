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
		Schema::create('project_infos', function (Blueprint $table) {
			$table->id();
			$table->smallInteger('min_price', false, true);
			$table->smallInteger('max_price', false, true);
			$table->tinyInteger('num_of_days', false, true);
			$table->enum('active', ['active', 'inactive'])->default('inactive');
			$table->enum('exp', ['beginner', 'intermediate', 'experienced']);
			$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('project_infos');
	}
};
