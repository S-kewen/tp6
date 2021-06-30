<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use app\model\UserService;
use app\result\MyResult;
use app\result\StatusCode;
use app\result\StatusMsg;
use app\util\TokenUtil;

class User extends BaseController
{
    public function index()
    {
        return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS);
    }

    public function list()
    {
        $param = Request::param();
        if (isset($param['id'])) {
            $where['id'] = $param['id'];
        }
        if (isset($param['type'])) {
            $where['type'] = $param['type'];
        }
        if (isset($param['state'])) {
            $where['state'] = $param['state'];
        }
        $userService = new UserService();
        $maps = $userService->list($where ?? true, $order ?? ['id DESC'], $param['pageNumber'] ?? 1, $param['pageSize'] ?? 20);
        return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS, ['list' => $maps['data'], 'total' => $maps['count']]);
    }

    public function create()
    {
        $param = Request::param();
        if (!isset($param['username']) || !isset($param['password'])) {
            return MyResult::myResult(StatusCode::$REQUEST_PARAMERROR, StatusMsg::$REQUEST_PARAMERROR);
        }
        $userService = new UserService();
        $where['username'] = $param['username'];
        if ($userService->getCount($where) > 0) {
            return MyResult::myResult(StatusCode::$USER_USEREXIST, StatusMsg::$USER_USEREXIST);
        }
        $where['password'] = $param['password'];
        $where['type'] = $param['type'] ?? 1;
        $where['state'] = $param['state'] ?? 1;
        if ($userService->insertOne($where)) {
            return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS);
        } else {
            return MyResult::myResult(StatusCode::$USER_REGISTERFAIL, StatusMsg::$USER_REGISTERFAIL);
        }

    }

    public function changeInfo()
    {
        $param = Request::param();
        if (!isset($param['id']) || !isset($param['state'])) {
            return MyResult::myResult(StatusCode::$REQUEST_PARAMERROR, StatusMsg::$REQUEST_PARAMERROR);
        }
        $userService = new UserService();
        $where['id'] = $param['id'];
        if ($userService->getCount($where) == 0) {
            return MyResult::myResult(StatusCode::$USER_USERNOTEXIST, StatusMsg::$USER_USERNOTEXIST);
        }
        $set['state'] = $param['state'];
        if ($userService->updateOne($where, $set)) {
            return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS);
        } else {
            return MyResult::myResult(StatusCode::$USER_UPDATEFAIL, StatusMsg::$USER_UPDATEFAIL);
        }

    }

    public function delete()
    {
        $param = Request::param();
        if (!isset($param['id'])) {
            return MyResult::myResult(StatusCode::$REQUEST_PARAMERROR, StatusMsg::$REQUEST_PARAMERROR);
        }
        $userService = new UserService();
        $where['id'] = $param['id'];
        if ($userService->getCount($where) == 0) {
            return MyResult::myResult(StatusCode::$USER_USERNOTEXIST, StatusMsg::$USER_USERNOTEXIST);
        }
        if ($userService->deleteOne($where)) {
            return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS);
        } else {
            return MyResult::myResult(StatusCode::$USER_DELETEFAIL, StatusMsg::$USER_DELETEFAIL);
        }

    }

    public function login()
    {
        $param = Request::param();
        if (!isset($param['username']) || !isset($param['password'])) {
            return MyResult::myResult(StatusCode::$REQUEST_PARAMERROR, StatusMsg::$REQUEST_PARAMERROR);
        }
        $userService = new UserService();
        $where['username'] = $param['username'];
        $where['password'] = $param['password'];
        $maps = $userService->selectOne($where);
        if (!$maps->isEmpty()) {
            return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS, ['id' => $maps[0]['id'], 'token' => TokenUtil::createToken($maps[0])]);
        } else {
            return MyResult::myResult(StatusCode::$USER_USERNAMEORPASSWORDERROR, StatusMsg::$USER_USERNAMEORPASSWORDERROR);
        }
    }

    public function checkToken()
    {
        $header = Request::header();
        if (!isset($header['authorization'])) {
            return MyResult::myResult(StatusCode::$REQUEST_PARAMERROR, StatusMsg::$REQUEST_PARAMERROR);
        }
        $maps = TokenUtil::parseToken($header['authorization']);
        if ($maps['code'] == 200) {
            return MyResult::myResult(StatusCode::$SUCCESS, StatusMsg::$SUCCESS, $maps);
        } else {
            return MyResult::myResult(StatusCode::$TOKENINVAILD, StatusMsg::$TOKENINVAILD, $maps);
        }
    }
}