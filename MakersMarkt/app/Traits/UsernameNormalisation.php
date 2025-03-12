<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsernameNormalization
{
    /**
     * Set the username attribute with normalization.
     *
     * @param string $value
     * @return void
     */
    public function setUsernameAttribute($value)
    {
        // Convert username to lowercase for consistent storage
        // This prevents duplicate usernames with different casing
        $this->attributes['username'] = Str::lower($value);
    }

    /**
     * Get the original (non-lowercase) username for display purposes.
     *
     * @param string $value
     * @return string
     */
    public function getDisplayUsernameAttribute()
    {
        return $this->username;
    }
}
