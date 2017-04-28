<?php

namespace app\models;

/**
 * This is the model class for table "dispositivos".
 *
 * @property integer $id
 * @property string $marca_disp
 * @property string $modelo_disp
 * @property integer $ordenador_id
 * @property integer $aula_id
 *
 * @property Aulas $aula
 * @property Ordenadores $ordenador
 * @property RegistroDisp[] $registroDisps
 */
class Dispositivo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dispositivos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ordenador_id', 'aula_id'], 'integer'],
            [['ordenador_id', 'aula_id'], 'filter', 'filter' => function ($value) {
                if ($value === '' || $value === null) {
                    return null;
                } else {
                    return intval($value);
                }
            }],
            [['marca_disp', 'modelo_disp'], 'string', 'max' => 255],
            [['aula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['aula_id' => 'id']],
            [['ordenador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ordenador::className(), 'targetAttribute' => ['ordenador_id' => 'id']],
            [
                [
                    'ordenador_id',
                    'aula_id'
                ],
                function ($attribute, $params, $validator) {
                    if ($this->ordenador_id == null && $this->aula_id == null) {
                        $this->addError($attribute, 'El dispositivo debe estar en alguna parte.');
                    } elseif ($this->ordenador_id != null && $this->aula_id != null) {
                        $this->addError($attribute, 'El dispositivo no puede estar en dos sitios a la vez.');
                    }
                },
                'skipOnEmpty' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marca_disp' => 'Marca',
            'modelo_disp' => 'Modelo',
            'ordenador_id' => 'Ordenador',
            'aula_id' => 'Aula',
            'ubicacion' => 'Ubicación',
        ];
    }

    public function getNombre()
    {
        return $this->marca_disp . ' ' . $this->modelo_disp;
    }

    public function getUbicacion()
    {
        if ($this->ordenador === null) {
            return $this->aula->den_aula;
        } else {
            return $this->ordenador->nombre;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAula()
    {
        return $this->hasOne(Aula::className(), ['id' => 'aula_id'])->inverseOf('dispositivos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenador()
    {
        return $this->hasOne(Ordenador::className(), ['id' => 'ordenador_id'])->inverseOf('dispositivos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistros()
    {
        return $this->hasMany(RegistroDisp::className(), ['dispositivo_id' => 'id'])->inverseOf('dispositivo');
    }

    public function afterSave($insert, $changedAttributes)
    {
        var_dump($changedAttributes);
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            return;
        }

        if (array_key_exists('aula_id', $changedAttributes)) {  // Se ha cambiado el aula
            $reg = new RegistroDisp;
            if ($changedAttributes['aula_id'] == null) {        // Se ha cambiado el aula y antes era nula
                // De ordenador a aula
                $reg->origen_ord_id = $changedAttributes['ordenador_id'];
                $reg->destino_aula_id = $this->aula_id;
            } elseif ($this->aula_id == null) {                 // Se ha cambiado el aula y ahora es nula
                // De aula a ordenador
                $reg->origen_aula_id = $changedAttributes['aula_id'];
                $reg->destino_ord_id = $this->ordenador_id;
            } else {                                            // Se ha cambiado el aula y ahora NO es nula
                // De aula a aula
                $reg->origen_aula_id = $changedAttributes['aula_id'];
                $reg->destino_aula_id = $this->aula_id;
            }
        } elseif (array_key_exists('ordenador_id', $changedAttributes)) {   // Se ha cambiado el ordenador
            $reg = new RegistroDisp;
            // De ordenador a ordenador
            $reg->origen_ord_id = $changedAttributes['ordenador_id'];
            $reg->destino_ord_id = $this->ordenador_id;
        }
        if (isset($reg)) {
            $reg->dispositivo_id = $this->id;
            $reg->save();
        }
    }
}
