<?php

    namespace App\Http\Controllers\Auth;

    use App\User;
    use App\Models\Sms;
    use App\Models\SmsLogs;
    use App\Models\Mail;
    use App\Http\Controllers\Controller;
    use Curl\Curl;
    use Illuminate\Auth\Events\Registered;
    use Illuminate\Foundation\Auth\RedirectsUsers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Foundation\Auth\RegistersUsers;
    use Illuminate\Validation\ValidationException;
    use Illuminate\Foundation\Auth\AuthenticatesUsers;

    class RegisterController extends Controller {
        /*
        |--------------------------------------------------------------------------
        | Register Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles the registration of new users as well as their
        | validation and creation. By default this controller uses a trait to
        | provide this functionality without requiring any additional code.
        |
        */

        use RedirectsUsers;

        public function showRegistrationForm() {
            return view('auth.register');
        }

        protected function guard() {
            return Auth::guard();
        }

        protected function registered(Request $request, $user) {
            //
        }

        protected $redirectTo = '/';

        public function __construct() {
            $this->middleware('guest');
        }

        protected function validator(array $data) {
            return Validator::make($data, [
                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'last_name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'mobile' => [
                    'required',
                    'unique:users'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255'
                ],
                'address' => ['required'],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed'
                ],
            ]);
        }

        protected function create(array $data) {
            $auth_code = rand(1000, 9999);
            $message = 'Code: ' . $auth_code . '

تیم پرینتم';
            $params = [
                'action' => 'auth',
                'mobile' => $data['mobile'],
                'message' => $message,
            ];
            $sms_record = Sms::SendSms($params);

            $sms_params = [
                'username' => 'printam',
                'password' => 'printam@1398',
                'from' => '3000747788',
                'to' => $data['mobile'],
                'text' => $message,
            ];
            $curl = new Curl();
            $response = $curl->post('http://sms.mabnallc.com/API/SendSms.ashx/', $sms_params);

            $logs_params = [
                'sms_id' => $sms_record,
                'code' => $response->response,
                'recid' => $response->response,
            ];
            SmsLogs::registerSms($logs_params);

            $token = strtoupper(str_random(32));
            $token_email = 'https://printam.ir/verify/email/' . $token;

            // Send Email Verification
            $email_data = [
                'to' => $data['email'],
                'subject' => 'تایید ایمیل ثبت نام',
                'title' => 'تایید ایمیل ثبت نام',
                'h1' => 'به پرینتم خوش آمدید',
                'text' => 'حساب کاربری شما با موفقیت ایجاد شد',
                'desc' => 'به منظور اعتبارسنجی ثبت نام خود ، لطفاً با کلیک بر روی دکمه زیر ، ایمیل خود را تأیید کنید. تأیید باید ظرف 48 ساعت پس از ثبت نام انجام شود',
                'auth' => $token_email,
            ];
            Mail::SendMail($email_data['to'], $email_data['subject'], $email_data['title'], $email_data['h1'], $email_data['text'], $email_data['desc'], $email_data['auth']);

            return User::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'mobile' => $data['mobile'],
                'address' => $data['address'],
                'email' => $data['email'],
                'type' => User::TYPE_CUSTOMER,
                'language' => 'persian',
                'activity' => 'customer',
                'auth' => $auth_code,
                'token' => $token,
                'password' => Hash::make($data['password']),
                'status' => User::STATUS_Auth,
            ]);
        }

        public function redirectPath() {
            if(method_exists($this, 'redirectTo')) {
                return $this->redirectTo();
            }

            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/panel';
        }

        public function registerAuth() {
            $request = $_GET;
            $response = [
                'success' => false,
                'data' => 'error',
            ];

            $check_user = User::where('mobile', $request['mobile'])->first();
            if($check_user) {
                if($check_user->auth == $request['auth']) {
                    User::where('mobile', $request['mobile'])->update([
                        'auth' => null,
                        'status' => User::STATUS_Active,
                    ]);
                    $response = [
                        'success' => true,
                        'data' => 'حساب کاربری شما با موفقیت تایید شد، لطفا وارد سیستم شوید',
                    ];

                } else {
                    $response = [
                        'success' => false,
                        'data' => 'error',
                        'error' => 'کد تایید وارد شده صحیح نمی باشد',
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'data' => 'error',
                    'error' => 'شناسه کاربری شما در سیستم یافت نشد',
                ];
            }

            return $response;
        }

        public function verifyEmail(string $token = null) {
            if($token != null) {
                $check_token = User::where('token', $token)->first();
                if($check_token) {
                    if($check_token->verified == 0) {
                        User::where('token', $token)->update([
                            'verified' => 1,
                        ]);
                        throw ValidationException::withMessages([
                            'success' => 'ایمیل شما با موفقیت در سامانه تایید شد.',
                        ]);
                    } else {
                        throw ValidationException::withMessages([
                            'token' => 'ایمیل شما قبلا در سامانه تایید شده است، این شناسه یکتا منقضی شده است.',
                        ]);
                    }
                } else {
                    throw ValidationException::withMessages([
                        'token' => 'شناسه یکتا شما منقضی شده است، لطفا درخواست تایید ایمیل مجدد ارسال نمایید.',
                    ]);
                }
            } else {
                throw ValidationException::withMessages([
                    'token' => 'شناسه یکتا شما برای تایید ایمیل صحیح نمی باشد، لطفا پس از بررسی، مجدد تلاش نمایید.',
                ]);
            }
        }

        public function registerUser() {
            $request = $_GET;
            $response = [
                'success' => false,
                'data' => 'error',
            ];

            if($request) {
                $check_mobile = self::is_phone_number($request['mobile']);
                if($check_mobile['success'] == true) {
                    if($check_mobile['present'] == false) {
                        $check_email = self::is_email($request['email']);
                        if($check_email['success'] == true) {
                            if($check_email['present'] == false) {
                                $user_create = self::create($request);
                                if($user_create) {
                                    $response = [
                                        'success' => true,
                                        'data' => 'ثبت نام با موفقیت انجام شد',
                                    ];
                                } else {
                                    $response = [
                                        'success' => false,
                                        'data' => 'خطای ناشناخته رخ داد',
                                    ];
                                }
                            } else {
                                $response = [
                                    'success' => false,
                                    'data' => 'error',
                                    'error' => 'ایمیل شما تکراری است',
                                ];
                            }
                        } else {
                            $response = [
                                'success' => false,
                                'data' => 'error',
                                'error' => 'ایمیل وارد شده صحیح نمی باشد',
                            ];
                        };
                    } else {
                        $response = [
                            'success' => false,
                            'data' => 'error',
                            'error' => 'شماره تلفن شما تکراری است',
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'data' => 'error',
                        'error' => 'شمراه همراه شما صحیح نمی باشد',
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'data' => 'error',
                    'error' => 'پرکردن تمامی فیلدها الزامی است',
                ];
            }
            //$user_create = self::create($request);

            return $response;
        }

        private function is_phone_number($phone) {
            if(preg_match("/^09[0-9]{9}$/", $phone)) {
                $user = User::where(['mobile' => $phone])->first();
                if($user) {
                    return [
                        'success' => true,
                        'present' => true
                    ];
                } else {
                    return [
                        'success' => true,
                        'present' => false
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'present' => false
                ];
            }
        }

        private function is_email($email) {
            if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
                $user = User::where(['email' => $email])->first();
                if($user) {
                    return [
                        'success' => true,
                        'present' => true
                    ];
                } else {
                    return [
                        'success' => true,
                        'present' => false
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'present' => false
                ];
            }
        }

        public function smsSend() {
            $message = 'کد تایید هویت شما: ' . '4452' . '

تیم پرینتم';

            $sms_params = [
                'username' => 'printam',
                'password' => 'printam@1398',
                'from' => '3000747788',
                'to' => '09352601050',
                'text' => $message,
            ];
            $curl = new Curl();
            $response = $curl->post('http://sms.mabnallc.com/API/SendSms.ashx/', $sms_params);
            echo '<pre>', var_dump($response->response), '</pre>';
            exit;
        }

    }
