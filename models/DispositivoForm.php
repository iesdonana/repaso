<?php

namespace app\models;

use yii\base\Model;

class DispositivoForm extends Dispositivo
{
    public $ubicacion_id;
    public $nuevo_aula_id;
    public $nuevo_ordenador_id;

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
                $procedencia = $this->$attribute[0];
                $ubicacion = substr($this->$attribute, 1);

                if (!in_array($procedencia, ['a', 'o']) ||
                    !ctype_digit($ubicacion)) {
                    $this->addError(
                        $attribute,
                        'La ubicación tiene una codificación incorrecta.'
                    );
                    return;
                }

                $ubicacion = (int) $ubicacion;

                switch ($procedencia) {
                    case 'a':
                        $this->nuevo_aula_id = $ubicacion;
                        $this->nuevo_ordenador_id = null;
                        break;

                    case 'o':
                        $this->nuevo_ordenador_id = $ubicacion;
                        $this->nuevo_aula_id = null;
                        break;
                }
            }],
            ['ordenador_id', 'filter', 'filter' => function ($value) {
                return $this->nuevo_ordenador_id;
            }],
            ['aula_id', 'filter', 'filter' => function ($value) {
                return $this->nuevo_aula_id;
            }],
            [
                ['ubicacion_id'],
                'exist',
                'targetClass' => Aula::className(),
                'targetAttribute' => ['aula_id' => 'id'],
                'isEmpty' => function ($value) {
                    return $this->aula_id === null;
                }
            ],
            [
                ['ubicacion_id'],
                'exist',
                'targetClass' => Ordenador::className(),
                'targetAttribute' => ['ordenador_id' => 'id'],
                'isEmpty' => function ($value) {
                    return $this->ordenador_id === null;
                }
            ],
        ];
        return array_merge($rules, parent::rules());
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
