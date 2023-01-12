<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDistributorRequest;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\DeleteDistributorRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateDistributorRequest;
use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use \App\Traits\MyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // login - createDes - logout - deleteDes - updateDes

    public function login(LoginRequest $request)
    {
        $user = User::where('name', $request->name)->first();
        if (isset($user)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth')->plainTextToken;
                $user->token = $token;
                return MyResponse::returnData(
                    'user',
                    $user,
                    "logged in successfully."
                );
            } else {
                return MyResponse::returnError('wrong password.', 200);
            }
        } else {
            return MyResponse::returnError('user not found', 200);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return  MyResponse::returnMessage("logout sucessfully.");
    }
    public function createDistributor(CreateDistributorRequest $request)
    {
        $user = User::create($request->values());
        if ($request->hasFile('avatar_url')) {
            $image = $request->file('avatar_url');
            $ext = $image->getClientOriginalExtension();
            $name = time() . ".$ext";
            $image->storeAs('/public/images/avatars/', $name);
            $user->avatar_url = "/storage/images/avatars/$name";
            $user->save();
        }
        Group::create([
            'owner_id' => $user->id,
            'name' => "group-$user->id",
            "desc" => ""
        ]);
        return MyResponse::returnData('distributor', $user);
    }
    public function deleteDistributor(DeleteDistributorRequest $request)
    {
        $user = User::find($request->values()['id']);
        if ($user->type == 'distributor') {
            $user->delete();
            return MyResponse::returnMessage("$user->name distributor deleted.");
        } else {
            return MyResponse::returnError("user must be distributor.", 401);
        }
    }
    public function updateDistributor(UpdateDistributorRequest $request)
    {
        $user = User::find($request->values()['id']);
        if ($user->type == 'distributor') {
            $user->update($request->values());
            if ($request->hasFile('avatar_url')) {
                $image = $request->file('avatar_url');
                $ext = $image->getClientOriginalExtension();
                $name = time() . ".$ext";
                $image->storeAs('/public/images/avatars/', $name);
                $user->avatar_url = "/storage/images/avatars/$name";
            }
            $user->save();
            return MyResponse::returnMessage("$user->name distributor data updated.");
        } else {
            return MyResponse::returnError("user must be distributor.", 401);
        }
    }
    public function getAllDistributor()
    {
        $dists = User::where('type', 'distributor')->get()->map->formatUser();
        return MyResponse::returnData('distributors', $dists);
    }
    public function createTag(CreateTagRequest $request)
    {
        $tag = Tag::create($request->values());
        return MyResponse::returnData('tag', $tag);
    }
    public function getDistributorById($id)
    {
        $dist = User::find($id);
        if (isset($dist)) {
            if ($dist->type == 'distributor') {

                return MyResponse::returnData("distributor", $dist->format());
            } else {
                return MyResponse::returnError("not a distributor", 401);
            }
        } else {
            return  MyResponse::returnError("distributor not found.", 400);
        }
    }

    public function me(){
        $user = Auth::user();

        return MyResponse::returnData("me",$user->format());
    }
    public function deleteTag(int $id){
        $tag = Tag::find($id);
        if(isset($tag)){
            $tag->delete();
            return MyResponse::returnMessage("tag removed successfully.");
        }else{
            return MyResponse::returnError("tag not found.",404);
        }
    }
}
