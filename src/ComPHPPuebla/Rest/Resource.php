<?php
namespace ComPHPPuebla\Rest;

use \ComPHPPuebla\Validator\Validator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;

class Resource implements Validator
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Table     $table
     * @param Validator $validator
     */
    public function __construct(Table $table, Validator $validator = null)
    {
        $this->table = $table;
        $this->validator = $validator;
    }

    /**
     * @param  array $newResource
     * @return array
     */
    public function create(array $newResource)
    {
        $id = $this->table->insert($newResource);

        return $this->retrieveOne($id);
    }

    /**
     * @param  array $resourceValues
     * @param  int   $id
     * @return array
     */
    public function update(array $resourceValues, $id)
    {
        return $this->table->update($resourceValues, $id);
    }

    /**
     * @param  array   $values
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
