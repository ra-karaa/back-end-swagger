<?php

namespace App\Repository;

use App\Interfaces\ApiRepoInterfaces;
use App\Models\Setting;
use App\Models\References;
use App\Models\Employees;
use App\Models\Overtimes;
use Carbon\Carbon;
use Str;

class ApiRepo implements ApiRepoInterfaces
{
    public function __construct(Setting $seting, References $refern, Employees $emplo, Overtimes $over)
    {
        $this->seting = $seting;
        $this->refern = $refern;
        $this->emplo = $emplo;
        $this->over = $over;
    }

    public function updateSetting(array $data)
    {
        $refern = $this->refern->all();
        $id_refern_1 = $refern[0]['id'];
        $id_refern_2 = $refern[1]['id'];
        if($data['key'] != 'overtime_method'){
            return response()->json(["msg" => 'Maaf Key Harus Berisi "overtime_method"'], 401);
        } else {
            if($data['value'] == $id_refern_1 || $data['value'] == $id_refern_2){
                $cek = $this->seting->where('key', 'overtime_method')->update([
                    'value' => $data['value']
                ]);
                return response()->json(["msg"=>"Berhasil Upadate Data Seting"],200);
            } else {
                return response()->json(["msg"=>"Maaf Nilai Value Harus Bernilai 1 atau 2"],401);
            }
        }
    }

    public function storeEmployees(array $data)
    {
        return $this->emplo->create($data);
    }

    public function storeOvertimes(array $data)
    {
        $cek = $this->emplo->where('id', $data['employees_id'])->first();
        if($cek){
            $over = $this->over->where('employees_id', $cek->id)->first();
            if(empty($over)){
                return $this->over->create($data);
            } else {
                return response()->json(['msg'=> 'Maaf Anda Sudah Terdata Overtimes'], 401);
            }
        } else {
            return response()->json(['msg'=> 'Maaf Id yang anda masukan tidak terdapat pada table employees'], 401);
        }
    }

    public function overtimePay($date)
    {
        $parse = \Carbon\Carbon::parse($date)->translatedFormat('m');
        $rumus = null;
        $res = array();
        $data = $this->emplo->whereHas('overtime', function($query) use ($parse) {
            $query->whereMonth('date', $parse);
        })->with('overtime')->get();
        $res = array();
        foreach($data as $temp):
            $total = 0;
            foreach($temp->overtime as $temp_overtime):
                $startTime = Carbon::parse($temp_overtime->time_started);
                $finishTime = Carbon::parse($temp_overtime->time_ended);
                $oke = $finishTime->diff($startTime)->format('%H');
                $total += $finishTime->diff($startTime)->format('%H');
            endforeach;
            $cek = $this->seting->first();
            if($cek->value == 2){
                $oke = $this->refern->where('id', $cek->value)->first();
                $rumus = mb_substr($oke->expresion, 0, 5) * $total;
            } else {
                $oke = $this->refern->where('id', $cek->value)->first();
                $rumus = round(($temp->salary / mb_substr($oke->expresion, 9, 4)) * $total,0);
            }
            array_push($res, array(
                "id" => $temp->id,
                "name" => $temp->name,
                "salary" => $temp->salary,
                "overtime" => $temp->overtime,
                "overtime_duration" => $total,
                "amount" => $rumus
                )
            );
        endforeach;
        return response()->json($res);
    }
}
