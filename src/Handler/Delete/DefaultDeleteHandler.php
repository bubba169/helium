<?php

namespace Helium\Handler\Delete;

use Illuminate\Database\Eloquent\Model;

class DefaultDeleteHandler
{
    public function __invoke(Model $entry, array $cascade)
    {
        $this->deleteRelated($entry, $cascade);
        $entry->delete();
    }

    protected function deleteRelated(Model $entry, array $cascade): void
    {
        foreach ($cascade as $key => $value) {
            // Could be either nested array with relationship as key or
            // int key with relationship as string value
            $relationship = is_array($value) ? $key : $value;
            $cascade = is_array($value) ? $value : [];
            $toDelete = $entry->{$relationship}()->get();

            // If there are nested relationships to remove deal with
            // those first
            if (!empty($cascade)) {
                foreach ($toDelete as $deleteEntry) {
                    $this->deleteRelated($deleteEntry, $cascade);
                }
            }

            // Remove all related records
            $entry->{$relationship}()->delete();
        }
    }
}
