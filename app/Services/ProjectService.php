<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;
    /**
     * @var ProjectValidator
     */
    private $validator;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Storage
     */
    private $storage;

    /**
     * ClientService constructor.
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     * @param Filesystem $filesystem
     * @param Storage $storage
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator, Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    public function create(array $data){
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function update(array $data, $id){
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function find($id){
        try{
            $project = $this->repository->with(['owner','client'])->find($id);
            return $project;
        }catch(ModelNotFoundException $e){
            return ['error' => true, "message" => "Projeto não encontrado."];
        }
    }

    public function findWhere($id){
        $project = $this->repository->with(['owner','client'])->findWhere(['owner_id'=>$id]);
        return $project;
    }

    public function all(){
        return $this->repository->with(['owner','client'])->all();
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        try {
            $this->repository->find($id)->delete();
            return ['success'=>true, "message" => 'Projeto deletado com sucesso!'];
        } catch (QueryException $e) {
            return ['error'=>true, "message" => 'Projeto não pode ser apagado pois existe um ou mais clientes vinculados a ele.'];
        } catch (ModelNotFoundException $e) {
            return ['error'=>true, "message" => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error'=>true, "message" => 'Ocorreu algum erro ao excluir o projeto.'];
        }
    }

    public function addMember($projectId, $memberId){
        try{
            $this->repository->find($projectId)->members()->attach($memberId);
            return ['success'=>true, "message" => "Membro adicionado ao projeto com sucesso!"];
        }catch(ModelNotFoundException $e){
            return ['error'=>true, 'message'=>'Projeto ou usuário não encontrado.'];
        }
    }

    public function removeMember($projectId, $memberId){
        try{
            $this->repository->with(['members'])->find($projectId)->detach($memberId);
            return ['success'=>true, "message" => "Membro removido do projeto com sucesso!"];
        }catch(ModelNotFoundException $e){
            return ['error'=>true, 'message'=>'Projeto ou usuário não encontrado.'];
        }
    }

    public function isMember($projectId, $memberId){
        $member = $this->repository->find($projectId)->members()->find(['user_id'=>$memberId]);
        if(count($member)){
            return true;
        }
        return false;
    }

    public function createFile(array $data){
        // name
        // description
        // extension
        // file
        $project = $this->repository->skipPresenter()->find($data['project_id']);
        $projectFile = $project->files()->create($data);
        $this->storage->put($projectFile->id.".".$data['extension'], $this->filesystem->get($data['file']));
    }

    public function checkProjectOwner($projectId){
        $userId = Authorizer::getResourceOwnerId();
        return $this->repository->isOwner($projectId, $userId);
    }

    public function checkProjectMember($projectId){
        $userId = Authorizer::getResourceOwnerId();
        return $this->service->isMember($projectId, $userId);
    }

    public function checkProjectPermissions($projectId){
        if($this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId)){
            return true;
        }
        return false;
    }
}