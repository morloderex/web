<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracasts\Flash\Flash;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::count();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('photos.upload');
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

        $photo = new Photo($request->all());

        $this->authorize($method, $photo);

        $saved = $photo->save();

        $this->handleStatus($saved, $method);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return view('photos.display', compact('photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        return view('photos.edit', compact('photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $method = __FUNCTION__;

        $this->authorize($method, $photo);

        $updated = $photo->update($request->all());

        $this->handleStatus($updated, $method);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $method = __FUNCTION__;

        $this->authorize($method, $photo);

        $destroyed = $photo->delete();

        $this->handleStatus($destroyed, $method);
    }

    protected function handleStatus(bool $status, string $method)
    {
        if( $status === False )
        {
            $this->flashErrorAndRedirectBack($method);
        }

        Flash::success('Photo' . str_plural($method) . 'successfully.');
        return redirect()->route('gallery.index');
    }


    protected function flashErrorAndRedirectBack(string $method)
    {
        Flash::error('Something went wrong while '.str_plural($method). 'gallery.');
        return redirect()->back();
    }
}
