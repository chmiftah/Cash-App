<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       return [
        'name'=>$this->name,
        'description'=>$this->description,
        'slug'=>$this->slug,
        'id'=>$this->id,
        'when'=> $this->when->format("d F Y H:i"),
        'amount'=> str_replace(',', '.',number_format(abs($this->amount))),
        'isCredit' =>($this->amount < 0) ? true : false
       ];
    }
}
