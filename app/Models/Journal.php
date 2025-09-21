<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'posting_date' => 'date',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
