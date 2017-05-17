<?php

namespace app\components;

use Yii;
use yii\helpers\Html;

class UsuariosHelper extends \yii\base\Component
{
    public static function isAdmin()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin;
    }

    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    public static function get($atributo)
    {
        return Yii::$app->user->identity->$atributo;
    }

    public static function listaTipos($key = null)
    {
        $lista = [
            'U' => 'Usuario',
            'A' => 'Administrador',
        ];

        return $key === null ? $lista : $lista[$key];
    }

    public static function menu()
    {
        return static::isGuest() ? (
            ['label' => 'Login', 'url' => ['/site/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . static::get('nombre') . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
        );
    }
}
