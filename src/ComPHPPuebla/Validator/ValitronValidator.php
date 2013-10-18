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
     * @var string
     */
    protected $pathToTranslations;

    /**
     * @param array $rules
     */
    public function __construct(array $rules, $pathToTranslations = 'config/validations')
    {
        $this->rules = $rules;
        $this->pathToTranslations = $pathToTranslations;
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function isValid(array $values)
    {
        $this->validator = new Valitron($values, [], 'en', $this->pathToTranslations);
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
