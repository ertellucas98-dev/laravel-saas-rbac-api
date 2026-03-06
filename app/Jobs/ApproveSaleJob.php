<?php

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApproveSaleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Sale $sale,
        public int $approvedByUserId,
    ) {
    }

    public function handle(): void
    {
        $this->sale->forceFill([
            'approved_at' => now(),
        ])->save();

        Log::info('Sale approved', [
            'sale_id' => $this->sale->id,
            'approved_by' => $this->approvedByUserId,
        ]);
    }
}
