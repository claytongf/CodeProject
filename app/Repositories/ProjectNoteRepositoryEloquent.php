<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\ProjectNote;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectNoteRepositoryEloquent extends BaseRepository implements ProjectNoteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectNote::class;
    }
}
