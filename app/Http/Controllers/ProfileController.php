<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileSearchRequest;
use App\Http\Requests\Profile\RegisterRequest;
use App\Http\Resources\Profile\ProfileCollection;
use App\Http\Resources\Profile\ProfileResource;
use App\Library\Profiles\ProfileService;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new ProfileResource($request->user());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, ProfileService $service)
    {
        if($request->search){
            return new ProfileCollection($service->searchProfiles($request->search));
        }
        return new ProfileCollection($service->randomProfiles());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
