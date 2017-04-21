<?php

namespace app\models;

/**
 * This is the model class for table "registro_ord".
 *
 * @property integer $id
 * @property integer $ordenador_id
 * @property integer $origen_id
 * @property integer $destino_id
 * @property string $created_at
 *
 * @property Aula $origen
 * @property Aula $destino
 * @property Ordenador $ordenador
 */
class RegistroOrd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registro_ord';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ordenador_id', 'origen_id', 'destino_id'], 'required'],
            [['ordenador_id', 'origen_id', 'destino_id'], 'integer'],
            [['created_at'], 'safe'],
            [['origen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['origen_id' => 'id']],
            [['destino_id'], 'exist', 'skipOnError' => true, 'targetClass' => Aula::className(), 'targetAttribute' => ['destino_id' => 'id']],
            [['ordenador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ordenador::className(), 'targetAttribute' => ['ordenador_id' => 'id']],
            [['origen_id'], 'compare', 'compareAttribute' => 'destino_id', 'operator' => '!=' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ordenador_id' => 'Ordenador',
            'origen_id' => 'Origen',
            'destino_id' => 'Destino',
            'created_at' => 'Fecha y hora',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrigen()
    {
        return $this->hasOne(Aula::className(), ['id' => 'origen_id'])->inverseOf('esOrigenOrd');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestino()
    {
        return $this->hasOne(Aula::className(), ['id' => 'destino_id'])->inverseOf('esDestinoOrd');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenador()
    {
        return $this->hasOne(Ordenador::className(), ['id' => 'ordenador_id'])->inverseOf('registros');
    }
}
