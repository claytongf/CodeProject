<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectMemberService;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{

    /**
     * @var ProjectMemberRepository
     */
    private $repository;
    /**
     * @var ProjectMemberService
     */
    private $service;

    public function __construct(ProjectMemberRepository $memberRepository, ProjectMemberService $memberService)
    {
        $this->repository = $memberRepository;
        $this->service = $memberService;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param $memberId
     * @return \Illuminate\Http\Response
     */
    public function show($id, $memberId)
    {
        return $this->service->findWhere(['project_id'=>$id, 'id'=>$memberId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param $memberId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $memberId)
    {
        return $this->service->update($request->all(), $memberId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param $memberId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $memberId)
    {
        return $this->service->destroy($memberId);
    }
}
