<?php

namespace App\Libraries\Image;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

/**
 * Class ImageHandler
 * @package App\Services
 */
class Handler {

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Image
     */
    protected $image;

    /**
     * @var integer
     */
    protected $lifetime;

    /**
     * @var ImageManager
     */
    protected $manager;

    /**
     * ImageHandler constructor.
     * @param ImageManager $manager
     */
    public function __construct(ImageManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $lifetime
     * @return $this
     */
    public function setLifetime(int $lifetime = 43200)
    {
        $this->lifetime = $lifetime;
        return $this;
    }

    /**
     * @return $this
     */
    public function create()
    {
        $image = $this->manager->cache(function($image){
            $img = $image->make($this->model->path);

            $imagesDirectory = $this->model->getDirectory();

            $target = $this->getTarget();

            if($this->model->isThumbnail)
            {
                $thumbsDirectory = "$imagesDirectory/thumbnails";
                $this->createDirectoryIfNotExists($thumbsDirectory);

                $img->resize(300,200)->save("$thumbsDirectory/$target");
            }

            $this->createDirectoryIfNotExists($imagesDirectory);
            return $img->save("$imagesDirectory/$target");
        },$this->lifetime, $returnImageObject = True);

        $this->setImage($image);

        return $this;
    }

    /**
     * @param string $path
     */
    protected function createDirectoryIfNotExists(string $path)
    {
        $exists = file_exists($path);

        if(!$exists)
            mkdir($path, $mode = 0777, $recursive = True);
    }

    /**
     * @return string
     */
    public function getTarget() : string
    {
        $name = $this->model->getName();
        $ext  = $this->model->extension;

        if(str_contains($name, ['http://', 'https://']))
        {
            $url = parse_url($name);
            $name = $url['query'] ?: $url['path'] ;
        }

        return "$name.$ext";
    }

    /**
     * @return Model
     */
    public function getModel() : Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return ImageHandler
     */
    public function setModel(Model $model) : ImageHandler
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage() : Image
    {
        if(!$this->image)
            $this->create();
        
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return ImageHandler
     */
    public function setImage(Image $image) : ImageHandler
    {
        $this->image = $image;
        return $this;
    }
    
        

}    
