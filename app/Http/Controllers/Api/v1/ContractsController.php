<?php
/**
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
use Morilog\Jalali\Jalalian;
use JWTAuth;

class ContractsController
{
    private $ads, $seller, $cusomter, $status, $product_name, $level_one, $level_two, $revival, $balance, $pending_balance, $response, $bid, $token, $now, $expire, $stock;

    public function __construct(Request $request)
    {
        // authorization
        if (isset($request->token)) {
            $this->user = JWTAuth::parseToken()->authenticate();
        }
        
        // set required values
        if($request->hasAny(['seller', 'ads'])) {
            $this->now = Carbon::now();
            $this->ads  = $request->ads;
            $this->seller = $request->seller;
            $this->status = ($request->status) ? $request->status : null;
            $this->customer = ($request->customer) ? $request->customer : null;
            $this->product_name = ($request->product_name) ? $request->product_name : null;
            $this->level_one = ($request->level_one) ? $request->level_one : null;
            $this->level_two = ($request->level_two) ? $request->level_two : null;
            $this->revival = ($request->revival) ? $request->revival : null;
            $this->balance = ($request->balance) ? $request->balance : null;
            $this->pending_balance = ($request->pending_balance) ? $request->pending_balance : null;
            $this->bid = ($request->bid) ? $request->bid : null;
            $this->expire = ($request->expire) ? $request->expire : null;
            $this->code = ($request->code) ? $request->code : null;
            $this->stock = DB::table('options')
                            ->select('option_value')
                            ->where('option_name', 'user_stock')
                            ->get();
            $this->stock = $this->stock[0]->option_value;
        } else {
            return [
                'status'    => 500,
                'message'   => 'require properties didn\'t set'
            ];
        }
    }
    
    /**
     * start contract by seller
     * 
     * @param int $ads, is id of current ads
     * @param int $seller, is id of seller
     * @param int $customer, is id of user who seller choose him/her
     * @param int $bid, is bid of customer for the ads
     * @debug true
     */
    public function start_contract() 
    {
        DB::table('user_ads')
            ->where('id', $this->ads)
            ->update([
                'winner_id' => $this->customer,
                'bid_price' => $this->bid,
                'status'    => 1, // start contract
                'expired_at'=> $this->get_days('payment_expiration')
            ]);
            
        $winner_meta = DB::table('user_meta')
                        ->select(['name', 'family', 'tell', 'mail', 'address', 'postal_code'])
                        ->where('user_id', $this->customer)
                        ->get();
        return $winner_meta;

    }

    /**
     * revival the ads for special days
     * 
     * @param int $ads, is id of current ads
     * @param int $seller, is id of seller
     * @param int $balance, is fund of the user
     * @param int $revival, is revival cost from active membership of the user
     * @param str $product_name, is of product which choosen for ads
     * @return json for create message into the notice in app and update expired_at
     * and also new expired_at for update app UI and new balance
     * @debug true
     */   
    public function revival_contract()
    {
        // first: pay revival cost
        $affected = DB::table('user_financial')
                                ->where('user_id', $this->seller)
                                ->update([
                                    'balance' => $this->balance - $this->revival
                                ]);

        if($affected == 1) {
            // save invoice of this action
            $title = 'تمدید آگهی برای ' . $this->product_name;
            $this->create_invoice($this->seller, $title, $this->revival, 2, $this->ads);
            $this->create_admin_invoice($this->seller, $title, $this->revival);

            // revival the ads  
            $date =  $this->get_days('revival_days');
            DB::table('user_ads')
                ->where('id', $this->ads)
                ->update([
                    'expired_at' => $date
                ]);

            $jalali =  jdate($date)->format('d F ماه y');
            return [
                'message'       => "انقضای این آگهی تا تاریخ $jalali تمدید شد.",
                'expired_at'    => $date,
                'balance' => $this->balance - $this->revival
            ];
        }
    }

    /**
     * cancel contract by seller
     * 
     * @param int $ads, is id of current ads
     * @param int $status, is level of process
     * @param int $seller, is id of seller
     * @param int $customer, is id of user who seller choose him/her
     * @param int $balance, is fund of the user
     * @param int $level_one, is cost of damage when status = 1
     * @param int $level_two, is cost of damage when status = 2
     * @param int $stock, is users's stock of compensation
     * @param str $product_name, is of product which choosen for ads
     * @return mixed [], if status = 1 || status = 2, it returns a json to send new balance of user,
     *  otherwise it doesn't return anything
     * @debug true
     */   
    public function cancel_contract_by_seller() 
    {
        if($this->status == 1) {
            // compensation from seller
            $title = 'لغو قرارداد آگهی ' . $this->product_name;
            $this->compensation($this->seller, $this->balance, $this->level_one, 'balance');
            $this->create_invoice($this->seller, $title, $this->level_one, 2, $this->ads);
            $this->create_admin_invoice($this->seller, $title, $this->level_one);

            // remove ads
            $this->remove_ads();

            return [
                'balance'   => $this->balance - $this->level_one
            ];
        } else if($this->status == 2) {
            // compensation from seller
            $title = 'لغو قرارداد آگهی ' . $this->product_name;
            $this->compensation($this->seller, $this->balance, $this->level_two, 'balance');
            $this->create_invoice($this->seller, $title, $this->level_two, 2, $this->ads);

            // refund secure payment of customer
            $title_one = 'بازگشت وجه خرید ' . $this->product_name;
            $title_two =  'دریافت خسارت آگهی' . $this->product_name;
            $customer_financial = $this->get_financial_info($this->customer);
            $this->compensation($this->customer, $customer_financial->pending_balance, $this->bid, 'pending_balance');
            $total_refund = $this->bid + floor($this->level_two / $this->stock);
            $this->refund($this->customer, $customer_financial->balance, $total_refund, 'balance');
            $this->create_invoice($this->customer, $title_one, $this->bid, 1, $this->ads);
            $this->create_invoice($this->customer, $title_two, floor($this->level_two / $this->stock), 1, $this->ads);
            
            // create invoice for admin
            $total_income = $this->level_two - floor($this->level_two / $this->stock);
            $this->create_admin_invoice($this->seller, $title, $total_income);

            // remove ads
            $this->remove_ads();

            return [
                'balance'   => $this->balance - $this->level_two
            ];
        }

        // remove ads
        $this->remove_ads();
    }

    /**
     * release payment and finish the contract by seller
     * 
     * @param int $ads, is id of current ads
     * @param int $seller, is id of seller
     * @param int $customer, is id of user who seller choose him/her
     * @param int $bid, is bid of customer for the ads
     * @param int $balance, is fund of the user
     * @param str $product_name, is of product which choosen for ads
     * @param int $token, the token that sent by sms to users and seller sent to us
     * @return json it returns new balance of seller and new status of process of contract, 
     * if token doesn't match with payment code, it returns a message to add it into the notice to show for user
     * @debug true
     */
    public function checkout_seller()
    {
        $ads = DB::table('user_ads')
                ->select(['payment_code', 'winner_id'])
                ->where('id', $this->ads)
                ->get();
        if($this->code == $ads[0]->payment_code && $this->customer == $ads[0]->winner_id) {
            // release payment
            $title = 'فروش آگهی ' . $this->product_name;
            $this->refund($this->seller, $this->balance, $this->bid, 'balance');
            $this->create_invoice($this->seller, $title, $this->bid, 1, $this->ads);

            // delete secure payment of customer
            $customer_financial = $this->get_financial_info($this->customer);
            $this->compensation($this->customer, $customer_financial->pending_balance, $this->bid, 'pending_balance');

            // finish contract
            DB::table('user_ads')
                    ->where('id', $this->ads)
                    ->update([
                        'status'    => 3    
                    ]);
        } else {
            return [
                'message' => 'کدی که برامون ارسال کردی، اشتباهه!'
            ];
        }

        
    }

    /**
     * cancel contract by customer
     * 
     * @param int $ads, is id of current ads
     * @param int $status, is level of process
     * @param int $seller, is id of seller
     * @param int $customer, is id of user who seller choose him/her
     * @param int $balance, is fund of the user
     * @param int $pending_balance, is fund of the customer that stored in secure bank account
     * @param int $level_one, is cost of damage when status = 1
     * @param int $level_two, is cost of damage when status = 2
     * @param int $stock, is users's stock of compensation
     * @param str $product_name, is of product which choosen for ads
     * @return mixed [], if status = 1 || status = 2, it returns a json to send new balance of user,
     *  otherwise it doesn't return anything
     * @debug true
     */   
    public function cancel_contract_by_customer() 
    {
        if($this->status == 1) {
            // compensation from customer
            $title = 'لغو قرارداد آگهی ' . $this->product_name;
            $this->compensation($this->customer, $this->balance, $this->level_one, 'balance');
            $this->create_invoice($this->customer, $title, $this->level_one, 2, $this->ads);

        } else if($this->status == 2) {
            // refund secure payment of customer + compensation from customer
            $title_one = 'بازگشت وجه خرید ' . $this->product_name;
            $title_two = 'لغو قرارداد آگهی ' . $this->product_name;
            $total_balance = $this->balance + $this->bid - $this->level_two;
            DB::table('user_financial')
                ->where('user_id', $this->customer)
                ->update([
                    'balance'           => $total_balance,
                    'pending_balance'   => $this->pending_balance - $this->bid
                ]);
            $this->create_invoice($this->customer, $title_one, $this->bid, 1, $this->ads);
            $this->create_invoice($this->customer, $title_two, $this->level_two, 2, $this->ads);

            // refund secure payment of seller
            $title =  'دریافت خسارت آگهی' . $this->product_name;
            $seller_financial = $this->get_financial_info($this->seller);
            $this->refund($this->seller, $seller_financial->balance, floor($this->level_two / $this->stock), 'balance');
            $this->create_invoice($this->seller, $title, floor($this->level_two / $this->stock), 1, $this->ads);

        }

        // remove ads
        $this->remove_ads();
    }

    /**
     * release payment and finish the contract by seller
     * 
     * @param int $ads, is id of current ads
     * @param int $customer, is id of user who seller choose him/her
     * @param int $bid, is bid of customer for the ads
     * @param int $balance, is fund of the user
     * @param int $pending_balance, is fund of the customer that stored in secure bank account
     * @param str $product_name, is of product which choosen for ads
     * @return json it returns new balance of seller and new tep of process of contract
     * @debug true
     */
    public function payment()
    {
        // buy products and send balance to pending balance for holding secure to payment
        $title = 'خرید بابت آگهی' . $this->product_name;
        DB::table('user_financial')
                ->where('user_id', $this->customer)
                ->update([
                    'balance'           => $this->balance - $this->bid,
                    'pending_balance'   => $this->pending_balance + $this->bid
                ]);
        $this->create_invoice($this->customer, $title, $this->bid, 2, $this->ads);

        // level up contract
        DB::table('user_ads')
            ->where('id', $this->ads)
            ->update([
                'status'    => 2    
            ]);
    }

    /**
     * release payment and finish the contract by seller
     * 
     * @param int $bid
     * @return json of message to notice the user
     * @debug true
     */
    public function send_token()
    {
        // create token
        $token = rand(10000000, 99999999);
        DB::table('user_ads')
                ->where('id', $this->ads)
                ->update([
                    'payment_code' => $token    
                ]);

        // server must use SMS Panel to send token to seller and customer
    }




    /**
     * start contract by seller
     * 
     * @return int is days based on options of the app in DB
     * @debug true
     */   
    private function get_days($name) 
    {
        $days = DB::table('options')
                ->select('option_value')
                ->where('option_name', $name)
                ->get();
        $unix_days = ((int) $days[0]->option_value * 24 * 3600 ) + $this->now->timestamp;
        return Carbon::createFromTimestamp($unix_days)->toDateTimeString();
    }
    
    /**
     * insert new invoice
     * 
     * @param int $owner, user_id of the user who has tradtition
     * @param str $title, is title of invoice
     * @param int $price, is total of price of invoice
     * @param int $status, if is 2, it means the tradition is for expansive; if is 1, it means the tradition is for income
     * @param int $linked, is id of URL of ads and something like that. it'll help app to find currect page of the subject of invoice
     * @debug true
     */   
    private function create_invoice($owner, $title, $price, $status = 2, $linked = null) 
    {
        DB::table('invoice')->insert([
            'user_id'   => $owner,
            'title'     => $title,
            'price'     => $price,
            'status'    => $status,
            'linked'    => $linked, // you can use $this->ads for this for pass argument
            'created_at'=> $this->now,
            'updated_at'=> $this->now
        ]);
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

    /**
     * remove a recordd from user_ads
     * 
     * @param int $ads, is id of current ads
     * @debug true
     */   
    private function remove_ads() 
    {
        $delete = DB::table('user_ads')
                    ->where('id', $this->ads)
                    ->update([
                        'status'    => 4    
                    ]);
}

    /**
     * compensation of cancel process
     * 
     * @param int $owner is id of user_id in user_financial table
     * @param int $acc is balance or pending balance of user 
     * @param int $damage is compensation of cancel process
     * @param str $col is name of column of user_financial
     * @return boolean
     * @debug true
     */   
    private function compensation($owner, $acc, $damage, $col) 
    {
        $affected = DB::table('user_financial')
                                    ->where('user_id', $owner)
                                    ->update([
                                        $col    => $acc - $damage
                                    ]);
        return ($affected == 1) ? true : false;
    }

    /**
     * refund cache
     * 
     * @param int $owner is id of user_id in user_financial table
     * @param int $acc is balance or pending balance of user 
     * @param int $fund is price for refund into balance or pending balance
     * @param str $col is name of column of user_financial
     * @return boolean
     * @debug true
     */   
    private function refund($owner, $acc, $fund, $col)
    {
        $affected = DB::table('user_financial')
                                    ->where('user_id', $owner)
                                    ->update([
                                        $col    => $acc + $fund
                                    ]);
        return ($affected == 1) ? true : false;
    }

    /**
     * get financial information
     * 
     * @param int $owner is id of user_id in user_financial table
     * @return mixed[]
     * @debug true
     */
    private function get_financial_info($owner)
    {
        $financial = DB::table('user_financial')
                        ->select(['balance', 'pending_balance'])
                        ->where('user_id', $owner)
                        ->get();
        return $financial[0];
    }

    


}
