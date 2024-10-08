<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function status(): BelongsTo
    {
        return $this->BelongsTo(ClientStatus::class);
    }
}
