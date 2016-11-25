<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService
{
    /**
     * @var ProjectTaskRepository
     */
    protected $repository;
    /**
     * @var ProjectTaskValidator
     */
    private $validator;

    /**
     * ClientService constructor.
     * @param ProjectTaskRepository $taskRepository
     * @param ProjectTaskValidator $taskValidator
     * @internal param ProjectNoteRepository $repository
     * @internal param ProjectNoteValidator $validator
     */
    public function __construct(ProjectTaskRepository $taskRepository, ProjectTaskValidator $taskValidator)
    {
        $this->repository = $taskRepository;
        $this->validator = $taskValidator;
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

    public function findWhere($id){
        try{
            return $this->repository->findWhere(['project_id' => $id['project_id']]);
        }catch (ModelNotFoundException $e){
            return ['error' => true, "message" => "Projeto não encontrado."];
        }
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
}