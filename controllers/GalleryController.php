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
        // Load languages
        $languages = Yii::$app->params['languages'];

        // Set the category root
        $root = 1;

        // Load the model
        $model = new Gallery(['active' => 1, 'date' => date('U')]);

        try {

            if (Yii::$app->request->getIsPost()) {

                $post = Yii::$app->request->post();

                // Ajax request, validate the models
                if (Yii::$app->request->isAjax) {

                    // Populate the model with the POST data
                    $model->load($post);

                    // Create an array of translation models
                    $translationModels = [];

                    foreach ($languages as $languageId => $languageName) {
                        $translationModels[$languageId] = new Lang(['language' => $languageId]);
                    }

                    // Populate the translation models
                    Model::loadMultiple($translationModels, $post);

                    // Validate the model and translation models
                    $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                    // Return validation in JSON format
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;

                    // Normal request, save models
                } else {
                    // Wrap the everything in a database transaction
                    $transaction = Yii::$app->db->beginTransaction();

                    // Save the main model
                    if (!$model->load($post) || !$model->save()) {
                        throw new Exception(Yii::t('app', 'Failed to save the node'));
                    }

                    // Save the translations
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        // Set the translation language and attributes
                        $model->language    = $languageId;
                        $model->name        = $data['name'];
                        $model->description = $data['description'];

                        if (!$model->saveTranslation()) {
                            throw new Exception(Yii::t('app', 'Failed to save the translation'));
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('gallery', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));

                    // Take appropriate action based on the pushed button
                    if (isset($post['close'])) {
                        return $this->redirect('index');
                    } elseif (isset($post['new'])) {
                        return $this->redirect(['create']);
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }
            }
        } catch (Exception $e) {

            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Set flash message
            Yii::$app->getSession()->setFlash('gallery-error', $e->getMessage());
        }

        return $this->render('create', [
            'model'         => $model,
        ]);
    }

    /**
     * Updates an existing Gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Load languages
        $languages = Yii::$app->params['languages'];

        // Set the category root
        $root = 1;

        // Load the model
        $model = $this->findModel($id);

        try {

            if (Yii::$app->request->getIsPost()) {

                $post = Yii::$app->request->post();

                // Ajax request, validate the models
                if (Yii::$app->request->isAjax) {

                    // Populate the model with the POST data
                    $model->load($post);

                    // Create an array of translation models
                    $translationModels = [];

                    foreach ($languages as $languageId => $languageName) {
                        $translationModels[$languageId] = $model->getTranslation($languageId);
                    }

                    // Populate the translation models
                    Model::loadMultiple($translationModels, $post);

                    // Validate the model and translation models
                    $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                    // Return validation in JSON format
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;

                    // Normal request, save models
                } else {
                    // Wrap the everything in a database transaction
                    $transaction = Yii::$app->db->beginTransaction();

                    // Save the main model
                    if (!$model->load($post) || !$model->save()) {
                        throw new Exception(Yii::t('app', 'Failed to update the node'));
                    }

                    // Save the translation models
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        $model->language    = $languageId;
                        $model->name        = $data['name'];
                        $model->description = $data['description'];

                        if (!$model->saveTranslation()) {
                            throw new Exception(Yii::t('app', 'Failed to update the translation'));
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('gallery', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));

                    // Take appropriate action based on the pushed button
                    if (isset($post['close'])) {
                        return $this->redirect('index');
                    } elseif (isset($post['new'])) {
                        return $this->redirect(['create']);
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }
            }
        } catch (Exception $e) {

            if (isset($transaction)) {
                $transaction->rollBack();
            }
            // Set flash message
            Yii::$app->getSession()->setFlash('gallery-error', $e->getMessage());
        }

        return $this->render('update', [
            'model'         => $model,
        ]);
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

        // Remove all images first
        $model->removeImages();

        // Remove model
        $model->delete();

        // Set flash message
        Yii::$app->getSession()->setFlash('commission', Yii::t('app', '"{item}" has been deleted', ['item' => $model->name]));

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
}


