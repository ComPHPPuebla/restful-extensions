<?php
namespace ComPHPPuebla\Model;

use \ComPHPPuebla\Validator\Validator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \ArrayObject;

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
     * @param Table $table
     */
    public function __construct(Table $table, Validator $validator)
    {
        $this->table = $table;
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
        $resources = $this->table->findAll($criteria);

        return is_array($resources) ? new ArrayObject($resources) : $resources;
    }

    /**
     * @param int $id
     * @param Resource $resource
     * @return array
     */
    public function retrieveOne($id)
    {
        $resource = $this->table->find($id);

        return is_array($resource) ? new ArrayObject($resource) : $resource;
    }

    /**
     * @param array $newResource
     * @param Resource $resource
     * @return array
     */
    public function create(array $newResource)
    {
        $id = $this->table->insert($newResource);

        return $this->retrieveOne($id);
    }

    /**
     * @param array $resourceValues
     * @param int $id
     * @return array
     */
    public function update(array $resourceValues, $id)
    {
        return new ArrayObject($this->table->update($resourceValues, $id));
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->table->delete($id);
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
        return new ArrayObject($this->validator->errors());
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getOptionsList()
    {
        return $this->optionsList;
    }
}
