<?php

/**
 * @TODO: Split into multiple smaller traits
 */

namespace App\Traits\Model;


use App\Libraries\Image\Handler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Intervention\Image\CachedImage,
    Intervention\Image\Image;

trait handlesImages
{
    /**
     * @var bool
     */
    public $isThumbnail = True;

    /**
     * @var string
     */
    public $baseOutputDirectory = 'images';

    /**
     * @var ImageHandler
     */
    protected $handler;
    
    /**
     * @var array
     */
    protected $acceptedExtensions = [
        'jpeg', 'jpg', 'png'
    ];

    /**
     * Default image extension
     * 
     * @var string
     */
    protected $extension = 'jpeg';

    /**
     * @inheritdoc
     */
    public static function boot()
    {
        static::saving(function($model){
            $model->createImage($model);
        });

        static::updating(function($model){
            $model->createImage($model);
        });


        static::deleting(function($model){
            $model->createImage()->destroy();
        });
    }
    
    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler ?: $this->setHandler(
            app()->make(ImageHandler::class)
        )->handler;
    }

    /**
     * @param mixed $handler
     */
    public function setHandler(ImageHandler $handler)
    {
        $handler->setModel($this);
        $this->handler = $handler;
        return $this;
    }

    /**
     * @param \Intervention\Image\Image $image
     * @return Model
     */
    public function setImage(\Intervention\Image\Image $image) : Model
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param int $quality
     * @return string
     */
    public function getImage(int $quality = 90) : string
    {
        $this->isThumbnail = False;
        return $this->encodeImage($quality);
    }

    /**
     * @param int $quality
     * @return string
     */
    public function getThumbnail(int $quality = 75) : string
    {
        $this->isThumbnail = True;
        return $this->encodeImage($quality);
    }

    /**
     * @return string
     */
    public function getThumbnailAttribute() : string
    {
        $directory = $this->getDirectory();
        $directory = "$directory/thumbnails";

        $name = $this->getName($withExtension = True);
        return "$directory/$name";
    }

    /**
     * Processes the Image using Intervention/Image
     *
     * @param int $quality
     * @return string
     */
    protected function encodeImage(int $quality) : string
    {
        $model = $this->image ?: $this->createImage($this);

        switch ($model)
        {
            case $model instanceof CachedImage || $model instanceof Image:
                $image = $model;
            break;

            case is_resource($model) || is_string($model):
                $image = $model;
            break;

            case $model instanceof Model && trait_exists(handlesImages::class):
                $image = $model->getHandler()->getImage();
            break;
        }

        if(!isset($image))
        {
            Log::debug("unresolved Image Object: $model");
            abort(500);
        }
        
        return $image->encode($this->extension, $quality);
    }

    /**
     * @return string
     */
    public function getDirectory() : string
    {
        $imageAble = $this->getImageAbleShortName();
        $baseDirectory = $this->baseOutputDirectory;
        $directory = $imageAble ? "$baseDirectory/$imageAble" : $baseDirectory;

        return storage_path($directory);
    }

    /**
     * @param bool $withExtension
     * @return string
     */
    public function getName($withExtension = False) : string
    {
        $name      = $this->name;
        $extension = $this->extension;

        if($withExtension)
        {
            if(str_contains($name, $this->acceptedExtensions))
                return $name;

            return "$name.$extension";
        }

        return $name;
    }


    /**
     * @param bool $fullPath
     * @return string
     */
    public function getPath($fullPath = False) : string
    {
        $filename  = $this->getName($withExtension = True);

        if(str_contains($filename, ['http://', 'https://']))
            return $filename;

        $directory = $this->getDirectory();

        return $fullPath ? "$directory/$filename" : $filename;
    }

    /**
     * @return string
     */
    public function getPathAttribute() : string
    {
        return $this->getPath($fullPath = True);
    }

    /**
     * @param Model $Model
     * @param int $lifetime
     * @return Model
     */
    protected function createImage(Model $model, int $lifetime = 43200) : Model
    {
        $handler = $this->getHandler();
        $handler
            ->setModel($model)
            ->setLifetime($lifetime)
            ->create();

        $this->setImage(
            $handler->getImage()
        );

        $this->setHandler($handler);
        return $this;
    }

    /**
     * @return string|Null
     */
    protected function getImageAbleShortName()
    {
        if($imageable = $this->getAttribute('imageable_type'))
            return (new \ReflectionClass($imageable))->getShortName();

        return Null;
    }


    /**
     * @param $image
     * @return Model
     */
    public function setImageAttribute($image) : Model
    {
        if(!is_object($image) && !$image instanceof \Intervention\Image\Image)
            return Null;

        $this->setImage($image);
        return $this;
    }

    /**
     * @return array
     */
    public function getAcceptedExtensions()
    {
        return $this->acceptedExtensions;
    }

    /**
     * @param array $acceptedExtensions
     */
    public function setAcceptedExtensions(array $acceptedExtensions)
    {
        $this->acceptedExtensions = $acceptedExtensions;
    }

    /**
     * @param string $Extension
     * @return Model
     */
    public function setExtensionAttribute(string $Extension) : Model
    {
        if(!in_array($Extension, $this->acceptedExtensions))
            abort(400, "invalid image Extension: $Extension");

        $this->attributes['extension'] = $Extension;

        return $this;
    }

    /**
     * @param string $name
     * @return Model
     */
    public function setNameAttribute(string $name) : Model
    {
        if(str_contains($name, ' '))
            $name = str_slug($name);

        $name = is_array($name) ? implode('-', $name) : $name;
        $this->attributes['name'] = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseOutputDirectory()
    {
        return $this->baseOutputDirectory;
    }

    /**
     * @param string $baseOutputDirectory
     * @return Model
     */
    public function setBaseOutputDirectory($baseOutputDirectory)
    {
        $this->baseOutputDirectory = $baseOutputDirectory;
        return $this;
    }

}