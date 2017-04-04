<?php

namespace app\models;

/**
 * This is the model class for table "ordenadores".
 *
 * @property integer $id
 * @property string $marca_ord
 * @property string $modelo_ord
 * @property integer $aula_id
 *
 * @property Dispositivo[] $dispositivos
 * @property Aula $aula
 * @property RegistroDisp[] $esOrigenDisp
 * @property RegistroDisp[] $esDestinoDisp
 * @property RegistroOrd[] $registros
 */
class Ordenador extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ordenadores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aula_id'], 'required'],
            [['aula_id'], 'integer'],
            [['marca_ord', 'modelo_ord'], 'string', 'max' => 255],
            [['aula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['aula_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marca_ord' => 'Marca',
            'modelo_ord' => 'Modelo',
            'aula_id' => 'Aula',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispositivos()
    {
        return $this->hasMany(Dispositivo::className(), ['ordenador_id' => 'id'])->inverseOf('ordenador');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAula()
    {
        return $this->hasOne(Aula::className(), ['id' => 'aula_id'])->inverseOf('ordenadores');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsOrigenDisp()
    {
        return $this->hasMany(RegistroDisp::className(), ['origen_ord_id' => 'id'])->inverseOf('origenOrd');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsDestinoDisp()
    {
        return $this->hasMany(RegistroDisp::className(), ['destino_ord_id' => 'id'])->inverseOf('destinoOrd');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistros()
    {
        return $this->hasMany(RegistroOrd::className(), ['ordenador_id' => 'id'])->inverseOf('ordenador');
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert && isset($changedAttributes['aula_id'])) {
            $reg = new RegistroOrd;
            $reg->ordenador_id = $this->id;
            $reg->origen_id = $changedAttributes['aula_id'];
            $reg->destino_id = $this->aula_id;
            $reg->save();
        }
    }
}
