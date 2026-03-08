<?php

namespace App\Observers;

use App\Models\Specification;

class SpecificationObserver
{
    /**
     * Handle the Specification "saved" event.
     * This covers both 'created' and 'updated' automatically.
     */
    public function saved(Specification $specification): void
    {
        // Access the parent variant and trigger the sync logic
        if ($specification->variant) {
            $specification->variant->syncNameFromSpec();
        }
    }

    /**
     * Handle the Specification "deleted" event.
     */
    public function deleted(Specification $specification): void
    {
        if ($specification->variant) {
            $specification->variant->syncNameFromSpec();
        }
    }

    /**
     * Handle the Specification "restored" event (if using SoftDeletes).
     */
    public function restored(Specification $specification): void
    {
        if ($specification->variant) {
            $specification->variant->syncNameFromSpec();
        }
    }
}