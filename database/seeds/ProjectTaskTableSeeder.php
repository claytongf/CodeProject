<?php

use CodeProject\Entities\ProjectTask;
use Illuminate\Database\Seeder;

class ProjectTaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Project::truncate();
        factory(ProjectTask::class, 50)->create();
    }
}
