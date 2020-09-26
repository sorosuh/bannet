<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Mockery\Exception;

    /**
     * @property integer $sms_id
     * @property integer $action
     * @property string  $mobile
     * @property string  $message
     * @property integer $status
     * @property integer $timestamp
     *
     * Class Address
     * @package App\Models
     */
    class Sms extends Model {
        const STATUS_Send = "1";
        const STATUS_NotSend = "2";
        const STATUS_Received = "3";

        protected $fillable = [
            'sms_id',
            'action',
            'mobile',
            'message',
            'status',
            'timestamp'
        ];

        public static function SendSms($request) {
            $sms = new Sms();
            $sms->action = self::getAction($request['action']);
            $sms->mobile = $request['mobile'];
            $sms->message = $request['message'];
            //$sms->timestamp = time();
            $sms->status = self::STATUS_Send;
            try {
                DB::beginTransaction();
                $sms->save();

                DB::commit();
                return $sms->id;

            } catch(Exception $e) {
                DB::rollBack();
                return false;
            }

        }

        public static function getAction($value) {
            switch($value) {
                case 'auth' :
                    return "1";
                    break;
                case 'notify' :
                    return "2";
                    break;
                case 'adv' :
                    return "3";
                    break;
                case 'forget' :
                    return "4";
                    break;
                default:
                    return "0";
                    break;
            }
        }
    }
