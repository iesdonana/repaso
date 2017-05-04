<?php

namespace app\controllers;

use Yii;
use app\models\Aula;
use app\models\Ordenador;
use app\models\Dispositivo;
use app\models\RegistroDisp;
use app\models\DispositivoForm;
use app\models\DispositivoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DispositivosController implements the CRUD actions for Dispositivo model.
 */
class DispositivosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBorrarHistorial()
    {
        $id = Yii::$app->request->post('id');

        if ($id === null || Dispositivo::findOne($id) === null) {
            throw new NotFoundHttpException('Dispositivo no encontrado');
        }

        RegistroDisp::deleteAll(['dispositivo_id' => $id]);
    }

    /**
     * Lists all Dispositivo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DispositivoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dispositivo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Dispositivo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dispositivo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dispositivo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $disp = $this->findModel($id);
        $model = new DispositivoForm($disp);
        $model->setOldAttributes($disp->getOldAttributes());
        $model->ubicacion_id = $model->codificarUbicacion();
        $ubicaciones = $this->listaUbicaciones();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'ubicaciones' => $ubicaciones,
            ]);
        }
    }

    /**
     * Deletes an existing Dispositivo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dispositivo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dispositivo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dispositivo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function listaUbicaciones()
    {
        $aulas = Aula::find()
            ->select("den_aula, ('a' || id) as id")
            ->indexBy('id')
            ->column();
        $ordenadores = Ordenador::find()
            ->select("(marca_ord || ' ' || modelo_ord) as nombre, ('o' || id) as id")
            ->indexBy('id')
            ->column();
        return [
            'Aulas' => $aulas,
            'Ordenadores' => $ordenadores,
        ];
    }
}
