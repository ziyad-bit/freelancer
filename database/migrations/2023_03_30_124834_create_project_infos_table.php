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
			$table->smallInteger('min_price', false, true);
			$table->smallInteger('max_price', false, true);
			$table->tinyInteger('num_of_days', false, true);
			$table->enum('exp', ['beginer', 'intermediate', 'experienced']);
			$table->string('file')->nullable();
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
