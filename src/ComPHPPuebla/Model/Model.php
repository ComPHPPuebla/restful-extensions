<?php
namespace ComPHPPuebla\Model;

use \ComPHPPuebla\Validator\Validator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \ComPHPPuebla\Paginator\PaginatorFactory;

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
     * @var Table
     */
    protected $table;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var PaginatorFactory
     */
    protected $paginatorFactory;

    /**
     * @param Table            $table
     * @param Validator        $validator
     * @param PaginatorFactory $paginatorFactory
     * @param array            $options
     * @param array            $optionsList
     */
    public function __construct(
        Table $table,
        Validator $validator,
        PaginatorFactory $paginatorFactory,
        array $options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'],
        array $optionsList = ['GET', 'POST', 'OPTIONS']
    )
    {
        $this->table = $table;
        $this->validator = $validator;
        $this->paginatorFactory = $paginatorFactory;
        $this->options = $options;
        $this->optionsList = $optionsList;
    }

    /**
     * @param  array     $criteria
     * @return Paginator
     */
    public function retrieveAll(array $criteria)
    {
        return $this->paginatorFactory->createPaginator($criteria, $this->table);
    }

    /**
     * @param  int   $id
     * @return array
     */
    public function retrieveOne($id)
    {
        return $this->table->find($id);
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
     * @param int $id
     */
    public function delete($id)
    {
        $this->table->delete($id);
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
