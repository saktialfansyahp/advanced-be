<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    public function index(){
        $currentTime = new DateTime();
        $time = $currentTime->format("H:i");
        $status = Status::all();

        return response($status);
    }
    public function byName($name){
        $status = Status::where('name', $name)->first();

        return response($status);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'time' => 'required|date_format:H:i',
            'daystoadd1' => 'required|integer',
            'daystoadd2' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $status = Status::create([
            'name' => $request->name,
            'active' => $request->active,
            'time' => $request->time,
            'daystoadd1' => $request->daystoadd1,
            'daystoadd2' => $request->daystoadd2,
        ]);

        return response($status);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'time' => 'required|date_format:H:i',
            'daystoadd1' => 'required|integer',
            'daystoadd2' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $status = Status::where('name', $request->name)->first();

        $status->update([
            'active' => $request->active,
            'time' => $request->time,
            'daystoadd1' => $request->daystoadd1,
            'daystoadd2' => $request->daystoadd2,
        ]);

        // $data = $request;


        return response($status);
    }
}
