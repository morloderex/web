<?php

namespace App\Traits\Seeder;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Relation,
    lluminate\Database\Eloquent\Relations\hasMany,
    Illuminate\Database\Eloquent\Relations\BelongsTo,
    Illuminate\Database\Eloquent\Relations\MorphMany;


trait Relatable {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Create original factory
    $factory = $this->makeFactory($this->model)->create();

    // map each model of the factory with relationship seeds
    $factory->each(function($model){
      return $this->buildRelations($model);
    });
  }

  protected function validateClassVariables() {
    if(!$this->relationships)
      throw new \Exception("please define your relations as a protected array variable.");

    if(!$this->model)
        throw new \Exception("please define your model as a protected string variable.");

    if(!$this->times)
        throw new \Exception("please define how many times you want the factory to run per default.");
  }

  protected function makeFactory(string $model, int $times = 0) {
    if($times === 0)
      $times = $this->times;

    return factory($model)->times($times);
  }


  protected function buildRelations(Model $with) {
    foreach ($this->relationships as $related => $times) {
      if(is_numeric($related) OR is_integer($related))
      {
        // only class name given
        $related = $times;

        $times = $this->times;
      }

      if(is_array($times))
      {
        $relationMethod = $times['method'];
        $times = $times['times'];
      } else {
        $relationMethod = $this->reflectLikelyRelationName($related, $with);
      }

      $relation = $with->$relationMethod();

      switch ($relation) {
        case $relation instanceof BelongsTo:
          $this->handleBelongsTo($relation, $with);
          break;

        case $relation instanceof BelongsToMany:
          $this->handleManyRelations($relation, $with, True);
          break;

        case $relation instanceof hasMany OR $relation instanceof MorphMany:
          $this->handleManyRelations($relation, $with);
          break;


        default:
            if(!$related instanceof Model)
            {
              if(is_string($related) OR empty($related->getAttributes()))
              {
                $related = $this->makeFactory($related, 1)->create();
              } else {
                $type = gettype($related);
                throw new \Exception("Excpected String or Model, got $type.");
              }
            }

            $relation->save($related);
            break;
      }
    }
  }

  protected function handleManyRelations(Relation $relations, Model $with, bool $inverse = False) {
    foreach ($relations as $relation) {
      if($inverse === False)
      {
        // get the relationMethod for the parent model.
        $relatedRelationMethod = $this->reflectLikelyRelationName($relation, $with);

        // attach the related model to the parent model.
        $with->$relationMethod()->save($relation);
      } else {
        // if a belongsTo* relationship is returned,
        // inverse relationship and map this model to that relation
        $this->handleBelongsTo($relation, $with);
      }
    }
  }

  protected function handleBelongsTo(Relation $relation, Model $with) {
    // get related model,
    // if not, create one.
    $relation = $relation->getRelated();
    $relation = !empty($relation->getAttributes()) ? $relation : $this->makeFactory(get_class($relation), 1)->create();

    // get the related models relation method for the parent model.
    $relatedRelationMethod = $this->reflectLikelyRelationName($with, $relation);

    // attach the parent model to the related model
    $relation->$relatedRelationMethod()->save($with);
  }

  protected function reflectLikelyRelationName($relation, $model) {
    if(!is_string($relation) && !is_object($relation))
    {
      $type = gettype($relation);
      throw new \Exception("method reflectLikelyRelationName expects an object or a string, $type given. data: [$relation]");
    }

    $singular = (new \ReflectionClass($relation))->getShortName();
    return (method_exists($model, $singular)) ? $singular : str_plural($singular);
  }
}
