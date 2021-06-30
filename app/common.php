<?php

use think\facade\Env;
use \Firebase\JWT\JWT;

//生成验签
function createToken($user)
{
    $token = array(
        'id' => $user['id'],
        'username' => $user['username'],
        "iat" => time(),      //签发时间(单位秒)
        "nbf" => time(),    //在什么时候jwt开始生效  （这里表示生成生效）(单位秒)
        "exp" => time() + 36000, //token 过期时间(单位秒)
        'role' => $user['type'] == 1 ? 'user' : 'admin',
        'password' => md5($user['password'])
    );
    $jwt = JWT::encode($token, Env::get('JWT.salt'), "HS256");  //根据参数生成了 token
    return $jwt;
}

//验证token
function parseToken($token)
{
    $status = array("code" => 2);
    try {
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        $decoded = JWT::decode($token, Env::get('JWT.salt'), array('HS256')); //HS256方式，这里要和签发的时候对应
        return ['code' => 200, 'msg' => 'success', 'data' => (array)$decoded];
    } catch (\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
        return ['code' => -10000, 'msg' => 'sign error'];
    } catch (\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
        return ['code' => -10001, 'msg' => 'token invalid'];
    } catch (\Firebase\JWT\ExpiredException $e) { // token过期
        return ['code' => -10002, 'msg' => 'token invalid'];
    } catch (Exception $e) { //其他错误
        return ['code' => -10003, 'msg' => 'unknown error'];
    }
}
// 应用公共文件
