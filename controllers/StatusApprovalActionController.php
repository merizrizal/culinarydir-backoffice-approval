<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\LogStatusApprovalAction;
use sycomponent\AjaxRequest;
use yii\filters\VerbFilter;


/**
 * StatusApprovalActionController implements the CRUD actions for Status model.
 */
class StatusApprovalActionController extends \backoffice\controllers\BaseController
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
                        'fix-incorrect' => ['POST'],
                    ],
                ],
            ]);
    }

    public function actionFixIncorrect($id, $appBId, $logsaid, $actid) {

        $modelLogStatusApprovalAction = new LogStatusApprovalAction();
        $modelLogStatusApprovalAction->log_status_approval_id = $logsaid;
        $modelLogStatusApprovalAction->status_approval_action_id = $actid;

        if ($modelLogStatusApprovalAction->save()) {

            Yii::$app->session->setFlash('status', 'success');
            Yii::$app->session->setFlash('message1', Yii::t('app', 'Create Data Is Success'));
            Yii::$app->session->setFlash('message2', Yii::t('app', 'Create data process is success. Data has been saved'));
        } else {

            Yii::$app->session->setFlash('status', 'danger');
            Yii::$app->session->setFlash('message1', Yii::t('app', 'Create Data Is Fail'));
            Yii::$app->session->setFlash('message2', Yii::t('app', 'Create data process is fail. Data fail to save'));
        }

        return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['/approval/status/view-application', 'id' => $id, 'appBId' => $appBId]));
    }
}
