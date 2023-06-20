<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ReminderController extends Controller
{
    public function reminder(){
        try {
            Artisan::call('reminder:whatsapp');
            Artisan::call('reminder:email');
        } catch (\Throwable $th) {
            echo $th;
        }
    }
}
