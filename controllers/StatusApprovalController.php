<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\ApplicationBusiness;
use core\models\LogStatusApproval;
use yii\filters\VerbFilter;


/**
 * StatusApprovalController implements the CRUD actions for Status model.
 */
class StatusApprovalController extends \backoffice\controllers\BaseController
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

    public function actionResubmit($appBId) {

        $flag = false;

        if (($flag = LogStatusApproval::updateAll(['is_actual' => 0], ['application_business_id' => $appBId]) > 0)) {

            $modelApplicationBusiness = ApplicationBusiness::findOne($appBId);

            $modelApplicationBusiness->counter = $modelApplicationBusiness->counter + 1;

            if (($flag = $modelApplicationBusiness->save())) {

                $modelLogStatusApproval = new LogStatusApproval();
                $modelLogStatusApproval->application_business_id = $appBId;
                $modelLogStatusApproval->status_approval_id = 'PNDG';
                $modelLogStatusApproval->application_business_counter = $modelApplicationBusiness->counter;
                $modelLogStatusApproval->is_actual = 1;

                $flag = $modelLogStatusApproval->save();
            }
        }

        return $flag;
    }

    public function actionApprove() {

        return false;
    }
}