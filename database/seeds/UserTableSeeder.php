<?php

use App\Information;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 User records
        // Keep in mind, when called by UserTest, a database Migration is called
        // So these records will be removed after the test.
        $users = factory(User::class)->times(10)->create();

        $this->saveRelationship($users, 'information');
    }

    protected function saveRelationship(Collection $models, string $method) {
        $models->each(function($model) use($method) {
            $target = $model->$method();
            $data = $this->$method();

            if(is_array($data) || $data instanceof Collection)
            {
                foreach ($data as $relation) {
                    $target->save($relation);        
                }
                
            } else {
                $target->save($data);
            }
        });
    }

    protected function Information()
    {
        return factory(Information::class)->times(2)->make();
    }
}
