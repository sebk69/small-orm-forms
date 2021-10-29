<?php

namespace Sebk\SmallOrmForms\Form;

use Sebk\SmallOrmCore\Dao\AbstractDao;
use Sebk\SmallOrmCore\Dao\Model;
use Sebk\SmallOrmCore\Dao\Relation;
use Sebk\SmallOrmCore\Dao\ToManyRelation;
use Sebk\SmallOrmCore\Dao\ToOneRelation;
use Sebk\SmallOrmCore\Factory\DaoNotFoundException;
use Sebk\SmallOrmForms\Type\BoolType;
use Sebk\SmallOrmForms\Type\DateTimeType;
use Sebk\SmallOrmForms\Type\FloatType;
use Sebk\SmallOrmForms\Type\IntType;
use Sebk\SmallOrmForms\Type\StringType;

class FormModel extends AbstractForm
{
    /**
     * @var AbstractDao
     */
    protected $dao;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var FormModel[]
     */
    protected $toOneSubforms = [];

    /**
     * Build fields from a dao
     * @param AbstractDao $dao
     * @return $this
     * @throws FieldException
     * @throws \Sebk\SmallOrmForms\Type\TypeNotFoundException
     */
    public function buildFromDao(AbstractDao $dao)
    {
        if ($this->dao instanceof AbstractDao) {
            throw new \Exception("DAO already initialized !");
        }

        foreach ($dao->getFields(true) as $daoField)
        {
            $this->addField(
                $daoField->getModelName(),
                null,
                $dao->getDefaultValue($daoField->getModelName()),
                $this->getTypeFromDaoField($daoField)
            );
        }

        $this->dao = $dao;

        return $this;
    }

    /**
     * Fill form values from model
     * @param Model $model
     * @return $this
     * @throws FieldException
     * @throws FieldNotFoundException
     * @throws \Sebk\SmallOrmForms\Type\TypeNotFoundException
     */
    public function fillFromModel(Model $model)
    {
        // Build if necessary
        if ($this->dao == null) {
            $this->buildFromDao($model->getDao());
        }
        $this->model = $model;

        // Check model
        $path = explode('\\', get_class($model));
        $modelName = array_pop($path);
        if ($this->dao->getModelName() != $modelName) {
            throw new \Exception("Model is not a " . $this->dao->getModelName() . " model !");
        }

        // Convert to array
        $array = $model->toArray(false, true);

        // And fill
        $this->fillFromArray($array);

        // Fill subforms
        foreach ($this->toOneSubforms as $alias => $subform) {
            $method = "get$alias";
            if ($model->$method() != null && !is_array($model->$method())) {
                $subform->fillFromModel($model->$method());
            }
        }

        return $this;
    }

    /**
     * Fill a model with form values and return it. If model is null, create a new model.
     * @param Model|null $model
     * @return Model|\Sebk\SmallOrmCore\Dao\modelClass
     * @throws \Exception
     */
    public function fillModel(Model $model = null)
    {
        // Is form initialized ?
        if ($this->dao == null) {
            throw new \Exception("Form not initialized !");
        }

        // Model already set ?
        if ($this->model != null) {
            $model = $this->model;
        }

        // Must we create model ?
        if ($model == null) {
            $stdClass = new \stdClass();
            foreach ($this->fields as $key => $field) {
                $stdClass->$key = $field->getValue();
            }

            return $this->dao->makeModelFromStdClass($stdClass);
        }

        // Fill model fields with form fields
        foreach ($this->fields as $key => $field) {
            $method = "raw" . $key;
            $model->$method($field->getValue());
        }

        // Return model
        return $model;
    }

    /**
     * Check form and model
     * @return \Sebk\SmallOrmForms\Message\MessageCollection
     * @throws \Exception
     */
    public function validate()
    {
        // Technical validations
        $messages = parent::validate();

        // Get model from form
        if ($this->model == null) {
            $this->model = $this->fillModel();
        } else {
            $this->model = $this->fillModel($this->model);
        }

        // Validate model with model validator
        try {
            if (!$this->model->getValidator()->validate()) {
                $messages->merge($this->model->getValidator()->getMessage());
            }
        } catch (DaoNotFoundException $e) {}

        // Merge validations from subforms
        foreach ($this->toOneSubforms as $subform) {
            $messages->merge($subform->validate());
        }

        // Return messages
        return $messages;
    }

    /**
     * Convert DAO field type to form field type
     * @param \Sebk\SmallOrmCore\Dao\Field $daoField
     * @return BoolType|DateTimeType|FloatType|IntType|StringType
     * @throws \Exception
     */
    protected function getTypeFromDaoField(\Sebk\SmallOrmCore\Dao\Field $daoField)
    {
        switch ($daoField->getType()) {
            case \Sebk\SmallOrmCore\Dao\Field::TYPE_BOOLEAN:
                return BoolType::TYPE_BOOL;
            case \Sebk\SmallOrmCore\Dao\Field::TYPE_FLOAT:
                return FloatType::TYPE_FLOAT;
            case \Sebk\SmallOrmCore\Dao\Field::TYPE_INT:
                return IntType::TYPE_INT;
            case \Sebk\SmallOrmCore\Dao\Field::TYPE_DATETIME:
                return DateTimeType::TYPE_DATE_TIME;
            case \Sebk\SmallOrmCore\Dao\Field::TYPE_STRING:
                return StringType::TYPE_STRING;
        }

        throw new \Exception("Type of DAO field unknown (" . $daoField->getType() . ")");
    }

    /**
     * Create subform from relation alias
     * @param $alias
     * @return FormModel
     * @throws \Exception
     */
    public function addSubformFromRelation($alias)
    {
        // Is form initialized ?
        if ($this->dao == null) {
            throw new \Exception("Form not initialized !");
        }

        // Get relation
        $relation = $this->getRelation($alias);

        // To many subforms not implemented yet
        if ($relation instanceof ToManyRelation) {
            throw new \Exception("To many subforms not implemented yet !");
        }

        // Create to one subform
        if ($relation instanceof ToOneRelation) {
            $subform = $this->buildFormFromRelation($relation);
            $this->toOneSubforms[$alias] = $subform;

            return $subform;
        }
    }

    // Get a relation from alias
    protected function getRelation(string $alias)
    {
        // Get relations
        $toOneRelations = $this->dao->getToOneRelations();
        $toManyRelations = $this->dao->getToManyRelations();

        // Relation exists ?
        if (!isset($toOneRelations[$alias]) && !isset($toManyRelations[$alias])) {
            throw new \Exception("Relation $alias not found");
        }

        // already set ?
        if (isset($this->toOneSubforms[$alias])) {
            throw new \Exception("Relation $alias allready set");
        }

        // return relation
        if (isset($toOneRelations[$alias])) {
            return $toOneRelations[$alias];
        }

        return $toManyRelations[$alias];
    }

    // Build subform from a relation
    protected function buildFormFromRelation(Relation $relation)
    {
        return (new FormModel())
            ->buildFromDao($relation->getDao());
    }

    /**
     * Get a subform
     * @param $alias
     * @return FormModel
     */
    public function getSubform($alias)
    {
        return $this->toOneSubforms[$alias];
    }

    /**
     * Fill form from an array
     * @param array $array
     * @return FormModel
     * @throws FieldException
     * @throws FieldNotFoundException
     */
    public function fillFromArray(array $array)
    {
        parent::fillFromArray($array);

        foreach ($array as $key => $value) {
            if (isset($this->toOneSubforms[$key]) && is_array($value)) {
                $this->toOneSubforms[$key]->fillFromArray($value);
            }
        }

        return $this;
    }
}