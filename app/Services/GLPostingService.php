<?php
namespace app\Services;

use App\Models\{arbatch, GLBatch, GLEntry, AREntry};
use Illuminate\Support\Facades\DB;

class GLPostingService
{
    public function postArBatch(arbatch $arBatch, $userId)
    {
        return DB::transaction(function () use ($arBatch, $userId) {
            $glRef = 'GLB-' . now()->format('YmdHis') . '-' . $arBatch->id;
            $glBatch = glbatch::create([
                'reference' => $glRef,
                'source_type' => $arBatch->source_type,
                'source_id' => $arBatch->source_id,
                'status' => 'posted', // we'll mark posted at creation if immediate
                'created_by' => $userId,
                'posted_at' => now(),
            ]);

            // For each AR entry create matching GL entries.
            // A debit AR entry becomes a debit GL entry; credit AR becomes credit GL
            foreach ($arBatch->entries as $arEntry) {
                $debit = $arEntry->entry_type === 'debit' ? $arEntry->amount : 0;
                $credit = $arEntry->entry_type === 'credit' ? $arEntry->amount : 0;

                glentry::create([
                    'batch_id' => $glBatch->id,
                    'account_id' => $arEntry->gl_account_id,
                    'debit' => $debit,
                    'credit' => $credit,
                    'description' => $arEntry->description,
                ]);
            }

            return $glBatch;
        });
    }
}
