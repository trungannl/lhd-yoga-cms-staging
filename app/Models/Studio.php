<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studio extends Model implements HasMedia
{
    const CLOSE = 0;
    const OPEN  = 1;
    const DEFAULT_SCHEDULE = [
        'mon' => 0,
        'tue' => 0,
        'wed' => 0,
        'thu' => 0,
        'fri' => 0,
        'sat' => 0,
        'sun' => 0,
    ];

    use HasFactory, InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    use SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'created_by',
        'status',
        'coach_id',
        'owner_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'price',
        'schedule',
        'number_of_sessions',
        'latitude',
        'longtitude',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::CLOSE,
    ];

    protected $casts = [
        'schedule' => 'array'
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function coacher()
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }

    public function countStudent()
    {
        return $this->hasMany(StudioUser::class, 'studio_id', 'id')->where('approve', 1)->count();
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

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
            } else {
                return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
            }
        }else{
            return '';
        }
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getAvatarAttribute()
    {
        return $this->getMedia('image');
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile();
    }

    /**
     * get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return [
            self:: CLOSE  => 'Close',
            self:: OPEN   => 'Open',
        ][$this->status];
    }

    /**
     * get status
     *
     * @return array
     */
    public function getDisplayStatus(): array
    {
        return [
            self::OPEN  => ['text' => 'Active', 'color' =>'badge-success'],
            self::CLOSE => ['text' => 'Canceled', 'color' =>'badge-danger'],
        ][$this->status];
    }

    public function scopeName($query, $request)
    {
        if (isset($request['name'])) {
            $query->where('name', 'LIKE', '%' . $request['name'] . '%');
        }

        return $query;
    }


    /**
     * transform schedule
     *
     * @param array $value
     * @return void
     */
    public function setScheduleAttribute(array $value): void
    {
        $this->attributes['schedule'] = json_encode(array_merge(self::DEFAULT_SCHEDULE, $value));
    }

    /**
     * The users that belong to the studio.
     */
    public function students()
    {
        return $this->belongsToMany(User::class);
    }
}
