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
			$table->string('file', 80)->unique();
			$table->string('type', 15);
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
		Schema::dropIfExists('message_files');
	}
};
