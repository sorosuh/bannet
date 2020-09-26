<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\api\v1\mediaStoreRequest;
use App\Http\Requests\api\v1\mediaEditRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\folder;
use App\media;
use App\User;
use App\option;
use File;


class mediaController extends Controller
{
    protected $user;
    protected $log;

    public function __construct(Request $request)
    {
        $this->log = [];
        if (isset($request->token)) {
            $this->user = JWTAuth::parseToken()->authenticate();
        }
    }

    public function store(Request $request)
{
    //dd($request->all());
    $option = option::find(1);  //id=1 is for validator
    $value = json_decode($option->option_value);
    $validatedData = $request->validate([
        'media' => 'required | mimes:' . $value->mime,
    ]);

    try {
        $file   = $request->file('media');
        $format = $file->guessClientExtension();
        $name   = time() . '.' . $format;

        $dir = folder::where('size', '<=', 1000)->first();
        if ($dir == NULL) {
            $dir = $this->generateDIR();
        }

        $media = new media;
        $media->name    = $name;
        $media->owner   = $request->owner;
        $media->kind    = (isset($request->kind)) ? $request->kind : 1;    //zero value means that it's for product
        $media->main    = (isset($request->main)) ? $request->main : 0;
        if($media->main == 1 ){
            $n = DB::table('media')
                ->where('owner',$media->owner)
                ->where('kind',$media->kind)
                ->where('main',1)
                ->count();

            if($n>=1){
                return response()->json([
                    'message'=>'there is a main picture for this owner'
                ]);
            }
        }

        $file->move('public/files/' . $dir->name, $name);
        ($request->alt != NULL) ? $media->alt = $request->alt : $media->alt = $name;
        $media->folder_id = $dir->id;
        $dir->size++;
        $dir->save();
        $media->save();

        return response()->json([
            'status' => 'file stored',
        ]);

    } catch (exeption $e) {
        return $e->getMessage();
    }
}

    public function generateDIR()
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < 3; $i++) {    //generate random string
            $key .= $keys[array_rand($keys)];
        }

        $dir        = new folder;
        $dir->name  = hash('md5', $key);
        $dir->size  = 0;
        $dir->save();
        return $dir;
    }

    public function removing_logs($db, $file, $path) {
        if ($db === true && $file === true) {
            array_push($this->log, "the file which located on $path has been eliminated correctly");

        } else if ($db === true && $file !== true) {
            array_push($this->log, "the file which located on $path has been eliminated from the database, but couldn't removed from the directory");

        } else if ($db !== true && $file === true) {
            array_push($this->log, "the file which located on $path has been eliminated from the path, but couldn't removed from the database");

        } else {
            array_push($this->log, "OPS, the file which located on $path doesn't exist or something has been wrong !!");

        }
    }

    public function Delete(Request $request)
    {
        if ($request->all == 'true') {

            $all = DB::table('media')
                ->where('owner', $request->owner)
                ->where('kind', $request->kind)
                ->get();

            foreach ($all as $a) {
                $db     = media::find($a->id)->delete();
                $folder = folder::findOrFail($a->folder_id);
                $path   = 'public/files/' . $folder->name . '/' . $a->name;
                $unlink = unlink($path);
                $folder->size--;
                $folder->save();

                $this->removing_logs($db, $unlink, $path);

            }
            return response()->json([
                'log'       => $this->log,
                'message'   => 'success'
            ]);
        } elseif ($request->all == 'false') {
            $media  = media::find($request->id);
            $folder = folder::findOrFail($media->folder_id);
            $path   = 'public/files/' . $folder->name . "/" . $media->name;
            $unlink = unlink($path);
            $media->delete();
            $folder->size--;
            $folder->save();

            $this->removing_logs($media, $unlink, $path);

            return response()->json([
                'log'       => $this->log,
                'message'   => 'success'
            ]);
        }
    }

    public function show(Request $request)
    {
        $kind   = $request->kind;
        $owner  = $request->owner;

        if (isset($request->main)) {
            $media = media::where('kind', $kind)
                            ->where('owner', $owner)
                            ->where('main', $request->main)
                            ->get();
        } else {
            $media = media::where('kind', $kind)
                            ->where('owner', $owner)
                            ->get();
        }

        $media = json_decode($media);

        foreach ($media as $m) {
            $src    = env('APP_URL') . 'public/files/' . media::find($m->id)->folder->name . "/" . $m->name;
            $m->src = $src;
        }
        return response()->json([
            'media' => $media,
        ]);
    }

    public function edit(Request $request)
    {
        $option = option::find(1);  //id=1 is for validator
        $value = (array)json_decode($option->option_value);

        $validatedData = $request->validate([
            'kind'  => 'nullable | integer | max:' . $value['max'],
            'alt'   => 'nullable'
        ]);

        $mediaId     = $request->id;
        $media       = media::findORFail($mediaId);

        $media->owner= (isset($request->owner)) ? $request->owner : $media->owner;
        $media->alt  = (isset($request->alt)) ? $request->alt : $media->alt;
        $media->kind = (isset($request->kind)) ? $request->kind : $media->kind;
        if( isset($request->main)  && $request->main == 1 ){
            $n = DB::table('media')
                ->where('owner',$media->owner)
                ->where('kind',$media->kind)
                ->where('main',1)
                ->count();

            if($n>=1){
                return response()->json([
                    'message'=>'there is a main picture for this owner'
                ]);
            }else{
                $media->main = $request->main;
            }
        }
        if ($media->save()) {
            return response()->json([
                'message' => 'media edited'
            ]);
        } else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }
}
