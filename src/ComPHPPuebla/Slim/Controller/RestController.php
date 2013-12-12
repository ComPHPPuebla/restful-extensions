<?php
namespace ComPHPPuebla\Slim\Controller;

use \ComPHPPuebla\Model\Model;
use \ComPHPPuebla\Slim\Controller\Exception\ResourceNotFoundException;
use \ComPHPPuebla\Slim\Controller\Exception\BadRequestParameters;

class RestController extends SlimController
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        return $this->findById($id);
    }

    /**
     * @return \ComPHPPuebla\Paginator\Paginator
     */
    public function getList()
    {
        return $this->model->retrieveAll($this->request->params());
    }

    /**
     * @return array
     */
    public function post()
    {
        parse_str($this->request->getBody(), $values);
        $this->validate($values);
        $resource = $this->model->create($values);
        $this->response->setStatus(201); //Created

        return $resource;
    }

    /**
     * @param int $id
     * @param Resource $resource
     * @return array
     */
    public function put($id)
    {
        $resource = $this->findById($id);
        parse_str($this->request->getBody(), $values);
        $this->validate(array_merge($resource, $values));

        return $this->model->update($values, $id);

    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $resource = $this->findById($id);
        $this->model->delete($id);
        $this->response->status(204); //No Content
    }

    /**
     * @return void
     */
    public function optionsList()
    {
        $this->response->headers->set('Allow', implode(',', $this->model->getOptionsList()));
    }

    /**
     * @return void
     */
    public function options()
    {
        $this->response->headers->set('Allow', implode(',', $this->model->getOptions()));
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->model->errors();
    }

    /**
     * @param int $id
     * @return array
     * @throws ResourceNotFoundException
     */
    protected function findById($id)
    {
        if ($resource = $this->model->retrieveOne($id)) {

            return $resource;
        }
        throw new ResourceNotFoundException("Resource with ID: $id, cannot be found");
    }

    /**
     * @param array $values
     * @return boolean
     */
    protected function validate(array $values)
    {
        if (!$this->model->isValid($values)) {

            throw new BadRequestParameters('Resource values are invalid');
        }
    }
}
