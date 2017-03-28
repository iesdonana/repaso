<?php

namespace app\models;

use Yii;

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
            [['marca_disp', 'modelo_disp'], 'string', 'max' => 255],
            [['aula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['aula_id' => 'id']],
            [['ordenador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ordenador::className(), 'targetAttribute' => ['ordenador_id' => 'id']],
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
        ];
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
}
