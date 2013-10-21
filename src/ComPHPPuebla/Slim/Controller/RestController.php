<?php
namespace ComPHPPuebla\Slim\Controller;

use \Zend\EventManager\EventManagerInterface;
use \ComPHPPuebla\Model\Model;
use \ComPHPPuebla\Slim\Controller\Exception\ResourceNotFoundException;
use \ComPHPPuebla\Slim\Controller\Exception\BadRequestParameters;
use \IteratorAggregate;

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
        try {

            $resource = $this->findById($id);
            $this->triggerEvent('postDispatch', $resource);

        } catch(ResourceNotFoundException $e) {
            $this->response->status(404); //Not Found
        }
    }

    /**
     * @return array
     */
    public function getList()
    {
        $resources = $this->model->retrieveAll($this->request->params());
        $this->triggerEvent('postDispatch', $resources);
    }

    /**
     * @param Resource $resource
     * @return array
     */
    public function post()
    {
        try {
            parse_str($this->request->getBody(), $values);
            $this->validate($values);
            $resource = $this->model->create($values);
            $this->response->setStatus(201); //Created
            $this->triggerEvent('postDispatch', $resource);

        } catch (BadRequestParameters $e) {
            $this->handleBadRequest();
        }
    }

    /**
     * @param int $id
     * @param Resource $resource
     * @return array
     */
    public function put($id)
    {
        try {
            $resource = $this->findById($id);
            parse_str($this->request->getBody(), $values);
            $this->validate(array_merge($resource->getArrayCopy(), $values));
            $resource = $this->model->update($values, $id);
            $this->triggerEvent('postDispatch', $resource);

        } catch(ResourceNotFoundException $e) {
            $this->response->status(404); //Not Found
        } catch (BadRequestParameters $e) {
            $this->handleBadRequest();
        }
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        try {
            $resource = $this->findById($id);
            $this->model->delete($id);
            $this->response->status(204); //No Content

        } catch(ResourceNotFoundException $e) {
            $this->response->status(404); //Not Found
        }
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
     * @param int $id
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

    /**
     * @return void
     */
    protected function handleBadRequest()
    {
        $this->response->setStatus(400); //Bad request
        $errors = $this->model->errors();
        $this->triggerEvent('renderErrors', $errors);
    }

    /**
     * @param string $name
     * @param IteratorAggregate $resource
     */
    protected function triggerEvent($name, IteratorAggregate $resource)
    {
        $argv = [
            'resource' => $resource, 'request' => $this->request, 'response' => $this->response
        ];
        $this->eventManager->trigger($name, $this, $argv);
    }
}
