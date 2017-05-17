<?php

namespace app\helpers;

use Yii;

class Mensaje
{
    const MENSAJE_EXITO = 'exito';
    const MENSAJE_FRACASO = 'fracaso';

    public static function exito($mensaje = null)
    {
        if ($mensaje === null) {
            return Yii::$app->session->getFlash(self::MENSAJE_EXITO);
        }
        static::mensaje($mensaje, self::MENSAJE_EXITO);
    }

    public static function fracaso($mensaje = null)
    {
        if ($mensaje === null) {
            return Yii::$app->session->getFlash(self::MENSAJE_FRACASO);
        }
        static::mensaje($mensaje, self::MENSAJE_FRACASO);
    }

    public static function hayExito()
    {
        return Yii::$app->session->hasFlash(self::MENSAJE_EXITO);
    }

    public static function hayFracaso()
    {
        return Yii::$app->session->hasFlash(self::MENSAJE_FRACASO);
    }

    protected static function mensaje($mensaje, $tipo)
    {
        Yii::$app->session->setFlash($tipo, $mensaje);
    }
}
