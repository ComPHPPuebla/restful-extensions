<?php
namespace ComPHPPuebla\Model;

use \ComPHPPuebla\Validator\Validator;
use \ComPHPPuebla\Doctrine\Repository;

class Model implements Validator
{
    /**
     * @var array
     */
    protected $optionsList;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var repository
     */
    protected $repository;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository, Validator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->optionsList = ['GET', 'POST', 'OPTIONS'];
        $this->options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];
    }

    /**
     * @param array $criteria
     * @param ResourceCollection $resources
     */
    public function retrieveAll(array $criteria)
    {
        $collection = $this->repository->findAll($criteria);
        $collection['count'] = $this->repository->count();

        return $collection;
    }

    /**
     * @param int $id
     * @param Resource $resource
     * @return array
     */
    public function retrieveOne($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $newResource
     * @param Resource $resource
     * @return array
     */
    public function create(array $newResource)
    {
        $id = $this->repository->insert($newResource);

        return $this->repository->find($id);
    }

    /**
     * @param array $resourceValues
     * @param int $id
     * @return array
     */
    public function update(array $resourceValues, $id)
    {
        return $this->repository->update($resourceValues, $id);
    }

    /**
     * @param int $stationId
     */
    public function delete($stationId)
    {
        $this->repository->delete($stationId);
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function isValid(array $values)
    {
        return $this->validator->isValid($values);
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
