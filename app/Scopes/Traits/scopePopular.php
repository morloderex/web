<?php

namespace App\Scopes\Traits;

trait scopePopular {

	protected $popularAttribute = 'score';

	public function scopePopular($query,array $columns = ['*'], int $take = 10) {
		$popularAttribute = $this->popularAttribute;

        return $query->select($columns)
						->take($take)
                        ->where($popularAttribute, '>', 0)
                        ->orderBy($popularAttribute, 'desc')
                        ->get();
    }

    /**
     * Gets the value of popularAttribute.
     *
     * @return string
     */
    public function getPopularAttribute() : string
    {
        return $this->popularAttribute;
    }

    /**
     * Sets the value of popularAttribute.
     *
     * @param string $popularAttribute the popular attribute
     *
     * @return self
     */
    protected function setPopularAttribute(string $popularAttribute) : self
    {
        $this->popularAttribute = $popularAttribute;

        return $this;
    }
}
