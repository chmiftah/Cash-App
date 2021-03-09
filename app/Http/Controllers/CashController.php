<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashResource;
use App\Models\Cash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CashController extends Controller
{
  
  public function index(){
     
   $from = request('from');
   $to = request('to');
   if($from && $to){

    $debit = $this->getBalance($from, $to, ">=");
    $credit = $this->getBalance($from,$to, "<" );
    $transaction = Auth::user()->cashes() ->whereBetween('when', [$from, $to])->latest()->get();

  }else{


    $debit = $this->getBalance(now()->firstOfMonth(),now(), ">=");
    $credit = $this->getBalance(now()->firstOfMonth(),now(), "<");
    $transaction = Auth::user()->cashes() ->whereBetween('when', [now()->firstOfMonth(),now()])->latest()->get();
  }

  return response()->json([
    'balances'=> str_replace(',', '.',number_format(Auth::user()->cashes()->get('amount')->sum('amount'))),
    'debit'=> str_replace(',', '.',number_format($debit)),
    'credit'=>str_replace(',', '.',number_format($credit)),
    'transaction'=>CashResource::collection($transaction),
    'now'=>now()->format("Y-m-d"),
    'firstOfMonth'=>now()->firstOfMonth()->format("Y-m-d"),
  ]);

}

public function store(Request $request){
        //return ('ok');
  $request()->validate([
    'name'=>'required',
    'amount'=>'required|numeric',
  ]);
  $when = request('when') ?? now();
  $cash= Auth::user()->cashes()->create([
   'name'=>request('name'),
   'slug'=>Str::slug(request('name'). "-" . Str::random(6)) ,
   'when'=> $when,
   'amount'=>request('amount'),
   'description'=>request('description')
 ]);
  return response()->json([
   'message'=>'the transaction has been saved.',
   'cash'=>new CashResource($cash)
 ]);
}
public function show(Cash $cash){
  return new CashResource($cash);
}
public function getBalance($from, $to, $operator){
  return   Auth::user()->cashes()
  ->whereBetween('when', [$from, $to])
  ->where('amount', $operator ,0)
  ->get('amount')->sum('amount');
}
}
