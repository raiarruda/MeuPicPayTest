<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Balance extends Model {

	public $timestamps = FALSE;

	public function deposit($value) { //espera retorno de array

		DB::beginTransaction();


		$totalBefore = $this->amount ? $this->amount : 0;
		$valueFormated = number_format($value, 2, '.', '');
		$this->amount += $valueFormated;
		$totalAfter = $this->amount;
		$deposit = $this->save();

		$historic = auth()->user()->historics()->create([
			                                                'type'         => 'I',
			                                                'amount'       => $value,
			                                                'total_before' => $totalBefore,
			                                                'total_after'  => $totalAfter,
			                                                'date'         => date('Ymd')
		                                                ]);

		if ($deposit && $historic) {
			DB::commit();

			return [
				'success' => TRUE,
				'message' => "O valor de R$ {$valueFormated} foi depositado."
			];
		} else {
			DB::rollback();
			echo "rollback";
			return [
				'success' => TRUE,
				'message' => "Falha ao realizar deposito no valor de R$ {$valueFormated}."
			];

		}
	}
	public function withdraw($value) { //espera retorno de array

		if ($this->amount < $value)
			return [
				'success'=>false,
				'message'=>'Saldo insuficiente'
			];
		DB::beginTransaction();




		$totalBefore = $this->amount ? $this->amount : 0;

		$valueFormated = number_format($value, 2, '.', '');
		$this->amount -= $valueFormated;
		$totalAfter = $this->amount;
		$withdraw = $this->save();

		$historic = auth()->user()->historics()->create([
			                                                'type'         => 'I',
			                                                'amount'       => $value,
			                                                'total_before' => $totalBefore,
			                                                'total_after'  => $totalAfter,
			                                                'date'         => date('Ymd')
		                                                ]);

		if ($withdraw && $historic) {
			DB::commit();

			return [
				'success' => TRUE,
				'message' => "O valor de R$ {$valueFormated} foi depositado."
			];
		}
		else{
			DB::rollback();
			echo "rollback";
			return [
				'success' => TRUE,
				'message' => "Falha ao realizar deposito no valor de R$ {$valueFormated}."
			];

		}

	}
}
