<?php
namespace ComPHPPuebla\Validator;

use \Valitron\Validator as Valitron;

class ValitronValidator implements Validator
{
    /**
     * @var array
     */
    protected $rules;

    /**
     * @var Valitron
     */
    protected $validator;

    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function isValid(array $values)
    {
        $this->validator = new Valitron($values, [], 'en', 'config/validations');
        $this->validator->rules($this->rules);

        return $this->validator->validate();
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
