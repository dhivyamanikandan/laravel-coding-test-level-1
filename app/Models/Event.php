<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes,HasFactory;

    protected $table = 'event';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $dates = ['createdAt','updatedAt','deletedAt'];

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    const DELETED_AT = 'deletedAt';

    protected $fillable = ['startAt','endAt','name', 'slug'];

    public static function boot(){

        parent::boot();

        static::creating(function ($issue) {
            $issue->id = Str::uuid(36);
            $issue->deletedAt = null;
        });
    }

    public function setSlugAttribute($value)
    {
        if($value === null && $this->slug === null){
            $this->attributes['slug'] = Str::slug($this->attributes['name'].'-'.time());
        } else {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setStartAtAttribute($value)
    {
        if($value === null && $this->startAt === null){
            $this->attributes['startAt'] = date('Y-m-d H:i:s', strtotime('-1 week', strtotime(date("Y-m-d H:i:s"))));
        } else {
            $this->attributes['startAt'] = $value;
        }
    }

    public function setEndAtAttribute($value)
    {
        if($value === null && $this->endAt === null){
            $this->attributes['endAt'] = date('Y-m-d H:i:s', strtotime('+1 week', strtotime(date("Y-m-d H:i:s"))));
        } else {
            $this->attributes['endAt'] = $value;
        }
    }

}
