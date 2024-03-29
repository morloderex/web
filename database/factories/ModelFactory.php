<?php
use Faker\Generator as Faker;

use App\Models\Forum\Category,
    App\Models\Forum\Post,
    App\Models\Forum\Thread;

use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Information;
use App\Models\Location;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\Testimonial;
use App\Models\Emulators\TrinityCore\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
    	'active'		 =>	$faker->boolean($chanceOfGettingTrue = 90),
        'name' 			 => $faker->firstName,
        'email' 		 => $faker->safeEmail,
        'password' 		 => Hash::make(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Character::class, function(Faker $faker){
    return [
        'guid'          =>  $faker->numberBetween(1,2147483647),
        'account'       =>  \App\Models\Emulators\TrinityCore\Account::random()->id,
        'name'          =>  $faker->firstName,
        'race'          =>  $faker->numberBetween(1,22),
        'class'         =>  $faker->numberBetween(1,11),
        'gender'        =>  $faker->numberBetween(0,1),
        'level'         =>  $faker->numberBetween(1,255),
        'money'         =>  $faker->numberBetween(100,1000),
        'position_x'    =>  $faker->numberBetween(10,2000),
        'position_y'    =>  $faker->numberBetween(10,2000),
        'position_z'    =>  $faker->numberBetween(10,200),
        'orientation'   =>  $faker->randomFloat(),
        'map'           =>  $faker->numberBetween(1,2),
        'zone'          =>  $faker->numberBetween(1,20),
        'cinematic'     =>  $faker->boolean
    ];
});

$factory->define(Category::class, function (Faker $faker) {
	return [
		'visits'	        =>	$faker->numberBetween(5,20),
		'title'		        =>	$faker->title,
		'description'	    =>	$faker->catchPhrase,
        'threads_enabled'   =>    $faker->boolean(90)
	];
});


$factory->define(Thread::class, function(Faker $faker){
    return [
        'category_id'   =>  factory(Category::class)->create()->id,
        'author_id'     =>  factory(User::class)->create()->id,
        'title'         =>  $faker->title,
        'pinned'        =>  $faker->numberBetween(0,1),
        'locked'        =>  $faker->numberBetween(0,1),
    ];
});

$factory->define(Post::class, function(Faker $faker){
		$user = User::random();

        if(!$user instanceof User)
        {
            switch ($user) {
                case is_array($user):
                    $user = (object)$user;
                    break;

                case $user instanceof Builder:
                    $user = $user->first();
                    break;
            }
        }

        if(!$user || empty($user))
        {
            $user = factory(User::class)->times(1)->create();
        }

		return [
			'thread_id'             =>  factory(Thread::class)->create()->id,
            'author_id'				=>	$user->id,
            'content'  				=>  $faker->text
    ];
});

$factory->define(Comment::class, function(Faker $faker){
    $user = factory(User::class)->create();
    return [
        'user_id'               =>  $user->id,
        'title'     		    =>  $faker->title,
        'body'  				=>  $faker->text
    ];
});

$factory->define(Location::class, function(Faker $faker){
    return [
			'current_ip'  =>  $faker->ipv6,
			'last_ip'     =>  $faker->ipv4,
			'country'     =>  $faker->countryCode, // @TODO: move this into geoIP resolution
			'city'        =>  $faker->city
    ];
});

$factory->define(Photo::class, function(Faker $faker){
    $acceptedExtensions = (new Photo())->getAcceptedExtensions();
    $image = $faker->imageUrl(1920, 1080, Null, True, Null, 'http://loremflickr.com');
    return [
        'name' => $image,
        'extension' => $acceptedExtensions[array_rand($acceptedExtensions)]
    ];
});

$factory->define(Information::class, function(Faker $faker){
    return [
        'title'     =>  $faker->title,
        'synopsis'  =>  $faker->catchPhrase,
        'data'  	=>  $faker->text
    ];
});

$factory->define(Testimonial::class, function(Faker $faker){
    return [
        'title'     =>  $faker->title,
        'content'   =>  $faker->text
    ];
});

$factory->define(Tag::class, function(Faker $faker){
    return [
				'tag'			=>	$faker->bs
    ];
});

$factory->define(Taggable::class, function(Faker $faker){
    $tag = factory(Tag::class)->create();
    return [
        'tag_id'	=>	$tag->id,
        'tag' 		=> 	$tag->tag ?: $faker->bs
    ];
});

$factory->define(Gallery::class, function(Faker $faker){
    return [
        'name'          =>  $faker->bs,
        'description'   =>  $faker->catchPhrase
    ];
});
