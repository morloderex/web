<?php

namespace App\Traits\Seeder;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation,
    Illuminate\Database\Eloquent\Relations\HasMany,
    Illuminate\Database\Eloquent\Relations\BelongsTo,
    Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;


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
      $this->buildRelations($model);
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
      if(is_string($related))
      {
        $related = $this->makeFactory($related, $times)->create();
      }

      if($related instanceof Collection)
      {
        // Yes, i know it's not a registered word.
        $related->each(function($relatee) use($relation) {
          $this->handleRelation($relation, $relatee);
        });
      } else {
        $this->handleRelation($relation, $related);
      }
    }
  }

  protected function handleRelation(Relation $relation, Model $related)
  {
    switch ($relation) {
      case $relation instanceof BelongsTo:
        $this->handleBelongsTo($relation, $related);
        break;

      case $relation instanceof BelongsToMany:
        $this->handleManyRelations($relation, $related, True);
        break;

      case $relation instanceof HasMany OR $relation instanceof MorphMany:
        $this->handleManyRelations($relation, $related);
        break;


      default:
        if(!$related instanceof Model)
        {
          $type = gettype($related);
          throw new \Exception("Excpected String or Model, got $type.");
        }
        break;
    }
  }

  protected function handleManyRelations(Relation $relation, Model $with, bool $inverse = False) {
      if($inverse === True)
      {
        // get the relationMethod for the parent model.
        $relatedRelationMethod = $this->reflectLikelyRelationName($relation->getParent(), $with);

        // attach the parent model to the related model.
        $with->$relatedRelationMethod()->save($relation->getParent());
      } else {
        // if a belongsTo* relationship is returned,
        // inverse relationship and map this model to that relation
        $this->handleBelongsTo($relation, $with);
      }
  }

  protected function handleBelongsTo(Relation $relation, Model $with) {

    // If for some reason save is not available on given relation,
    // this is typically seen when a hasMany -> belongsTo relation is attempt called in reverse..
    // ie. a User can save a Post BUT a post CANNOT save a User.

    // just dump and skip for now.
    if( ! method_exists($relation, 'save'))
    {
      dump("cannot save [ " . get_class($relation) . " ] between { ". get_class($relation->getParent()) . " } and { ". get_class($with) . " } ");
      return;
    }

    // attach the related model to the parent model
    $relation->save($with);
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
