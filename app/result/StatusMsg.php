<?php

namespace app\result;
class StatusMsg
{
    static public $SUCCESS = "success";
    /* REQUEST */
    static public $REQUEST_PARAMERROR = "参数错误";
    /* USER */
    static public $USER_USEREXIST = "该用户已存在";
    static public $USER_REGISTERFAIL = "注册失败,请稍后再试";
    static public $USER_USERNOTEXIST = "该用户不存在";
    static public $USER_UPDATEFAIL = "修改失败,请稍后再试";
    static public $USER_DELETEFAIL = "删除失败,请稍后再试";
    static public $USER_USERNAMEORPASSWORDERROR = "用户名或密码错误";
    /* TOKEN */
    static public $TOKENINVAILD = "令牌校验失败";
}
