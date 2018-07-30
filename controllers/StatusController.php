<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use yii\filters\VerbFilter;


/**
 * StatusController implements the CRUD actions for Status model.
 */
class StatusController extends \backoffice\controllers\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
            $this->getAccess(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [

                    ],
                ],
            ]);
    }

    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = $this->ajaxLayout;
        }

        return $this->render('index', [

        ]);
    }
}
