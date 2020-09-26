<?php
/*
 * @author Milad Mohammadi
 * @desc see @APIlink [blank]
 */

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\QueryRequest;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use JWTAuth;

class FinancialController extends Costomizer
{
    // global properties
    protected $id, $now, $response;
    
    // update financials properties
    protected $balance, $membership, $default_membership, $expiration;
    
    // merchant properties
    protected $amount, $desc; 
    
    // merchant consts
    const MERCHANT = '*************************';
    const CALLBACK = 'YOUR ROUTE FOR RUN VERIFY ACTION';

    public function __construct(Request $request)
    {
        // authorization
        if (isset($request->token)) {
            $this->user = JWTAuth::parseToken()->authenticate();
        }
        
        // set required values
        $this->response     = null;
        $this->now = Carbon::now();
        $this->id = ($request->id) ? $request->id : null;
        $this->membership = ($request->membership) ? $request->membership : null;
        
    }
    
    /**
     * @return json
     * @debug false
     * @desc update balance & membership
     */
    public function update_user_financial() 
    {
        $balance = $this->update_user_balance();
        $membership = $this->update_user_membership();
        $this->response['status']['balance'] = $balance;
        $this->response['status']['membership'] = $membership;
        $this->response;
        return $this->response;
    }
    
    /**
     * @return boolean
     * @debug true
     * @desc update balance
     */
    private function update_user_balance()
    {
        $join = DB::table('user_financial')
                    ->join('membership', 'membership.id', '=', 'user_financial.membership_id')
                    ->select(['balance', 'user_financial.updated_at', 'unity'])
                    ->where('user_financial.user_id', $this->id)
                    ->get();
        
        $pastime = $this->now->timestamp - Carbon::parse($join[0]->updated_at)->timestamp;
        
        // if last update is for at least one hour ago
        if($pastime >= 3600) {
            
            // calculate cost
            $cost = round(($pastime / 3600), 2) * $join[0]->unity;
            
            // if user has enough money for payment:
            if($cost < $join[0]->balance) {
                $this->response['balance'] = round($join[0]->balance - $cost);
                $this->response['updated_at'] = $this->now->toDateTimeString();
                $this->create_admin_invoice($this->id, 'مبلغ کسر شده از حساب کاربر', $cost);
                
            // if user hasn't enough money for payment:
            } else {
                $this->response['balance'] = 0;
                $this->response['updated_at'] = $this->now->toDateTimeString();
                if($join[0]->balance != 0) {
                   $this->create_admin_invoice($this->id, 'مبلغ کسر شده از حساب کاربر', $join[0]->balance); 
                }
            }
            
            $effected = DB::table('user_financial')
                        ->where('user_id', $this->id)
                        ->update($this->response);
            
            // balance updated : balance can\'t update
            return ($effected == 1) ? true : false;

        } else {
            // pastime is low
            return false;
        }
    }
    
    /**
     * @return boolean
     * @debug false
     */
    private function update_user_membership() 
    {
        // faild in get data from DB
        if(! $this->get_default_membership() || ! $this->get_membership_exp()) {
            return false;  
        };
        
        // user has default membership
        if($this->membership == $this->default_membership) {
            return false;
            
        // user hasn't enough time
        } else if($this->now->timestamp >= $this->expiration) {
            return $this->disable_membership();
            
        // user has enough time
        } else if($this->now->timestamp < $this->expiration) {
            
            // user has 10 days for revival
            if(($this->expiration - $this->now->timestamp) < 3600 * 10) {
                return $this->send_revival_sms();
                
            // user has more 10 days for revival
            } else {
                return false;
            }
        }
    }
    
    /**
     * @return boolean
     * @debug false
     * @desc get default membership in app
     */
    private function get_default_membership() 
    {
        $select = DB::table('options')
                    ->select('option_value')
                    ->where('option_name', 'default_membership')
                    ->get();
                    
        return (count($select) != 0) ? $this->default_membership = (int) $select[0]->option_value : false;
    }
    
    /**
     * @return boolean
     * @debug false
     * @desc get membership of user
     */
    private function get_membership_exp() 
    {
        $select = DB::table('user_financial')
                    ->select('expired_at')
                    ->where('user_id', $this->id)
                    ->get();
                    
        return (count($select) != 0 && $select[0]->expired_at != null) ? $this->expiration = Carbon::parse($select[0]->expired_at)->timestamp : false;
    }
    
    /**
     * @return boolean
     * @debug false
     * @desc set default membership for user and disable expiration
     */
    private function disable_membership() 
    {
        $new = [
            'membership_id' => $this->default_membership,
            'expired_at'    => null
        ];
        
        $affected = DB::table('user_financial')
                        ->where('user_id', $this->id)
                        ->update($new);
                        
        $this->response['membership'] = $this->default_membership;
        return ($affected) ? true : false;
    }
    
    /**
     * @return boolean
     * @debug false
     * @desc send sms by otp for alarm user
     */
    private function send_revival_sms() 
    {
        return false;
        //  - not complete
    }
    
    /**
     * insert new invoice for admins
     * 
     * @param int $customer, user_id of the user who has tradtition
     * @param str $title, is title of invoice
     * @param int $price, is total of price of invoice
     * @debug true
     */   
    private function create_admin_invoice($customer, $title, $price) 
    {
        DB::table('invoice')->insert([
            'user_id'   => $customer,
            'title'     => $title,
            'price'     => $price,
            'created_at'=> $this->now,
            'updated_at'=> $this->now
        ]);
    }
    
    


}
