<?php

namespace Database\Factories;

use DB;

/**
* Attempts to generate fields using faker,
* prefers to use eloquent casts as pivot
* but alternatively *can* guess column
*/
class FactoryGenerator
{		
	protected $faker;

	protected $model;

	protected $fields = [];

	public function __construct(Faker\Generator $faker)
	{
		$this->faker = $faker;
	}

	public function run() {
		$model = $this->getModel();
		if( ! $model )
			throw new \MissingDependencyException("Model was not defined.");
		
		$faker = $this->faker;

		$casts = $model->getCasts();
		$fields = $this->getFields();
		if(!empty($fields))
		{
			$fields = array_merge($casts, $fields);
		} else {
			$fields = $casts;
		}

		if(empty($fields))
		{
			$fields = DB::query('DESCRIBE '.$model->getTable());
		}

		$data = [];
		foreach ($fields as $field) {
			if(method_exists($faker, $field) || property_exists($faker, $field))
			{
				$data[$field] = $faker->$field;
			}
		}

		return $data;
	}

    /**
     * Gets the value of model.
     *
     * @return mixed
     */
    public function getModel() : Model
    {
        return $this->model;
    }

    /**
     * Sets the value of model.
     *
     * @param mixed $model the model
     *
     * @return self
     */
    protected function setModel(Model $model) : FactoryGenerator
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the value of fields.
     *
     * @return array
     */
    public function getFields() : array
    {
        return $this->fields;
    }

    /**
     * Sets the value of fields.
     *
     * @param array $fields the fields
     *
     * @return self
     */
    protected function setFields(array $fields) : FactoryGenerator
    {
        $this->fields = $fields;

        return $this;
    }
}