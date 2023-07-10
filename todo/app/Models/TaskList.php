<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'owner_id',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getOwnerName(): string
    {
        return User::findOrFail($this->owner_id)->name;
    }

    public function getUsersNamesListBelongsTo(): string
    {
        return $this->users()
            ->where('id', '!=', auth()->id())
            ->pluck('name')
            ->join(', ');
    }
}
