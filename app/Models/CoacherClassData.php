<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CoacherClassData extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    protected $table = 'coacher_class_data';

    protected $fillable = [
        'studio_id',
        'user_id',
        'description',
        'date',
    ];

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        if ($url) {
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            if (in_array($extension, config('media-library.extensions_has_thumb'))) {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            }
        }
        return '';
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('data-image') ? true : false;
    }

    public function getImages()
    {
        $images = [];
        foreach ($this->getMedia('data-image') as $media) {
            $images[] = $media->getFullUrl();
        }
        return $images;
    }
}
