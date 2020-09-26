<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Mockery\Exception;

    /**
     * @property integer $id
     * @property integer $sms_id
     * @property string  $from
     * @property string  $code
     * @property string  $recid
     * @property integer $status
     * @property integer $timestamp
     *
     * Class Address
     * @package App\Models
     */
    class SmsLogs extends Model {
        const STATUS_Send = "1";
        const STATUS_NotSend = "2";
        const STATUS_Received = "3";

        protected $fillable = [
            'id',
            'sms_id',
            'from',
            'code',
            'recid',
            'status',
            'timestamp'
        ];

        public static function registerSms($request) {
            $smsLogs = new SmsLogs();
            $smsLogs->sms_id = $request['sms_id'];
            $smsLogs->from = '3000747788';
            $smsLogs->code = isset($request['code']) ? $request['code'] : null;
            $smsLogs->recid = isset($request['recid']) ? $request['recid'] : null;
            $smsLogs->timestamp = time();
            $smsLogs->status = self::STATUS_Send;
            try {
                DB::beginTransaction();
                $smsLogs->save();

                DB::commit();
                return $smsLogs->id;

            } catch(Exception $e) {
                DB::rollBack();
                return false;
            }

        }

    }
