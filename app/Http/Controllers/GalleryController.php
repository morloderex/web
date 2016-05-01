<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracasts\Flash\Flash;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::all();
        return view('gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $method = __FUNCTION__;

        $gallery = new Gallery($request->all());
        $this->authorize($method, $gallery);

        $saved = $gallery->save();

        if( !  $saved )
        {
            $this->flashErrorAndRedirectBack($method);
        }

        Flash::success('Gallery created!');
        return redirect()->route('gallery.edit', $gallery->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        $gallery = $this->getPopulatedGallery($gallery);

        $photos = $gallery->photos()->paginate(15);
        
        return view('gallery.show', compact('gallery', 'photos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        $gallery = $this->getPopulatedGallery($gallery);

        return view('gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        $method = __FUNCTION__;

        $this->authorize($method, $gallery);

        $updated = $gallery->update($request->all());

        if( ! $updated )
        {
            $this->flashErrorAndRedirectBack($method);
        }

        Flash::success('Gallery updated!');
        return redirect()->route('gallery.show', $gallery->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $method = __FUNCTION__;
        
        $this->authorize($method, $gallery);

        $destroyed = $gallery->delete();

        if( ! $destroyed )
        {
            $this->flashErrorAndRedirectBack($method);
        }

        Flash::success('gallery destroyed.');
        return redirect()->route('gallery.index');
    }

    protected function getPopulatedGallery(Gallery $gallery)
    {
        return $gallery->load('Photos');
    }

    protected function flashErrorAndRedirectBack(string $method)
    {
        Flash::error('Something went wrong while '.str_plural($method). 'gallery.');
        return redirect()->back();
    }
}