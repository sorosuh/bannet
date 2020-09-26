<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class Costomizer extends Controller
{

    protected function after_query($result)
    {
        // change default date to Jalali date
        if (isset($this->afterQuery['timestamp'])) {
            return $this->timestamp($result, $this->afterQuery['timestamp']);
        }

        return $result;
    }

    private function timestamp($result, $data)
    {
        foreach ($result as $row) {
            foreach ($data['col'] as $col) {
                $row->$col = jdate($row->$col)->format($data['format']);
            }
        }
        
        return $result;
    }
    
    protected function before_query($row, $func = null)
    {
        // pattern values : 'name of column' => ['{{name of aciont}}', '{{your value}}']
        // structure : $row[$key]= [$row[$key][0], $row[$key][1]]
        // example : 'expired_at' => ['expire', '30'] => 30 days expire after insert
        
        // global function
        foreach ($row as $key => $val) {
            if(is_array($val) === true) {
                
                // create expiration
                if($val[0] == 'expire') {
                    $row[$key] = date('Y-m-d H:i:s', time() + ((int) $val[1] * 3600));
                }
                
                // revival expiration
                // old exp + new exp width days [days * 3600]
                if($val[0] == 'revival') {
                    $new_expire = Carbon::parse( $val[1]['expired'] )->timestamp + ($val[1]['days'] * 24 * 3600);
                    $row[$key] = date('Y-m-d H:i:s', $new_expire);
                }
                
                // create hash for password
                if($val[0] == 'hash') {
                    $row[$key] = bcrypt($val[1]);
                }
                
            }
        }
        $row['updated_at'] = Carbon::now();
        
        // before insert
        if($func == 'insert') {
            $row['created_at'] = Carbon::now();
        }
        
        return $row;
    }
    
    protected function condition() {
        // check where
        if(is_string($this->where)) {
            $this->db->whereRaw($this->where);
        } else {
            $this->db->where($this->where);
        }
        
        // check limit & offset
        if(! is_null($this->limit) && ! is_null($this->offset)) {
            $this->db->offset($this->offset)->limit($this->limit);
        }
    }


}

