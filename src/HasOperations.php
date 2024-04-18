<?php

namespace Panoscape\History;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasOperations
{
    /**
     * Get all the agent's operations.
     */
    public function operations():MorphMany
    {
        return $this->morphMany(History::class, 'user');
    }
}
