<?php

namespace App\Models;

use Statamic\Entries\Entry;
use Illuminate\Database\Eloquent\Model;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Exceptions\EntryNotFoundException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use StatamicRadPack\Runway\Traits\HasRunwayResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentReport extends Model
{
    use HasFactory, HasRunwayResource;

    protected $fillable = [
        'entry_id',
        'reason',
        'comments',
        'email'
    ];

    protected $casts = [
        'complete' => 'boolean'
    ];

    protected function getEntry(): Entry
    {
        $entry = EntryFacade::find($this->entry_id);

        if(!$entry) {
            throw new EntryNotFoundException($entry);
        }

        return $entry;
    }

    public function entry(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->getEntry();
            }
        );
    }

    public function scopeRunwayListing($query)
    {
        return $query->where('complete', false);
    }
}
