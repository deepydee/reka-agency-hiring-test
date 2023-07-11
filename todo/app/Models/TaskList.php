<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TaskList extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('list-image')
            ->useFallbackUrl(asset('storage/img/placeholder-image.png'), 'thumb')
            ->useFallbackPath(asset('storage/img/placeholder-image.png'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 350, 350)
            ->nonQueued();
    }
}
