<?php

namespace App\Http\Proxy;
use App\Events\UserLogin;
use App\Events\UserLogout;
use App\Models\LogLogin;
use App\Models\ThreeLogin;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25 0025
 * Time: 23:02
 */
class TokenProxy
{
    protected $http;

    /**
     * TokenProxy constructor.
     * @param $http
     */
    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }

    public function login($email, $password)
    {
        if (auth()->attempt(['email'=> $email, 'password'=> $password])){
            event(new UserLogin());
            return $this->proxy('password', [
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ]);
        }
        return response()->json([
            'status' => 'login error',
            'status_code' => 421,
            'message' => 'Credentials not match'
        ],421);
    }
    public function loginWithThree($email, $password, $id, $provider)
    {
        if (auth()->attempt(['email'=> $email, 'password'=> $password])){
            $user_id = Auth::user()->id;
            event(new UserLogin());
            ThreeLogin::firstOrCreate(['platform_id'=>$id, 'provider'=>$provider, 'user_id' => $user_id]);
            return $this->proxy('password', [
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ]);
        }
        return response()->json([
            'status' => 'login error',
            'status_code' => 421,
            'message' => 'Credentials not match'
        ],421);
    }

    public function proxy($grantType, array $data = [])
    {
        $data     = array_merge($data, ['client_id'     => env('PASSPORT_CLIENT_ID'),
                                        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                                        'grant_type'    => $grantType
        ]);
        $website = $_SERVER['HTTP_HOST'];
        $response = $this->http->post('http://' . $website . '/oauth/token', ['form_params' => $data
        ]);
        $token = json_decode((string)$response->getBody(), true);
        return response()->json(['token'      => $token['access_token'],
                                 'expires_in' => $token['expires_in'],
                                 'status' => 'success',
                                 'status_code' => 200
        ])->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
    }

    public function logout()
    {
        $user = auth()->guard('api')->user();
        $accessToken = $user->token();
        app('db')->table('oauth_refresh_tokens')
                         ->where('access_token_id', $accessToken->id)
                         ->update([
                             'revoked' => true
                         ]);
        app('cookie')->forget('refreshToken');
        $accessToken->revoke();
 //       $log = new LogLogin();
//        $log->saveLogoutLog($user);
       event(new UserLogout($user));
//        event(new UserLogout($user));
        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'logout success'
            ]
        ,200);
    }

    public function refresh()
    {
        $refreshToken = request()->cookie('refreshToken');
        return $this->proxy('refresh_token',
            ['refresh_token' => $refreshToken]);
    }
}