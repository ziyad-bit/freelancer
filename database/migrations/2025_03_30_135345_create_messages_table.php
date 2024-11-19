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
			$table->text('text');
			$table->tinyInteger('last')->default(1);
			$table->uuid('chat_room_id');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('messages');
	}
};
