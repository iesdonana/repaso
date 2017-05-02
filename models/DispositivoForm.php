<?php

namespace app\models;

use yii\base\Model;

class DispositivoForm extends Dispositivo
{
    public $ubicacion_id;

    public function rules()
    {
        $rules = [
            [['ubicacion_id'], 'default', 'isEmpty' => function ($value) {
                return $value === '' || $value === null || is_array($value);
            }],
            [['ubicacion_id'], 'required'],
            [['ubicacion_id'], 'trim'],
            [['ubicacion_id'], 'string', 'min' => 2],
            [['ubicacion_id'], function ($attribute, $params, $validator) {
                if (!in_array($this->$attribute[0], ['a', 'o'])) {
                    $this->addError(
                        $attribute,
                        'La ubicación tiene una codificación incorrecta.'
                    );
                }
            }],
            [['ubicacion_id'], 'filter', 'filter' => function ($value) {
                $ubicacion = substr($value, 1);
                $procedencia = $value[0];
                if ($procedencia === 'a') {
                    $this->aula_id = $ubicacion;
                    $this->ordenador_id = null;
                } elseif ($procedencia === 'o') {
                    $this->ordenador_id = $ubicacion;
                    $this->aula_id = null;
                }
                return $ubicacion;
            }],
            [['ordenador_id', 'aula_id'], 'integer'],
            [['ordenador_id', 'aula_id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
        ];
        return $rules + parent::rules();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
