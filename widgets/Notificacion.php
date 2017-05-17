<?php

namespace app\widgets;

use app\helpers\Mensaje;
use yii\base\Widget;
use yii\bootstrap\Alert;

class Notificacion extends Widget
{
    public function run()
    {
        $out = '';
        if (Mensaje::hayExito()) {
            $out .= Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Mensaje::exito(),
            ]);
        }
        if (Mensaje::hayFracaso()) {
            $out .= Alert::widget([
                'options' => [
                    'class' => 'alert-danger',
                ],
                'body' => Mensaje::fracaso(),
            ]);
        }
        return $out;
    }
}
