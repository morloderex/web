<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model,
    App\Models\User,
	App\Models\Category,
	App\Models\Post,
	App\Models\Comment,
	App\Models\Location,
	App\Models\Photo,
	App\Models\Tag,
	App\Models\Taggable,
	App\Models\Testimonial,
	App\Models\Information,
    App\Models\Gallery;

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

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
    	'active'		 =>	$faker->boolean($chanceOfGettingTrue = 90),
        'name' 			 => $faker->name,
        'email' 		 => $faker->safeEmail,
        'password' 		 => Hash::make(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Category::class, function (Faker\Generator $faker) {
	return [
        'user_id'       =>  factory(User::class)->create()->id,
		'visits'	    =>	$faker->numberBetween(5,20),
		'name'		    =>	$faker->title,
		'description'	=>	$faker->catchPhrase,
	];
});


$factory->define(Post::class, function(Faker\Generator $faker){
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
			'active'				=>	$faker->boolean,
			'score'					=>	$faker->numberBetween(0,10),
			'user_id'				=>	$user->id,
      'title'     		=>  $faker->title,
      'description'  	=>  $faker->catchPhrase,
      'body'  				=>  $faker->text
    ];
});

$factory->define(Comment::class, function(Faker\Generator $faker){
    $user = factory(User::class)->create();
    return [
        'user_id'               =>  $user->id,
        'title'     		    =>  $faker->title,
        'body'  				=>  $faker->text
    ];
});

$factory->define(Location::class, function(Faker\Generator $faker){
    return [
			'current_ip'  =>  $faker->ipv6,
			'last_ip'     =>  $faker->ipv4,
			'country'     =>  $faker->countryCode, // @TODO: move this into geoIP resolution
			'city'        =>  $faker->city
    ];
});

$factory->define(Photo::class, function(Faker\Generator $faker){
    $acceptedExtensions = (new Photo())->getAcceptedExtensions();
    $image = $faker->imageUrl(1920, 1080, Null, True, Null, 'http://loremflickr.com');
    return [
        'name' => $image,
        'extension' => $acceptedExtensions[array_rand($acceptedExtensions)]
    ];
});

$factory->define(Information::class, function(Faker\Generator $faker){
    return [
        'title'     =>  $faker->title,
        'synopsis'  =>  $faker->catchPhrase,
        'data'  	=>  $faker->text
    ];
});

$factory->define(Testimonial::class, function(Faker\Generator $faker){
    return [
        'title'     =>  $faker->title,
        'content'   =>  $faker->text
    ];
});

$factory->define(Tag::class, function(Faker\Generator $faker){
    return [
				'tag'			=>	$faker->bs
    ];
});

$factory->define(Taggable::class, function(Faker\Generator $faker){
    $tag = factory(Tag::class)->create();
    return [
        'tag_id'	=>	$tag->id,
        'tag' 		=> 	$tag->tag ?: $faker->bs
    ];
});

$factory->define(Gallery::class, function(Faker\Generator $faker){
    return [
        'name'          =>  $faker->bs,
        'description'   =>  $faker->catchPhrase
    ];
});
