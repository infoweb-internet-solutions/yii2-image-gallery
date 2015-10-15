<?php
namespace infoweb\gallery\widgets;

use infoweb\cms\helpers\ArrayHelper;
use Yii;
use yii\bootstrap\Widget;
use infoweb\gallery\models\Gallery as GalleryModel;

class Gallery extends Widget
{
    public $template = '_list';
    public $detailTemplate = '_single';
    public $class = 'col-sm-8';

    public function init()
    {
        parent::init();

    }

    /**
     * Get the requested model
     *
     * @return array|null|\yii\db\ActiveRecord|static
     */
    private function findModel()
    {
        $model = null;

        if (Yii::$app->request->get('slug', null) !== null) {
            $model = GalleryModel::find()->joinWith('translations')->where(['slug' => Yii::$app->request->get('slug'), 'active' => 1])->one();
        }

        return $model;
    }

    /**
     * @return null|string
     */
    public function run()
    {
        $model = $this->findModel();

        if (isset($model)) {
            return $this->render($this->detailTemplate, ['model' => $model, 'class' => $this->class]);
        } else {

            $models = ArrayHelper::indexRecursive(GalleryModel::find()->where(['active' => 1])->orderby(['position' => SORT_DESC])->orderBy(['date' => SORT_DESC])->all(), function($element) {
                $year = date('Y', $element['date']);
                $month = date('m', $element['date']);
                
                // Create the index based on the date (a period runs from 01/07/current-year till 30/06/next-year)
                if (in_array($month, ['07', '08', '09', '10', '11', '12'])) {
                    return $year.'-'.($year+1);    
                } else {
                    return ($year-1).'-'.$year;    
                }
            });

            return $this->render($this->template, ['models' => $models, 'class' => $this->class]);
        }
    }
}
