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
		Schema::create('message_files', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('file', 80)->nullable();
			$table->string('video', 80)->nullable();
			$table->string('image', 80)->nullable();
			$table->foreignId('message_id')->constrained('messages')->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('project_files');
	}
};
