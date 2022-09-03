<?php

namespace App\Http\Controllers;

use App\Datatables\UserDatatable;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDatatable $userDatatable)
    {
        return $userDatatable->render('users.index');
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
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        return view('users.profile', compact('user'));
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
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return response()->json([], 400);
        }

        $this->userRepository->destroy($id);

        return response()->json([], 200);
    }

    public function active($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return response()->json([], 400);
        }

        try {
            $input = [
                'active' => ($user->active) ? false : true
            ];

            $user = $this->userRepository->update($id, $input);

        } catch (ValidatorException $e) {
            return response()->json([], 400);
        }

        return response()->json(['active' => ($user->active) ? 1 : 0], 200);
    }
}
