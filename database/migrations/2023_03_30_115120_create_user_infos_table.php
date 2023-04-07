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
			$table->string('location', 20);
			$table->decimal('review', 2, 1, true);
			$table->string('job', 30);
			$table->text('overview');
			$table->enum('payment_method', ['verified', 'unverified'])->default('unverified');
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
