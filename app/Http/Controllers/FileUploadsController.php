<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\FileUpload;
use App\PoData;
use Illuminate\Http\Request;

class FileUploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $fileuploads = FileUpload::where('transno','like','%'.$keyword.'%')->latest()->paginate($perPage);
        } else {
            $fileuploads = FileUpload::latest()->paginate($perPage);
        }

        return view('file-uploads.index', compact('fileuploads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('file-uploads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();

        for ($fileloop=1; $fileloop <= 5; $fileloop++) {
            if ($request->hasFile('uploadfile'.$fileloop )) {

                $filenamereal = $request->file('uploadfile' . $fileloop)->getClientOriginalName();

                $uploadfilepath = $request->file('uploadfile' . $fileloop)->storeAs('public/pdf/'. date('Ymd') .'/', $filenamereal);

                $trans = explode(".", $filenamereal);
                $tmp = array();

                $tmp['filename'] = $filenamereal;
                $tmp['serverpath'] = 'storage/pdf/' . date('Ymd') . '/' . $filenamereal;
                $tmp['type'] = 'PDF';
                $tmp['transno'] = $trans[0];
                
                $podata = PoData::where('trans_name', $trans[0])->first();

                if(empty($podata)){
                    $tmp['status'] = 'UPLOADED';
                }else{
                    $tmp['po_data_id'] = $podata->id;
                    $tmp['status'] = 'MAPPED';
                }

                FileUpload::create($tmp);

            }
        }

        

        
        //FileUpload::create($requestData);

        return redirect('file-uploads')->with('flash_message', ' added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $fileupload = FileUpload::findOrFail($id);

        return view('file-uploads.show', compact('fileupload'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $fileupload = FileUpload::findOrFail($id);

        return view('file-uploads.edit', compact('fileupload'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();

        $fileupload = FileUpload::findOrFail($id);
        $fileupload->update($requestData);

        return redirect('file-uploads')->with('flash_message', ' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        FileUpload::destroy($id);

        return redirect('file-uploads')->with('flash_message', ' deleted!');
    }

    public function rematch($id){
        $fileupload = FileUpload::findOrFail($id);
        
        $podata = PoData::where('trans_name', $fileupload->transno)->first();

        if(!empty($podata)){
            $fileupload->po_data_id = $podata->id;
            $fileupload->status = 'MAPPED';
            $fileupload->update();
        }

        return redirect('file-uploads')->with('flash_message', ' updated!');
    }
}
