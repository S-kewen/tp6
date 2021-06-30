<?php

namespace app\result;
class StatusCode
{
    static public $SUCCESS = 200;
    /* REQUEST */
    static public $REQUEST_PARAMERROR = -1001;
    /* USER */
    static public $USER_USEREXIST = -2001;
    static public $USER_REGISTERFAIL = -2002;
    static public $USER_USERNOTEXIST = -2003;
    static public $USER_UPDATEFAIL = -2004;
    static public $USER_DELETEFAIL = -2005;
    static public $USER_USERNAMEORPASSWORDERROR = -2006;
    /* TOKEN */
    static public $TOKENINVAILD = -3001;
}
