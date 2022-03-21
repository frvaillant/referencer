<?php


namespace App\Configurator;

class ConfVerificator
{



    public static function isDefault($conf)
    {
        return false;
    }

    public static function isMailConfigOk($conf)
    {
        return
            isset($conf['MAILER_LOGIN'])  &&
            isset($conf['MAILER_PASS'])  &&
            isset($conf['MAILER_SMTP'])  &&
            isset($conf['MAILER_PORT'])  &&
            isset($conf['MAILER_ENCRYPT'])  &&
            isset($conf['MAILER_SENDER']);
    }

    public static function isComplete($conf)
    {
        return !self::isDefault($conf) && self::isMailConfigOk($conf) && isset($conf['API_IP']) ;
    }

}
