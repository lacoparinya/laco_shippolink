<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SapDataCf;
use Illuminate\Http\Request;

class SapDataCfsController extends Controller
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
            $sapdatacfs = SapDataCf::latest()->paginate($perPage);
        } else {
            $sapdatacfs = SapDataCf::latest()->paginate($perPage);
        }

        return view('sap-data-cfs.index', compact('sapdatacfs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('sap-data-cfs.create');
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

        SapDataCf::create($requestData);

        return redirect('sap-data-cfs')->with('flash_message', ' added!');
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
        $sapdatacf = SapDataCf::findOrFail($id);

        return view('sap-data-cfs.show', compact('sapdatacf'));
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
        $sapdatacf = SapDataCf::findOrFail($id);

        return view('sap-data-cfs.edit', compact('sapdatacf'));
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

        $sapdatacf = SapDataCf::findOrFail($id);
        $sapdatacf->update($requestData);

        return redirect('sap-data-cfs')->with('flash_message', ' updated!');
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
        SapDataCf::destroy($id);

        return redirect('sap-data-cfs')->with('flash_message', ' deleted!');
    }
}
