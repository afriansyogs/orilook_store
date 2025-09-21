<?php

namespace App\Console\Commands;
use App\Models\Order;
use Illuminate\Console\Command;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status terubah ke completed setelah 3 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::where('status', 'menunggu confirm user')
            ->where('updated_at', '<=', now()->subDays(3))
            ->update(['status' => 'completed']);

        $this->info("Order yang sudah 3 hari dalam status pending berhasil diperbarui menjadi completed.");
    }
}
