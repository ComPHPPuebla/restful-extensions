<?php
namespace ComPHPPuebla\Slim\Controller;

use \ReflectionMethod;
use \Zend\EventManager\EventManagerInterface;
use \ComPHPPuebla\Model\Model;

class RestController extends SlimController
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([
            __CLASS__,
            get_called_class(),
        ]);
        $this->eventManager = $eventManager;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $id
     */
    public function get($id)
    {
        return $this->findById($id);
    }

    /**
     * @param ResourceCollection $collection
     * @return array
     */
    public function getList()
    {
        return $this->model->retrieveAll($this->request->params());
    }

    /**
     * @param Resource $resource
     * @return array
     */
    public function post()
    {
        parse_str($this->request->getBody(), $values);

        if (!$this->validate($values)) {

            return $this->model->errors();
        }

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
        if (!$resource) {

            return;
        }

        parse_str($this->request->getBody(), $values);

        if (!$this->validate(array_merge($resource, $values))) {

            return $this->model->errors();
        }

        return $this->model->update($values, $id);
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $resource = $this->findById($id);
        if (!$resource) {

            return;
        }

        $this->model->delete($id);
        $this->response->status(204); //No Content
    }

    /**
     * @return void
     */
    public function optionsList()
    {
        $this->response->headers->set('Allow', implode(',', $this->optionsList));
    }

    /**
     * @return void
     */
    public function options()
    {
        $this->response->headers->set('Allow', implode(',', $this->options));
    }

    /**
     * @return array
     */
    protected function findById($id)
    {
        $resource = $this->model->retrieveOne($id);

        if (!$resource) {
            $this->response->status(404); //Not found
        }

        return $resource;
    }

    /**
     * @param array $values
     * @return boolean
     */
    protected function validate(array $values)
    {
        $isValid = $this->model->isValid($values);
        if (!$isValid) {
            $this->response->setStatus(400); //Bad request
        }

        return $isValid;
    }

    /**
     * @param string $methodName
     * @param array $params
     * @return void
     */
    public function dispatch($methodName, array $params = [])
    {
        $method = new ReflectionMethod(__CLASS__, $methodName);
        $resource = $method->invokeArgs($this, $params);

        $argv = [
            'resource' => $resource, 'request' => $this->request, 'response' => $this->response
        ];
        $this->eventManager->trigger('postDispatch', $this, $argv);
    }
}
