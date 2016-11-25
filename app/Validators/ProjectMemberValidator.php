<?php
namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectMemberValidator extends LaravelValidator
{
    protected $rules = [
        'project_id' => 'required',
        'user_id' => 'required'
    ];
}