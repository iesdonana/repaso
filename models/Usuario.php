<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $password
 * @property string $tipo
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_FORM = 'form';

    public $passwordForm;
    public $passwordConfirmForm;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'tipo'], 'required'],
            [['password'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['nombre'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 60],
            [['tipo'], 'string', 'max' => 1],
            [['tipo'], 'in', 'range' => ['U', 'A']],
            [['nombre'], 'unique'],
            [
                ['passwordForm', 'passwordConfirmForm'],
                'required',
                'on' => [self::SCENARIO_FORM],
            ],
            [
                ['passwordForm'],
                'compare',
                'compareAttribute' => 'passwordConfirmForm',
                'on' => [self::SCENARIO_FORM],
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
            'nombre' => 'Nombre',
            'password' => 'Contraseña',
            'tipo' => 'Tipo',
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * Busca a un usuario por su nombre
     *
     * @param string $nombre
     * @return static|null
     */
    public static function findPorNombre($nombre)
    {
        return self::findOne(['nombre' => $nombre]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

    /**
     * Valida la contraseña
     *
     * @param string $password contraseña a validar
     * @return bool si la contraseña indicada es la correcta para el usuario
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($this->scenario === self::SCENARIO_FORM) {
            $this->password =
                Yii::$app->security->generatePasswordHash($this->passwordForm);
        }
        return true;
    }
}
