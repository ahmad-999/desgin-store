<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDesignRequest;
use App\Http\Requests\DeleteDesignRequest;
use App\Http\Requests\UpdateDesginRequest;
use App\Models\Desgin;
use App\Models\DesginTag;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\Request;
use \App\Traits\MyResponse;
use \App\Traits\Constance;
use Illuminate\Support\Facades\Auth;

class DesginController extends Controller
{
    public function createDesgin(CreateDesignRequest $request)
    {
        $storageDir = Constance::getMyStorageDir($request);
        info($request->all());
        $user = Auth::user();
        $group = Group::where('owner_id',$user->id)->first();
        $design = Desgin::create($request->values());
        $design->group_id = $group->id;
        $design->save();
        // $group = Group::find($request->values()['group_id']);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $name = time() . ".$ext";
            $image->storeAs("/public/images/groups/$group->name", $name);
            $design->url = "$storageDir/images/groups/$group->name/$name";
            $design->save();
        }
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $ext = $video->getClientOriginalExtension();
            $name = (time()+ 1) . ".$ext";
            $video->storeAs("/public/videos/groups/$group->name", $name);
            $design->video_url = "$storageDir/videos/groups/$group->name/$name";
            info($design->video_url);
            $design->save();
        }
        
        $tags = Tag::whereIn('id', $request->tags)->get();

        for ($i = 0; $i < count($tags); $i++) {
            DesginTag::create([
                'tag_id' => $tags[$i]->id,
                'desgin_id' =>  $design->id,
            ]);
        }
        return MyResponse::returnData('design', $design);
    }
    public function deleteDesgin(DeleteDesignRequest $request)
    {
        $design = Desgin::find($request->id);
        
        
        if(isset($design->url)){
            $path = Constance::getMyFilePath($request,$design->url);
            $isDeleted = unlink($path);
            info("design image $design->name : $isDeleted");
        }
        if(isset($design->video_url)){
            $path = Constance::getMyFilePath($request,$design->video_url);
            $isDeleted = unlink($path);
            info("design video $design->name : $isDeleted");
        }
        
        $design->delete();
        return MyResponse::returnMessage("design $design->name deleted");
    }
    public function updateDesgin(UpdateDesginRequest $request)
    {
        $design = Desgin::find($request->id);
        $design->update($request->values());
        $group = Group::find($design->group_id);
        $storageDir = Constance::getMyStorageDir($request);
        if ($request->hasFile('image')) {
            $isDeleted = unlink("/home/laserstars/public_html".$design->url);
            info("design image $design->name : $isDeleted");
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $name = time() . ".$ext";
           
            $image->storeAs("/public/images/groups/$group->name", $name);
            $design->url = "$storageDir/images/groups/$group->name/$name";
            $design->save();
        }
        if ($request->hasFile('video')) {
            $isDeleted = unlink("/home/laserstars/public_html".$design->video_url);
            info("design video $design->name : $isDeleted");
            $video = $request->file('video');
            $ext = $video->getClientOriginalExtension();
            $name = time() . ".$ext";
            $video->storeAs("/public/videos/groups/$group->name", $name);
            $design->url = "$storageDir/videos/groups/$group->name/$name";
            $design->save();
        }
        
        $desginTag = DesginTag::where('desgin_id', $design->id)->get();
        for ($i = 0; $i < count($desginTag); $i++) {
            $desginTag[$i]->delete();
        }
        $tags = Tag::whereIn('id', $request->tags)->get();

        for ($i = 0; $i < count($tags); $i++) {
            DesginTag::create([
                'tag_id' => $tags[$i]->id,
                'desgin_id' =>  $design->id,
            ]);
        }
        return MyResponse::returnMessage("design updated succesfully.");
    }
    public function updateDesginTags(UpdateDesginRequest $request)
    {
        
        $design = Desgin::find($request->id);
        $desginTag = DesginTag::where('desgin_id', $design->id)->get();
        for ($i = 0; $i < count($desginTag); $i++) {
            $desginTag[$i]->delete();
        }
        $tags = Tag::whereIn('id', $request->tags)->get();

        for ($i = 0; $i < count($tags); $i++) {
            DesginTag::create([
                'tag_id' => $tags[$i]->id,
                'desgin_id' =>  $design->id,
            ]);
        }
        return MyResponse::returnMessage("design tags updated succesfully.");
    }
    public function getDesignsByTags(Request $request)
    {
        $tags = $request->tags;
        $designs = Desgin::with('tags')->get()->filter(function ($desgin) use ($tags) {

            $dTags = $desgin->tags;
            $tagsIds = [];
            for ($i = 0; $i < count($dTags); $i++) {
                $tag = $dTags[$i];
                array_push($tagsIds, $tag->tag_id);
            }

            for ($i = 0; $i < count($tags); $i++) {
                $tag = $tags[$i];
                if (!in_array($tag, $tagsIds)) return false;
            }
            return true;
        })->values()->map->format();
        return MyResponse::returnData("designs", $designs);
    }
    public function getAllDesigns()
    {
        return MyResponse::returnData("designs", Desgin::orderBy("created_at", "DESC")->get()->map->formatWithOwner());
    }
    public function getMyDesigns(Request $request)
    {
        $user = Auth::user();

        $group = $user->group;
        $designs = Desgin::where('group_id', $group->id)->orderBy("created_at", "DESC")->get()->map->formatWithOwner();
        return MyResponse::returnData("designs", $designs);
    }
    public function getAllTags(){
        $tags = Tag::all();
        return MyResponse::returnData("tags",$tags);
    }
}
