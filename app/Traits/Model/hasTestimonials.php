<?php

namespace App\Traits\Model;


use App\Testimonial;

trait hasTestimonials
{
    public function testimonials()
    {
        return $this->morphMany(Testimonial::class, 'testimoniable');
    }
}