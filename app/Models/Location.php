<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Event\Mail\ChangedIPNotification;
use Event;

class Location extends Model
{
    protected $fillable = [
      'current_ip',
      'last_ip',
      'ip_changed_times',
      'country',
      'city'
    ];

    protected $casts = [
      'current_ip'        =>  'string',
      'last_ip'           =>  'string',
      'ip_changed_times'  =>  'integer',  
      'country'           =>  'string',
      'city'              =>  'string'
    ];

    public Static function boot() {
      static::creating(function($location){
          $location->updateLocation();
      });

      static::updating(function($location){
          $location->updateLocation();
      });
    }

    protected function updateLocation() : Location 
    {
      $data = $location->getLocation();

      if($this->ip_changed_times > 3 && $this->last_ip !== $this->current_ip)
      {
        Event::fire(new ChangedIPNotification($this));
      }

      $location->country = $data['country'];
      $location->city    = $data['city'];

      return $location;
    }

    protected function getLocation() : array 
    {
      return GeoIP::getLocation($this->current_ip);
    }

    public function locatable()
    {
        return $this->morphTo();
    }
}
