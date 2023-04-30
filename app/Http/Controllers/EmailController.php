<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

// class EmailController extends Controller
// {
//     public function index()
//     {
//         $data = [
//             'subject' => 'Mail',
//             'body' => 'Hello Word'
//         ];
//         try {
//             Mail::to('saktialfansyahp@gmail.com')->send(new SendMail($data));
//             return response()->json(['Success']);
//         } catch (Exception $e) {
//             return response()->json($e);
//         }
//     }
// }

class EmailController extends Controller
{
    public function mail(){
        return view('emails.index');
    }
    public function index(Request $request) {

    	//Create response variable for response message

    	//Check validate request
    	$validateData = Validator::make($request->all(), [
	  			'subject'	=> 'bail|required',
	  			'no_tagihan'=> 'bail|required',
	  			'name'  	=> 'bail|required',
	  			'email'		=> 'bail|required|email',
	  		]);

    	//Get all error in validate request
    	$errors = $validateData->errors();

    	//if the number of errors is more than zero
    	if (!(count($errors->all()) > 0 )) {

    		//Enter data request to variable array data
	  		$data = array(
	  			'subject'	=> $request->subject,
	  			'no_tagihan'=> $request->no_tagihan,
	  			'email'		=> $request->email,
	  			'name'	=> $request->name,
	  			//Send Request is send_feedback
	  			'request'	=> 'send'
	  		);

	  		//Try to send Email
	  		try {
	  			//Send Email with model of email SendEmail and with variable data
	  			$mail = Mail::to('example@gmail.com')->send(new SendMail($data));

	  			//Check if sending email failure
		  		if (!Mail::failures()) {
		  			//Give response message success if success to send email
		  			response()->json(['Message' => 'Success']);
		  		} else {
		  			//Give response message failed if failed to send email
		  			response()->json(['Message' => 'Failed']);
		  		}

                return response()->json($mail);

	  		} catch (Exception $e) {
	  			//Give response message error if failed to send email
	  			response()->json([$e->getMessage()]);
	  		}

    	} else {

    		//Give response message error if the number of errors more than zero
    		foreach ($errors->all() as $e) {
                response()->json('Message' == $e);
	  		}

            response()->json('Message' == 'All Input Cannot Be Empty!');
    	}


    }
    public function reminder() {
        $transaksi = Transaksi::where('jatuh_tempo', Carbon::today())->where('status_tagihan', 'Belum Bayar')->get();

        $data = array(
            'subject'	=> $transaksi->subject,
            'no_tagihan'=> $transaksi->no_tagihan,
            'email'		=> $transaksi->email,
            'name'	=> $transaksi->name,
            //Send Request is send_feedback
            'request'	=> 'send'
        );

    	try {
            //Send Email with model of email SendEmail and with variable data
            Mail::to('example@gmail.com')->send(new SendMail($data));

            //Check if sending email failure
            if (!Mail::failures()) {
                //Give response message success if success to send email
                response()->json(['Message' => 'Success']);
            } else {
                //Give response message failed if failed to send email
                response()->json(['Message' => 'Failed']);
            }

        } catch (Exception $e) {
            //Give response message error if failed to send email
            response()->json([$e->getMessage()]);
        }
    }
}
