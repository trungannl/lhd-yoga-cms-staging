<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Upload extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    protected $table = 'uploads';

    public $fillable = [
        'uuid'
    ];

    private $performed = 'default';

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_FILL, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_FILL, 100, 100)
            ->sharpen(10);
    }

    // TODO
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        if($url){
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            if (in_array($extension, ['jpg', 'png', 'gif', 'bmp', 'jpeg'])) {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            } else {
                return asset('images/icons/' . $extension . '.png');
            }
        }else{
            return '';
        }
    }

    /**
     * @return string
     */
    public function getPerformed(): string
    {
        return $this->performed;
    }

    /**
     * @param string $performed
     */
    public function setPerformed(string $performed): void
    {
        $this->performed = $performed;
    }
}
