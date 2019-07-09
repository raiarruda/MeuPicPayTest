<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MoneyValidationForm;
use App\Models\Balance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;

class BalanceController extends Controller
{

    public function  index(){
    	//user logado
	    $balance = auth()->user()->balance;
    	$amount = $balance? $balance->amount : 0;
    	return view('admin.balance.index', compact('amount'));
    }
    public function deposit(){
	    // dd(auth()->user()->balance()->get());

	    return view('admin.balance.deposit');
    }
    public function withdraw(){

	    return view('admin.balance.withdraw');
    }
    public function depositStore(MoneyValidationForm $request){
	    $balance = auth()->user()->balance()->firstOrCreate([]);
		$response = $balance->deposit($request->valor);
		if($response['success']) {
			echo "oi";

			return redirect()
				->route('admin.balance')
				->with('success', $response['message']);
			}

		return redirect()
				->back()
				->with('error', $response['message']);

    }

    public function withdrawStore(MoneyValidationForm $request){

	    $balance = auth()->user()->balance()->firstOrCreate([]);
		$response = $balance->withdraw($request->valor);


		if($response['success']) {
			echo "oi";

			return redirect()
				->route('admin.balance')
				->with('success', $response['message']);
			}

		return redirect()
				->back()
				->with('error', $response['message']);

    }
}
