<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\LogStatusApprovalAction;
use core\models\RegistryBusiness;
use core\models\RegistryBusinessImage;
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
            Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
            Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
        } else {

            Yii::$app->session->setFlash('status', 'danger');
            Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
            Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
        }

        return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['/approval/status/view-application', 'id' => $id, 'appBId' => $appBId]));
    }

    public function actionCheckSetPicture($id, $appBId, $logsaid, $actid) {

        $model = RegistryBusiness::find()
            ->joinWith([
               'membershipType',
                'city',
                'district',
                'village',
                'userInCharge',
                'registryBusinessCategories' => function($query) {
                    $query->andOnCondition(['registry_business_category.is_active' => true]);
                },
                'registryBusinessCategories.category',
                'registryBusinessProductCategories' => function($query) {
                    $query->andOnCondition(['registry_business_product_category.is_active' => true]);
                },
                'registryBusinessHours' => function($query) {
                    $query->andOnCondition(['registry_business_hour.is_open' => true])
                        ->orderBy(['registry_business_hour.day' => SORT_ASC]);
                },
                'registryBusinessProductCategories.productCategory',
                'registryBusinessFacilities' => function($query) {
                    $query->andOnCondition(['registry_business_facility.is_active' => true]);
                },
                'registryBusinessFacilities.facility',
                'registryBusinessImages',
                'applicationBusiness',
                'applicationBusiness.logStatusApprovals' => function($query) {
                    $query->andOnCondition(['log_status_approval.is_actual' => true]);
                },
                'applicationBusiness.logStatusApprovals.statusApproval',
            ])
            ->andWhere(['registry_business.id' => $id])
            ->asArray()->one();

        if (!empty($post = Yii::$app->request->post()) && !empty($post['check_set_picture'])) {

            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;

            $modelRegistryBusinessImage = RegistryBusinessImage::find()
                ->andWhere(['registry_business_id' => $id])
                ->all();

            foreach ($modelRegistryBusinessImage as $dataRegistryBusinessImage) {

                $dataRegistryBusinessImage->type = !empty($post['profile'][$dataRegistryBusinessImage->id]) ? 'Profile' : 'Gallery';
                $dataRegistryBusinessImage->is_primary = !empty($post['thumbnail']) && $post['thumbnail'] == $dataRegistryBusinessImage->id ? true : false;

                if (!($flag = $dataRegistryBusinessImage->save())) {
                    break;
                }
            }

            if ($flag) {

                $modelLogStatusApprovalAction = new LogStatusApprovalAction();
                $modelLogStatusApprovalAction->log_status_approval_id = $logsaid;
                $modelLogStatusApprovalAction->status_approval_action_id = $actid;

                $flag = $modelLogStatusApprovalAction->save();
            }

            if ($flag) {

                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));

                $transaction->commit();

                return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['/approval/status/view-application', 'id' => $id, 'appBId' => $appBId]));
            } else {

                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));

                $transaction->rollBack();
            }
        }

        return $this->render('check_set_picture', [
            'model' => $model,
            'id' => $id,
            'appBId' => $appBId,
        ]);
    }
}
