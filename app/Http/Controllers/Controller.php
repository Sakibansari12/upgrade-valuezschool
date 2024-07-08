<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use App\Models\{User, School, CitiesModel, StateModel, LogsModel, Program, Course, Package, SchoolPayment, AppSetting};
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function generateUniqueBarcode(Model $target, int $type)
    {
        $settingArr = AppSetting::where('type', $type)->get()->toArray();

        $singleArray = array();

        foreach ($settingArr as $setting) {
            $singleArray[$setting['field']] = $setting['value'];
        }

        $status = true;
        $prefix = (string)$singleArray['prefix_string'];
        $number = (int)$singleArray['next_number'];
        $perfixLen = strlen($prefix);
        $padLen = (int)$singleArray['number_length'];

        if ($padLen > $perfixLen) {
            $padLen = $padLen - $perfixLen;
        } else {
            $prefix = substr($prefix, 0, ($padLen - 2));
            $perfixLen = strlen($prefix);
            if ($padLen > $perfixLen) {
                $padLen = $padLen - $perfixLen;
            }
        }

        $barcode = strtoupper(Str::random(10));

        while ($status === true) {
            $barcode = (string)$prefix . str_pad($number, $padLen, '0', STR_PAD_LEFT);

            $dataArr = $target::where('orderid', $barcode)->exists();

            if (!$dataArr) {
                $status = false;
                AppSetting::where('type', $type)
                    ->where('field', 'next_number')
                    ->update(['value' => $number + 1]);
            } else {
                ++$number;
            }
        }

        return $barcode;
    }


    public static function generateUniqueStudentPayment(Model $target, int $type)
    {
      //  dd($type);
        $settingArr = AppSetting::where('type', $type)->get()->toArray();

        $singleArray = array();
        $month = now()->format('m');
        $year  = substr(now()->format('Y'), -2);
        foreach ($settingArr as $setting) {
            
            $singleArray[$setting['field']] = $setting['value'];
            //dd($singleArray[$setting['field']]);
        }

        $status = true;
        $prefix = (string)$singleArray['prefix_string'];
       // dd($prefix);
        $number = (int)$singleArray['next_number'];
        
        $perfixLen = strlen($prefix);
        $padLen = (int)$singleArray['number_length'];
        
        if ($padLen > $perfixLen) {
            $padLen = $padLen - $perfixLen;
        } else {
            $prefix = substr($prefix, 0, ($padLen - 2));
            
            $perfixLen = strlen($prefix);
            
            if ($padLen > $perfixLen) {
                $padLen = $padLen - $perfixLen;
            }
        }

        $barcode = strtoupper(Str::random(10));
        
        while ($status === true) {
            //dd(str_pad($number, $padLen, '0', STR_PAD_LEFT));
           // dd($padLen);
            $barcode = $month.$year . str_pad($number, $padLen, '0', STR_PAD_LEFT);
           // dd($barcode);
            $dataArr = $target::where('orderid', $barcode)->exists();
           // dd($dataArr);
            if (!$dataArr) {
                $status = false;
                AppSetting::where('type', $type)
                    ->where('field', 'next_number')
                    ->update(['value' => $number + 1]);
            } else {
                ++$number;
            }
        }
      
        return $barcode;
    }



    public static function generateUniquetestId(Model $target, int $type)
    {
      //  dd($type);
        $settingArr = AppSetting::where('type', $type)->get()->toArray();

        $singleArray = array();

        foreach ($settingArr as $setting) {
            $singleArray[$setting['field']] = $setting['value'];
        }

        $status = true;
        $prefix = (string)$singleArray['prefix_string'];
        $number = (int)$singleArray['next_number'];
        $perfixLen = strlen($prefix);
        $padLen = (int)$singleArray['number_length'];

        if ($padLen > $perfixLen) {
            $padLen = $padLen - $perfixLen;
        } else {
            $prefix = substr($prefix, 0, ($padLen - 2));
            $perfixLen = strlen($prefix);
            if ($padLen > $perfixLen) {
                $padLen = $padLen - $perfixLen;
            }
        }

        $barcode = strtoupper(Str::random(10));

        while ($status === true) {
            $barcode = (string)$prefix . str_pad($number, $padLen, '0', STR_PAD_LEFT);

            $dataArr = $target::where('orderid', $barcode)->exists();

            if (!$dataArr) {
                $status = false;
                AppSetting::where('type', $type)
                    ->where('field', 'next_number')
                    ->update(['value' => $number + 1]);
            } else {
                ++$number;
            }
        }

        return $barcode;
    }
}
