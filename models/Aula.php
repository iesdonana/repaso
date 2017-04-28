<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aulas".
 *
 * @property integer $id
 * @property string $den_aula
 *
 * @property Dispositivo[] $dispositivos
 * @property Ordenador[] $ordenadores
 * @property RegistroDisp[] $esOrigenDisp
 * @property RegistroDisp[] $esDestinoDisp
 * @property RegistroOrd[] $esOrigenOrd
 * @property RegistroOrd[] $esDestinoOrd
 */
class Aula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aulas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['den_aula'], 'required'],
            [['den_aula'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'den_aula' => 'Denominación',
        ];
    }

    public static function findDropDownList()
    {
        $lista = self::find()
            ->select('den_aula, id')
            ->indexBy('id')
            ->orderBy('den_aula')
            ->column();
        return ['' => ''] + $lista;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispositivos()
    {
        return $this->hasMany(Dispositivo::className(), ['aula_id' => 'id'])->inverseOf('aula');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenadores()
    {
        return $this->hasMany(Ordenador::className(), ['aula_id' => 'id'])->inverseOf('aula');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsOrigenDisp()
    {
        return $this->hasMany(RegistroDisp::className(), ['origen_aula_id' => 'id'])->inverseOf('origenAula');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsDestinoDisp()
    {
        return $this->hasMany(RegistroDisp::className(), ['destino_aula_id' => 'id'])->inverseOf('destinoAula');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsOrigenOrd()
    {
        return $this->hasMany(RegistroOrd::className(), ['origen_id' => 'id'])->inverseOf('origen');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsDestinoOrd()
    {
        return $this->hasMany(RegistroOrd::className(), ['destino_id' => 'id'])->inverseOf('destino');
    }
}
