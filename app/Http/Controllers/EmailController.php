<?php

namespace App\Http\Controllers;

// use PDF;
use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

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
    // public function index(Request $request) {

    // 	//Create response variable for response message

    // 	//Check validate request
    // 	$validateData = Validator::make($request->all(), [
	//   			'subject'	=> 'bail|required',
	//   			'no_tagihan'=> 'bail|required',
	//   			'name'  	=> 'bail|required',
	//   			'email'		=> 'bail|required|email',
	//   		]);

    // 	//Get all error in validate request
    // 	$errors = $validateData->errors();

    // 	//if the number of errors is more than zero
    // 	if (!(count($errors->all()) > 0 )) {

    // 		//Enter data request to variable array data
	//   		$data = array(
	//   			'subject'	=> $request->subject,
	//   			'no_tagihan'=> $request->no_tagihan,
	//   			'email'		=> $request->email,
	//   			'name'	=> $request->name,
	//   			//Send Request is send_feedback
	//   			'request'	=> 'send'
	//   		);

	//   		//Try to send Email
	//   		try {
	//   			//Send Email with model of email SendEmail and with variable data
	//   			$mail = Mail::send(new SendMail($data));

	//   			//Check if sending email failure
	// 	  		if (!Mail::failures()) {
	// 	  			//Give response message success if success to send email
	// 	  			response()->json(['Message' => 'Success']);
	// 	  		} else {
	// 	  			//Give response message failed if failed to send email
	// 	  			response()->json(['Message' => 'Failed']);
	// 	  		}

    //             return response()->json($mail);

	//   		} catch (Exception $e) {
	//   			//Give response message error if failed to send email
	//   			response()->json([$e->getMessage()]);
	//   		}

    // 	} else {

    // 		//Give response message error if the number of errors more than zero
    // 		foreach ($errors->all() as $e) {
    //             response()->json('Message' == $e);
	//   		}

    //         response()->json('Message' == 'All Input Cannot Be Empty!');
    // 	}


    // }
    public function index(Request $request)
    {
        $data = [
                "email" => $request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "address" => $request->address,
                "no_tagihan" => $request->no_tagihan,
                "jumlah_tagihan" => $request->jumlah_tagihan,
                "jatuh_tempo" => $request->jatuh_tempo,
                "produk" => $request->produk,
        ];

        Log::info($data);

        $pdf = PDF::loadView('emails.invoice', $data);
        $pdf->setPaper('A4', 'landscape');

        try {
            $mail = Mail::send('emails.mail', $data, function($message) use ($data, $pdf) {
                $message->to($data["email"], $data["firstname"])
                ->subject('Reminder')
                ->attachData($pdf->output(), "Invoice.pdf");
            });
            if ($mail) {
                return response('Success');
            } else {
                return response('Failed');
            }
        } catch (Exception $e) {
            response()->json([$e->getMessage()]);
        }
    }
    public function pdf(Request $request)
    {
        $data = [
                "email" => $request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "address" => $request->address,
                "no_tagihan" => $request->no_tagihan,
                "jumlah_tagihan" => $request->jumlah_tagihan,
                "jatuh_tempo" => $request->jatuh_tempo,
                "produk" => $request->produk
        ];

        try {
            $pdf = PDF::loadView('emails.invoice', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('invoice.pdf');
        } catch (\Exception $e) {
            error_log($e->getMessage()); // Menampilkan error ke log
            // Lakukan tindakan lain jika diperlukan, seperti memberikan tanggapan error ke pengguna
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
    public function dom(){
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];

        $pdf = PDF::loadView('emails.index', $data);

        return $pdf->stream('example.pdf');
    }
    public function sendInvoice()
    {
        $data = [
            'email' => 'contoh@email.com',
            'subject' => 'Invoice',
            // tambahkan data lain yang dibutuhkan untuk view dan email
        ];

        // Generate PDF
        $pdf = PDF::loadView('emails.index', $data);

        // Kirim email dengan lampiran PDF
        Mail::to($data['email'])->send(new SendMail($data, $pdf));

        return "Invoice berhasil dikirim!";
    }
    public function invoice(){
        $transaksi = Transaksi::with('user')->get();
        foreach($transaksi as $tr){
            $data = [
                "email" => $tr->user->email,
                "firstname" => $tr->user->firstname,
                "lastname" => $tr->user->lastname,
                "address" => $tr->user->address,
                "no_tagihan" => $tr->no_tagihan,
                "produk" => $tr->produk,
                "jumlah_tagihan" => $tr->jumlah_tagihan,
                "jatuh_tempo" => $tr->jatuh_tempo,
            ];
            return view('emails.invoice', $data);
        }
    }
    public function remind(){
        $transaksi = Transaksi::where(function($query) {
            $query->where('status_tagihan', 'Belum Bayar')
                ->where(function($query) {
                    $query->whereDate('jatuh_tempo', Carbon::today())
                        ->orWhereDate('jatuh_tempo', '<=', Carbon::today()->addDay(3))
                        ->orWhereDate('jatuh_tempo', '<=', Carbon::today()->addDay(7));
                });
        })
        ->with('pelanggan')
        ->with('user')
        ->with('transaksi_produk.produk')
        ->get();

        $data = $transaksi->map(function($item) {
            return [
                "email" => $item->user->email,
                "firstname" => $item->user->firstname,
                "lastname" => $item->user->lastname,
                "address" => $item->user->address,
                "no_tagihan" => $item->no_tagihan,
                "jumlah_tagihan" => $item->jumlah_tagihan,
                "jatuh_tempo" => $item->jatuh_tempo,
                "produk" => $item->transaksi_produk->toArray(),
            ];
        })->toArray();

        foreach ($data as $datum) {
            dd($datum);
        }
    }
}
