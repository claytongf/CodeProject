<?php
/**
 * Created by PhpStorm.
 * User: Clayton
 * Date: 08/11/2016
 * Time: 13:13
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $repository;
    /**
     * @var ClientValidator
     */
    private $validator;

    /**
     * ClientService constructor.
     * @param ClientRepository $repository
     * @param ClientValidator $validator
     */
    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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
            $client = $this->repository->find($id);
            return $client;
        }catch(ModelNotFoundException $e){
            return ['error' => true, "message" =>  "Cliente não encontrado."];
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->findOrFail($id)->delete();
            return ['success'=>true, "message" => 'Cliente deletado com sucesso!'];
        } catch (QueryException $e) {
            return ['error'=>true, "message" => 'Cliente não pode ser apagado pois existe um ou mais clientes vinculados a ele.'];
        } catch (ModelNotFoundException $e) {
            return ['error'=>true, "message" => 'Cliente não encontrado.'];
        } catch (Exception $e) {
            return ['error'=>true, "message" => 'Ocorreu algum erro ao excluir o cliente.'];
        }
    }
}