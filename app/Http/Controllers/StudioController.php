<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Datatables\StudioDataTable;
use App\Repositories\Studio\StudioRepository;
use App\Repositories\Upload\UploadRepository;

class StudioController extends Controller
{
    protected $studioRepository;
    protected $uploadRepository;

    public function __construct(StudioRepository $studioRepository, UploadRepository $uploadRepository)
    {
        $this->studioRepository = $studioRepository;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudioDataTable $dataTable)
    {
        return $dataTable->render('studios.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studio = $this->studioRepository->find($id);

        if (empty($studio)) {
            Flash::error('Studio not found');

            return redirect(route('studio.index'));
        }

        return view('studios.show', compact('studio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $studio = $this->studioRepository->find($id);

        if (empty($studio)) {
            Flash::error('Studio not found');

            return redirect(route('studio.index'));
        }

        $studio->delete();

        Flash::success('Studio removed successfully.');

        return view('studios.index');
    }

    /**
     * active a studio
     *
     * @param [type] $id
     * @return void
     */
    public function active($id)
    {
        $studio = $this->studioRepository->find($id);

        if (empty($studio)) {
            Flash::error('Studio not found');

            return redirect(route('studios.index'));
        }

        try {
            $studio->status = Studio::OPEN;
            $studio->save();
            Flash::success('Studio active successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Cancel a studio
     *
     * @param [type] $id
     * @return void
     */
    public function cancel($id)
    {
        $studio = $this->studioRepository->find($id);

        if (empty($studio)) {
            Flash::error('Studio not found');

            return redirect(route('studios.index'));
        }

        try {
            $studio->status = Studio::CLOSE;
            $studio->save();
            Flash::success('Studio cancel successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }

        return redirect()->back();
    }
}
