<?php

namespace infoweb\gallery\controllers;

use rico\yii2images\controllers\ImagesController as BaseImagesController;

use Yii;

use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\StringHelper;

use infoweb\cms\models\Image;
use infoweb\cms\models\ImageSearch;
use infoweb\cms\models\ImageUploadForm;
use infoweb\gallery\models\Gallery;

class GalleryImageController extends BaseImagesController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'upload' => ['post'],
                    'active' => ['post'],
                    'main' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        // An option-set id is provided through the url so store it in a session variable
        if (Yii::$app->request->get('gallery-id'))
            Yii::$app->session->set('gallery.gallery-id', Yii::$app->request->get('gallery-id'));

        // Check for a valid option-set id, otherwise redirect back to the option sets
        if (Yii::$app->session->get('gallery.gallery-id', null) === null)
            return $this->redirect(Url::to(['gallery/index']));

        $gallery = Gallery::findOne(Yii::$app->session->get('gallery.gallery-id'));

        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $gallery->id, Gallery::className());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gallery' => $gallery,
        ]);
    }

    public function actionUpload()
    {
        // @todo Update code
        // http://webtips.krajee.com/ajax-based-file-uploads-using-fileinput-plugin/

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();

            $gallery = Gallery::findOne(Yii::$app->session->get('gallery.gallery-id'));

            $form = new ImageUploadForm;
            $images = UploadedFile::getInstances($form, 'images');

            foreach ($images as $k => $image) {

                $_model = new ImageUploadForm();
                $_model->image = $image;

                if ($_model->validate()) {
                    $path = \Yii::getAlias('@uploadsBasePath') . "/img/{$_model->image->baseName}.{$_model->image->extension}";

                    $_model->image->saveAs($path);

                    // Attach image to model
                    $gallery->attachImage($path);

                } else {
                    foreach ($_model->getErrors('image') as $error) {
                        $gallery->addError('image', $error);
                    }
                }
            }

            if ($form->hasErrors('image')) {
                // @todo Translate
                $response['message'] = count($form->getErrors('image')) . ' of ' . count($images) . ' images not uploaded';
            } else {
                $response['message'] = Yii::t('infoweb/cms', '{n, plural, =1{Image} other{# images}} successfully uploaded', ['n' => count($images)]);
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;

        }
    }

    /**
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $gallery = Gallery::findOne(Yii::$app->session->get('gallery.gallery-id'));

        // Load all the translations
        $model->loadTranslations(array_keys(Yii::$app->params['languages']));

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                // Populate the model with the POST data
                $model->load($post);

                // Populate the translation model for the primary language
                $translationModel = $model->getTranslation(Yii::$app->language);
                $translationModel->alt = $post['ImageLang'][Yii::$app->language]['alt'];
                $translationModel->title = $post['ImageLang'][Yii::$app->language]['title'];
                $translationModel->description = $post['ImageLang'][Yii::$app->language]['description'];

                // Validate the translation model
                $translationValidation = ActiveForm::validate($translationModel);
                $correctedTranslationValidation = [];

                // Correct the keys of the validation
                foreach($translationValidation as $k => $v) {
                    $correctedTranslationValidation[str_replace('imagelang-', "imagelang-{$translationModel->language}-", $k)] = $v;
                }

                // Validate the model and primary translation model
                $response = array_merge(ActiveForm::validate($model), $correctedTranslationValidation);

                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;

                // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();

                // Save the main model
                /*
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                        'gallery' => $gallery,
                    ]);
                }*/

                // Save the translation models
                foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
                    $model->language = $languageId;
                    $model->alt = $post['ImageLang'][$languageId]['alt'];
                    $model->title = $post['ImageLang'][$languageId]['title'];
                    $model->description = $post['ImageLang'][$languageId]['description'];

                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'gallery' => $gallery,
                        ]);
                    }
                }
                $transaction->commit();

                // Set flash message
                $model->language = Yii::$app->language;
                Yii::$app->getSession()->setFlash('image-success', Yii::t('app', '{item} has been updated', ['item' => $model->name]));

                return $this->redirect('index');
            }

        }
        return $this->render('update', [
            'model' => $model,
            'gallery' => $gallery,
        ]);
    }
    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // @todo Add try catch
        $model = $this->findModel($id);
        $model->delete();

        // Set flash message
        $model->language = Yii::$app->language;

        Yii::$app->getSession()->setFlash('image-success', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

        return $this->redirect(['index']);
    }

    /**
     * Deletes existing images.
     * If deletion is successful, the gridview will be refreshed.
     * @param string $id
     * @return mixed
     */
    public function actionMultipleDelete()
    {
        // @todo $ids as param in action?

        $data['status'] = 0;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $ids = Yii::$app->request->post('ids');

            Image::deleteAll(['id' => $ids]);

            $data['message'] = Yii::t('infoweb/cms', '{n, plural, =1{Image} other{# images}} successfully deleted', ['n' => count($ids)]);
            $data['status'] = 1;
        }

        return $data;
    }

    public function actionMultipleDeleteConfirmMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $message = Yii::t('infoweb/cms', 'Are you sure you want to delete {n, plural, =1{this image} other{# images}}?', ['n' => Yii::$app->request->post('ids')]);
        return $message;
    }

    /**
     *
     *
     * @param string $id
     * @return mixed
     */
    public function actionSort()
    {
        $gallery = Gallery::findOne(Yii::$app->session->get('gallery.gallery-id'));

        $images = Image::find()->where(['itemId' => $gallery->id, 'modelName' => 'Gallery'])->orderBy(['position' => SORT_DESC])->all();

        return $this->render('sort', [
            'gallery' => $gallery,
            'images' => $images,
        ]);
    }

    public function actionSortPictures()
    {
        $data['status'] = 0;

        if (Yii::$app->request->isAjax) {

            // Get ids
            $post = Yii::$app->request->post();
            $ids = array_reverse($post['ids']);

            $sqlValues = [];

            // Update positions

            // Build values
            foreach ($ids as $position => $id) {
                $position++;
                $sqlValues[] = "({$id}, {$position})";
            }

            $sqlValues = implode(',', $sqlValues);

            // Execute query
            $connection = Yii::$app->db;
            $command = $connection->createCommand("
                INSERT INTO `image` (`id`,`position`) VALUES {$sqlValues}
                ON DUPLICATE KEY UPDATE `position` = VALUES(`position`)
            ");
            $command->execute();

            // Set responsse format
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Success
            $data['message'] = Yii::t('app', 'The sorting was successfully updated');
            $data['status'] = 1;
        }

        return $data;

    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
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
     * Set as main image
     * @param string $id
     * @return mixed
     */
    public function actionMain()
    {
        // Reset previous main image
        Image::updateAll(['isMain' => 0], ['isMain' => 1, 'itemId' => Yii::$app->session->get('gallery.gallery-id'), 'modelName' => StringHelper::basename(Gallery::className())]);

        // Set new main image
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->isMain = 1;
        $model->active = 1;

        return $model->save();
    }
}
