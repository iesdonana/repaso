<?php

namespace app\models;

use Yii;
use app\components\UsuariosHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\imagine\Image;

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
    const SCENARIO_FORM_CREATE = 'form-create';
    const SCENARIO_FORM_UPDATE = 'form-update';

    public $passwordForm;
    public $passwordConfirmForm;

    public $foto;

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
        $rules = [
            [['nombre'], 'required'],
            [['password'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['nombre'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 60],
            [['nombre'], 'unique'],
            [
                ['passwordForm', 'passwordConfirmForm'],
                'required',
                'on' => [self::SCENARIO_FORM_CREATE],
            ],
            [['passwordConfirmForm'], 'safe', 'on' => [self::SCENARIO_FORM_UPDATE]],
            [
                ['passwordForm'],
                'compare',
                'compareAttribute' => 'passwordConfirmForm',
                'on' => [self::SCENARIO_FORM_CREATE, self::SCENARIO_FORM_UPDATE],
            ],
            [['email'], 'email'],
            [['foto'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg'],
        ];
        if (Yii::$app->id == 'basic-console' || UsuariosHelper::isAdmin()) {
            $rules[] = [
                ['tipo'],
                'required',
                'on' => [self::SCENARIO_FORM_CREATE, self::SCENARIO_FORM_UPDATE],
            ];
            $rules[] = [['tipo'], 'string', 'max' => 1];
            $rules[] = [['tipo'], 'in', 'range' => ['U', 'A']];
            $rules[] = [['tipo'], function ($attribute, $params, $validator) {
                if ($this->getOldAttribute('tipo') == 'A' &&
                    $this->$attribute == 'U') {
                    if (self::find()->where(['tipo' => 'A'])->count() == 1) {
                        $this->addError(
                            $attribute,
                            'Debe haber al menos un administrador.'
                        );
                    }
                }
            }];
        }
        return $rules;
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
            'passwordForm' => 'Contraseña',
            'passwordConfirmForm' => 'Confirmar contraseña',
        ];
    }

    public function getIsAdmin()
    {
        return $this->tipo === 'A';
    }

    public function getTipoUsuario()
    {
        return UsuariosHelper::listaTipos($this->tipo);
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
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $security = Yii::$app->security;

        if ($insert && $this->scenario === self::SCENARIO_FORM_CREATE) {
            $this->token_val = $security->generateRandomString();
        }

        if ($this->scenario === self::SCENARIO_FORM_CREATE ||
           ($this->scenario === self::SCENARIO_FORM_UPDATE &&
            $this->passwordForm != '')) {
            $this->password =
                $security->generatePasswordHash($this->passwordForm);
        }

        return true;
    }

    public function uploadFile()
    {
        $this->foto = UploadedFile::getInstance($this, 'foto');
        if ($this->foto !== null) {
            $ruta = "fotos/{$this->id}.jpg";
            $this->foto->saveAs($ruta);
            Image::thumbnail($ruta, 100, null)->save($ruta, ['quality' => 50]);
        }
    }

    public function getRutaImagen()
    {
        $ruta = "fotos/{$this->id}.jpg";

        if (file_exists($ruta)) {
            return Url::to("/$ruta");
        } else {
            return Url::to('/fotos/default.jpg');
        }
    }
}
