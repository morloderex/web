<?php 

namespace App\Traits\Model;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
	protected $short_name;

	protected static $recordEvents = ['created', 'updated', 'deleted'];

	protected static function bootRecordsActivity()
	{
		foreach (static::getModelEvents() as $event) {
			
			// Hook into the event,
			// If the given event is firing, 
			// then create (or record) an activity.
			static::$event(function($model) use($event){
				$model->createActivity($event);				
			});
		}
	}

	protected static function getModelEvents()
	{
		$defaults = ['created', 'updated', 'deleted'];

		if(isset(static::$recordEvents))
			return array_unique(array_merge(static::$recordEvents, $defaults));

		return $defaults;
	}

	public function createActivity($event)
	{
		$activity 				=	new Activity;
		$activity->name 		=	$this->getActivityName($event);
		$activity->user_id 		=   $this->getUserId();	
		$activity->subject_id 	=	$this->id;
		$activity->subject_type =	get_class($this);

		$activity->save();
	}

	protected function getUserId()
	{
		$id = $this->short_name == 'User' ? $this->id : $this->user_id;
		if(!$id)
		{
			$id = Auth::id();
		}
		return $id;
	}

	protected function getActivityName($event)
	{
		$this->short_name 	=	(new \ReflectionClass($this))->getShortName();
		return 	"{$event}_{$this->short_name}";
	}
}

?>