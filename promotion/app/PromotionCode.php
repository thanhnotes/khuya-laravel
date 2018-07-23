<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Promotion;
use Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionCode extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['code', 'actived', 'value', 'type'];
    
    public function promotion(){
        return $this->belongsTo(Promotion::class);
    }

    protected function validator($data){
        return Validator::make($data, [
            'code' => 'unique:promotion_codes,code',
            'actived' => 'required',
            'value' => 'required|numeric',
            'type' => 'required',
            'promotion_id' => 'required',
        ]);
    }

    public static function codeGenerate($length = 8, $value = null)
    {
        $pool = "";
        if(is_null($value)){
            //Tao ra ma IN HOA, xoa khoang trang
            $pool = str_replace(" ", '', strtoupper(str_random($length)));
            $existedCode = PromotionCode::where('code',$pool)->first();
            if($existedCode){
                //Neu code da ton tai thi tao lai ma khac
                return $pool = PromotionCode::codeGenerate($length);
            }
            return $pool;
        }else{
            $pool = str_replace(" ", '', strtoupper($value));
            return $pool;
        }
    }

    public function codeGenerate2($length = 8, $value = null)
    {
        $existedCode = PromotionCode::pluck('code')->all();
        $pool = str_replace(" ", '', strtoupper(str_random($length)));
        if(is_null($value)){
            for($i=0; $i < count($existedCode);$i++){
                if($pool === $existedCode[$i]){
                    $pool = str_replace(" ", '', strtoupper(str_random($length)));
                    break;
                }
            }  
            return $pool;
        }else{
            $pool = str_replace(" ", '', strtoupper($value));
            return $pool;
        }

        // ubstr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
    }
}
