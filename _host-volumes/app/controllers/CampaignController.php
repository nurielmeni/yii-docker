<?php

namespace app\controllers;

use Yii;
use app\models\Campaign;
use yii\data\ActiveDataProvider;
use app\controllers\ElbitController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends ElbitController
{
    /**
     * {@inheritdoc}
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
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'view', 'update', 'delete', 'index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = 'main_admin';
        return parent::beforeAction($action);
    }

    /**
     * Lists all Campaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Campaign::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Campaign model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Campaign([
            'start_date' => date('d/m/Y', time()),
            'fbf' => 0,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->image);
            }

            $image = UploadedFile::getInstance($model, 'mobile_image');
            if ($image) {
                $model->mobile_image = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->mobile_image);
            }

            $image = UploadedFile::getInstance($model, 'logo');
            if ($image) {
                $model->logo = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->logo);
            }

            if ($model->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Campaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     **/
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->image);
            }

            $image = UploadedFile::getInstance($model, 'mobile_image');
            if ($image) {
                $model->mobile_image = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->mobile_image);
            }

            $image = UploadedFile::getInstance($model, 'logo');
            if ($image) {
                $model->logo = 'uploads/images/' . $image->baseName . '.' . $image->extension;
                $image->saveAs($model->logo);
            }

            if ($model->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Campaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
