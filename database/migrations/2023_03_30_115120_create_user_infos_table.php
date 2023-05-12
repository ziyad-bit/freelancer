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
		Schema::create('user_infos', function (Blueprint $table) {
			$table->id();
			$table->string('location', 100);
			$table->decimal('review', 2, 1, true)->default(0);
			$table->string('job', 50);
			$table->text('overview');
			$table->bigInteger('card_num', false, true);
			$table->enum('type', ['client', 'freelancer']);
			$table->enum('online', ['online', 'offline'])->default('offline');
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_infos');
	}
};
