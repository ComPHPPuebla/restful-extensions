<?php
namespace ComPHPPuebla\Slim\Controller;

use \Iterator;
use \Zend\EventManager\EventManagerInterface;
use \ComPHPPuebla\Model\Model;
use \ComPHPPuebla\Controller\Exception\ResourceNotFoundException;
use \ComPHPPuebla\Controller\Exception\BadRequestParameters;

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
            $this->triggerPostDispatch($resource);

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
        $this->triggerPostDispatch($resources);
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
            $this->triggerPostDispatch($resource);

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
            $this->validate(array_merge($resource, $values));
            $resource = $this->model->update($values, $id);
            $this->triggerPostDispatch($resource);

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
        $this->triggerPostDispatch($errors);
    }

    /**
     * @param Iterator $resource
     */
    protected function triggerPostDispatch(Iterator $resource)
    {
        $argv = [
            'resource' => $resource, 'request' => $this->request, 'response' => $this->response
        ];
        $this->eventManager->trigger('postDispatch', $this, $argv);
    }
}
