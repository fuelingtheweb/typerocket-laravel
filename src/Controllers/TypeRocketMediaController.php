<?php

namespace TypeRocket\Controllers;

use TypeRocket\Media;
use TypeRocket\Form;
use TypeRocket\MediaProcesses\ImageProcess;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class TypeRocketMediaController extends Controller
{

    /**
     * Runs top to bottom
     *
     * @var array
     */
    protected $processors = [
        \TypeRocket\MediaProcesses\Setup::class,
        \TypeRocket\MediaProcesses\LocalStorage::class
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!empty($_GET['search'])) {
            $media = Media::orderBy('id', 'desc')->where('caption', 'like', '%' . $_GET['search'] . '%')->paginate(35);
        } else {
            $media = Media::orderBy('id', 'desc')->paginate(35);
        }

        return view('typerocket.media.index', ['media' => $media]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jfeed()
    {

        if(!empty($_GET['search'])) {
            $media = Media::orderBy('id', 'desc')->where('caption', 'like', '%' . $_GET['search'] . '%')->paginate(35);
        } else {
            $media = Media::orderBy('id', 'desc')->paginate(35);
        }

        return $media;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = new Form('Media', 'create', null, '/media');
        return view('typerocket.media.create', ['form' => $form]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');

        if( !empty($file) ) {
            $media = new Media();
            foreach($this->processors as $class) {
                /** @var $imageProcess ImageProcess */
                $imageProcess = new $class();
                $imageProcess->run($file, $media);
            }
            $media->save();
        }

        return redirect()->route('media.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $form = new Form('Media', 'update', $id, '/media/' . $id);
        $form->setRequest($request);
        return view('media.edit', ['form' => $form]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tr = (object) $request->input('tr');

        $media = Media::findOrFail($id);
        $media->alt = $tr->alt;
        $media->caption = $tr->caption;
        $media->save();

        return redirect()->route('media.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        foreach($this->processors as $class) {
            /** @var $imageProcess ImageProcess */
            $imageProcess = new $class();
            $imageProcess->down($media);
        }
        $media->delete();

        return redirect()->route('media.index');
    }
}