<?php namespace Helium\Form;

use Helium\Contract\HeliumEntity;
use Illuminate\Support\Facades\Validator;

class FormHandler
{
    /**
     * @var HeliumEntity
     */
    protected $entity = null;

    /**
     * Rules to validate the form
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Custom messages for validation
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Construct
     *
     * @param HeliumEntity $entity
     */
    public function __construct(HeliumEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Validates the form data
     *
     * @param array $postData
     * @return self
     */
    public function validate(array $postData) : self
    {
        $fields = $this->entity->getFields();

        Validator::make(
            $postData,
            $this->buildRules($fields),
            $this->buildMessages($fields)
        )->validate();

        return $this;
    }

    /**
     * The form is posted, save the data
     *
     * @return void
     */
    public function post(array $postData) : self
    {
        $this->entity->getRepository()->save($postData);
        return $this;
    }

    /**
     * Builds  a set of rules for the form
     *
     * @param array $fields
     * @return array
     */
    protected function buildRules(array $fields) : array
    {
        return array_merge_deep(
            array_map(
                function ($field) {
                    return $this->getDefaultFieldRules($field);
                },
                $fields
            ),
            $this->rules
        );
    }

    /**
     * Gets some default validation rules based on the database settings
     *
     * @param Column $column
     * @return array
     */
    protected function getDefaultFieldRules(array $field) : array
    {
        $rules = [];

        if (in_array($field['type'], ['string', 'text'])) {
            $rules[] = 'max:' . $field['length'];
        } elseif (in_array($field['type'], ['integer', 'bigint', 'smallint'])) {
            $rules[] = 'integer';
        } elseif (in_array($field['type'], ['float', 'decimal'])) {
            $rules[] = 'numeric';
        }

        if (!empty($field['required'])) {
            $rules[] = 'required';
        }

        return $rules;
    }

    /**
     * Builds a set of rules for the form
     *
     * @param array $fields
     * @return array
     */
    protected function buildMessages(array $fields) : array
    {
        return $this->messages;
    }
}
