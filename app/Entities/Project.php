<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Project extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'owner_id',
        'client_id',
        'name',
        'description',
        'progress',
        'status',
        'due_date'
    ];

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function project(){
        return $this->hasMany(ProjectNote::class);
    }

    public function tasks(){
        return $this->hasMany(ProjectTask::class);
    }

    public function members(){
        return $this->belongsToMany(ProjectMember::class, 'project_members', 'project_id', 'user_id');
    }
}
