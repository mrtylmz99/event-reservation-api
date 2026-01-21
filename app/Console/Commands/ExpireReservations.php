<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending reservations that have passed their expiry time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        $count = $expiredReservations->count();
        if ($count > 0) {
            foreach ($expiredReservations as $reservation) {
                $reservation->status = 'expired'; // İptal edildi olarak işaretle
                $reservation->save();
                // Opsiyonel: Koltukları serbest bırak (zaten reservation statusu ile kontrol ediliyor)
            }
            $this->info("Expired $count reservations.");
        } else {
            $this->info("No expired reservations found.");
        }
    }
}
