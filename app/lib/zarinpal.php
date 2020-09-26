<?php

namespace App\lib;

use DB;
/*require_once 'nusoap.php';*/

use nusoap_client;

class zarinpal
{
    public $MerchantID;

    public function __construct()
    {
        $this->MerchantID = "96f80156-39fe-11e9-9ee6-005056a205be";
    }

    public function pay($Amount, $Email, $Mobile)
    {
        $Description = 'شارژ حساب';  // Required
        $CallbackURL = url('/api/v1/financial/verify'); // Required


        $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentRequest', [
            [
                'MerchantID' => $this->MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ],
        ]);

        //Redirect to URL You can do it also by creating a form
        if ($result['Status'] == 100) {
            return $result['Authority'];
        } else {
            return false;
        }


    }

}
