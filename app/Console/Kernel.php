<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the application's command schedule.
	 *
	 * @param \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('backup:clean')
				->weekly()
				->at('01:00')
				->onFailure(function () {
					Log::critical('clean Backup failed');
				})
				->onSuccess(function () {
					Log::info('clean Backup succeeded');
				});

		$schedule->command('backup:run')
				->daily()
				->at('01:30')
				->onFailure(function () {
					Log::critical('Backup failed');
				})
				->onSuccess(function () {
					Log::info('Backup succeeded');
				});
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');

		include base_path('routes/console.php');
	}
}
