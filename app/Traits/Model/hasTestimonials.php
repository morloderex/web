<?php

namespace App\Traits\Model;


use App\Models\Testimonial;

trait hasTestimonials
{
    public function testimonials()
    {
        return $this->morphMany(Testimonial::class, 'testimoniable');
    }
}