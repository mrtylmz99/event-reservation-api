use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:expire')->everyMinute();
