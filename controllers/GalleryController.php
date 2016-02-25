<?php

namespace infoweb\gallery\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\base\Exception;

use infoweb\cms\models\Image;
use infoweb\gallery\models\Gallery;
use infoweb\gallery\models\Lang;
use infoweb\gallery\models\GallerySearch;

/**
 * GalleryController implements the CRUD actions for Gallery model.
 */
class GalleryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'duplicate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Gallery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GallerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Orders the model.
     * @param string $id
     * @return mixed
     */
    public function actionOrder()
    {
        $post = Yii::$app->request->post();

        if (isset($post['key'], $post['pos'])) {
            $this->findModel($post['key'])->order($post['pos']);
        }
    }


    /**
     * Creates a new Gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Create the model with default values
        $model = new Gallery([
            'active' => 1,
            'date' => date('U')
        ]);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

                // Normal request, save models
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('create', $params);
    }

    /**
     * Updates an existing Gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Load the model
        $model = $this->findModel($id);

        // The view params
        $params = $this->getDefaultViewParams($model);

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                return $this->validateModel($model, $post);

                // Normal request, save models
            } else {
                return $this->saveModel($model, $post);
            }
        }

        return $this->render('update', $params);
    }

    /**
     * Deletes an existing Gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $name = $model->name;

        try {

            $transaction = Yii::$app->db->beginTransaction();

            // Remove all images first
            $model->removeImages();

            if (!$model->delete()) {
                throw new Exception(Yii::t('app', 'Error while deleting the node'));
            }

            $transaction->commit();

        } catch (Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('news-error', $e->getMessage());

            return $this->redirect(['index']);
        }

        // Set flash message
        Yii::$app->getSession()->setFlash('gallery', Yii::t('app', '"{item}" has been deleted', ['item' => $name]));

        return $this->redirect('index');
    }

    /**
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Set active state
     * @param string $id
     * @return mixed
     */
    public function actionActive()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->active = ($model->active == 1) ? 0 : 1;

        return $model->save();
    }

    /**
     * Returns an array of the default params that are passed to a view
     *
     * @param News $model The model that has to be passed to the view
     * @return array
     */
    protected function getDefaultViewParams($model = null)
    {
        return [
            'model'  => $model,
        ];
    }

    /**
     * Performs validation on the provided model and $_POST data
     *
     * @param \infoweb\pages\models\Page $model The page model
     * @param array $post The $_POST data
     * @return array
     */
    protected function validateModel($model, $post)
    {
        $languages = Yii::$app->params['languages'];

        // Populate the model with the POST data
        $model->load($post);

        // Create an array of translation models and populate them
        $translationModels = [];
        // Insert
        if ($model->isNewRecord) {
            foreach ($languages as $languageId => $languageName) {
                $translationModels[$languageId] = new Lang(['language' => $languageId]);
            }
            // Update
        } else {
            $translationModels = ArrayHelper::index($model->getTranslations()->all(), 'language');
        }
        Model::loadMultiple($translationModels, $post);

        // Validate the model and translation
        $response = array_merge(
            ActiveForm::validate($model),
            ActiveForm::validateMultiple($translationModels)
        );

        // Return validation in JSON format
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $response;
    }

    protected function saveModel($model, $post)
    {
        // Wrap everything in a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        // Get the params
        $params = $this->getDefaultViewParams($model);

        // Validate the main model
        if (!$model->load($post)) {
            return $this->render($this->action->id, $params);
        }

        // Add the translations
        foreach (Yii::$app->request->post('Lang', []) as $language => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($language)->$attribute = $translation;
            }
        }

        // Save the main model
        if (!$model->save()) {
            return $this->render($this->action->id, $params);
        }

        $transaction->commit();

        // Set flash message
        if ($this->action->id == 'create') {
            Yii::$app->getSession()->setFlash('gallery', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));
        } else {
            Yii::$app->getSession()->setFlash('gallery', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));
        }

        // Take appropriate action based on the pushed button
        if (isset($post['save-close'])) {
            // No referrer
            if (Yii::$app->request->get('referrer') != 'menu-items')
                return $this->redirect(['index']);
            else
                return $this->redirect(['/menu/menu-item/index']);
        } elseif (isset($post['save-add'])) {
            return $this->redirect(['create']);
        } else {
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }
}


