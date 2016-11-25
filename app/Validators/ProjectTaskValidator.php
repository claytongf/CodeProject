<?php

namespace CodeProject\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends LaravelValidator
{

    protected $rules = [
        'project_id' => 'required|integer',
        'name' => 'required|max:255',
        'status' => 'required',
        'start_date' => 'required|date',
        'due_date' => 'required|date'
   ];
}
