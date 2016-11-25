<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectTaskService;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use CodeProject\Http\Requests\ProjectTaskCreateRequest;
use CodeProject\Http\Requests\ProjectTaskUpdateRequest;
use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;


class ProjectTaskController extends Controller
{

    /**
     * @var ProjectTaskRepository
     */
    protected $repository;

    /**
     * @var ProjectTaskService
     */
    protected $service;

    public function __construct(ProjectTaskRepository $repository, ProjectTaskService $taskService)
    {
        $this->repository = $repository;
        $this->service  = $taskService;
    }


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $this->service->findWhere($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProjectTaskCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectTaskCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $projectTask = $this->repository->create($request->all());

            $response = [
                'message' => 'ProjectTask created.',
                'data'    => $projectTask->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param $taskId
     * @return \Illuminate\Http\Response
     */
    public function show($id, $taskId)
    {
        return $this->service->findWhere(['project_id'=>$id, 'id'=>$taskId]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ProjectTaskUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ProjectTaskUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $projectTask = $this->repository->update($id, $request->all());

            $response = [
                'message' => 'ProjectTask updated.',
                'data'    => $projectTask->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ProjectTask deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ProjectTask deleted.');
    }
}
