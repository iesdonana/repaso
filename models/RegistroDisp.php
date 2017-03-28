<?php

namespace app\models;

/**
 * This is the model class for table "registro_disp".
 *
 * @property integer $id
 * @property integer $dispositivo_id
 * @property integer $origen_ord_id
 * @property integer $origen_aula_id
 * @property integer $destino_ord_id
 * @property integer $destino_aula_id
 * @property string $created_at
 *
 * @property Aula $origenAula
 * @property Aula $destinoAula
 * @property Dispositivo $dispositivo
 * @property Ordenador $origenOrd
 * @property Ordenador $destinoOrd
 */
class RegistroDisp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registro_disp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dispositivo_id'], 'required'],
            [['dispositivo_id', 'origen_ord_id', 'origen_aula_id', 'destino_ord_id', 'destino_aula_id'], 'integer'],
            [['created_at'], 'safe'],
            [['origen_aula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['origen_aula_id' => 'id']],
            [['destino_aula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['destino_aula_id' => 'id']],
            [['dispositivo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dispositivo::className(), 'targetAttribute' => ['dispositivo_id' => 'id']],
            [['origen_ord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ordenador::className(), 'targetAttribute' => ['origen_ord_id' => 'id']],
            [['destino_ord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ordenador::className(), 'targetAttribute' => ['destino_ord_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dispositivo_id' => 'Dispositivo',
            'origen_ord_id' => 'Origen',
            'origen_aula_id' => 'Origen',
            'destino_ord_id' => 'Destino',
            'destino_aula_id' => 'Destino',
            'created_at' => 'Fecha y hora',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrigenAula()
    {
        return $this->hasOne(Aula::className(), ['id' => 'origen_aula_id'])->inverseOf('esOrigenDisp');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinoAula()
    {
        return $this->hasOne(Aula::className(), ['id' => 'destino_aula_id'])->inverseOf('esDestinoDisp');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispositivo()
    {
        return $this->hasOne(Dispositivo::className(), ['id' => 'dispositivo_id'])->inverseOf('registros');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrigenOrd()
    {
        return $this->hasOne(Ordenador::className(), ['id' => 'origen_ord_id'])->inverseOf('esOrigenDisp');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinoOrd()
    {
        return $this->hasOne(Ordenador::className(), ['id' => 'destino_ord_id'])->inverseOf('esDestinoDisp');
    }
}
