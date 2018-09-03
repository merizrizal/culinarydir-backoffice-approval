<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\RegistryBusiness;
use core\models\search\RegistryBusinessSearch;
use core\models\ApplicationBusiness;
use core\models\StatusApproval;
use core\models\StatusApprovalRequire;
use core\models\LogStatusApproval;
use sycomponent\AjaxRequest;
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
                        'update-status' => ['POST'],
                    ],
                ],
            ]);
    }

    public function actionPndgApplication()
    {
        return $this->indexApplication('PNDG', Yii::t('app', 'Pending'));
    }

    public function actionIcorctApplication()
    {
        return $this->indexApplication('ICORCT', Yii::t('app', 'Incorrect'));
    }

    public function actionViewApplication($id, $appBId)
    {
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
                'applicationBusiness.logStatusApprovals.statusApproval',
                'applicationBusiness',
                'applicationBusiness.logStatusApprovals',
                'applicationBusiness.logStatusApprovals.logStatusApprovalActions',
                'applicationBusiness.logStatusApprovals.statusApproval',
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalRequires0',
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalRequires0.statusApproval status_approval_req',
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalRequires0.statusApproval.logStatusApprovals log_status_approval_req' => function($query) use ($appBId) {
                    $query->andOnCondition(['log_status_approval_req.application_business_id' => $appBId]);
                },
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalActions',
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalActions.logStatusApprovalActions log_status_approval_action_act',
                'applicationBusiness.logStatusApprovals.statusApproval.statusApprovalActions.logStatusApprovalActions.logStatusApproval log_status_approval_act' => function($query) use ($appBId) {
                    $query->andOnCondition(['log_status_approval_act.application_business_id' => $appBId]);
                },
            ])
            ->andWhere(['registry_business.id' => $id])
            ->asArray()->one();

        return $this->render('view_application', [
            'model' => $model
        ]);
    }

    public function actionUpdateStatus($id, $rbid) {

        if (!empty(($post = Yii::$app->request->post()))) {

            $modelApplicationBusiness = ApplicationBusiness::find()
                ->joinWith([
                    'logStatusApprovals',
                    'logStatusApprovals.logStatusApprovalActions',
                    'logStatusApprovals.logStatusApprovalActions.logStatusApproval log_status_approval_act',
                ])
                ->andWhere(['application_business.id' => $id])
                ->asArray()->one();

            $modelStatusApproval = StatusApproval::find()
                ->joinWith([
                    'statusApprovalRequires',
                    'statusApprovalRequireActions',
                    'statusApprovalRequireActions.statusApprovalAction',
                ])
                ->andWhere(['status_approval.id' => $post['status_approval_id']])
                ->asArray()->one();

            $require = [];
            $err1 = '';
            foreach ($modelStatusApproval['statusApprovalRequires'] as $key => $dataStatusApprovalRequire) {
                $require[$key] = false;

                foreach ($modelApplicationBusiness['logStatusApprovals'] as $dataLogStatusApproval) {
                    if ($dataStatusApprovalRequire['require_status_approval_id'] == $dataLogStatusApproval['status_approval_id'] && $dataLogStatusApproval['is_actual']) {
                        $require[$key] = true;
                        break;
                    }
                }

                if (!$require[$key])
                    $err1 .= $dataStatusApprovalRequire['require_status_approval_id'] . ' ';
            }

            $result = true;
            foreach ($require as $value) {
                $result = $result && $value;
            }

            $require = [];
            $err2 = '';
            foreach ($modelStatusApproval['statusApprovalRequireActions'] as $key => $dataStatusApprovalRequireAction) {
                $require[$key] = false;

                foreach ($modelApplicationBusiness['logStatusApprovals'] as $dataLogStatusApproval) {

                    foreach ($dataLogStatusApproval['logStatusApprovalActions'] as $dataLogStatusApprovalAction) {

                        if ($dataStatusApprovalRequireAction['status_approval_action_id'] == $dataLogStatusApprovalAction['status_approval_action_id'] && $dataLogStatusApprovalAction['logStatusApproval']['application_business_counter'] == $modelApplicationBusiness['counter']) {
                            $require[$key] = true;
                            break;
                        }
                    }
                }

                if (!$require[$key])
                    $err2 .= $dataStatusApprovalRequireAction['statusApprovalAction']['name'] . ', ';
            }

            foreach ($require as $value) {
                $result = $result && $value;
            }

            if ($result) {

                $transaction = Yii::$app->db->beginTransaction();
                $flag = false;

                $modelLogStatusApproval = new LogStatusApproval();
                $modelLogStatusApproval->application_business_id = $modelApplicationBusiness['id'];
                $modelLogStatusApproval->status_approval_id = $post['status_approval_id'];
                $modelLogStatusApproval->is_actual = true;
                $modelLogStatusApproval->application_business_counter = $modelApplicationBusiness['counter'];

                if (($flag = $modelLogStatusApproval->save())) {

                    $statusActual = $post['status_approval_actual-' . $post['status_approval_id']];

                    $modelStatusApprovalActual = StatusApproval::find()
                        ->andWhere(['id' => $statusActual])
                        ->asArray()->one();

                    if (($flag = !empty($modelStatusApprovalActual))) {

                        $result = true;

                        if ($modelStatusApprovalActual['branch'] > 1) {

                            $checkLogStatusApproval = LogStatusApproval::find()
                                ->andWhere(['application_business_id' => $modelApplicationBusiness['id']])
                                ->andWhere(['!=', 'status_approval_id', $statusActual])
                                ->andWhere(['application_business_counter' => $modelApplicationBusiness['counter']])
                                ->asArray()->all();

                            $modelStatusApprovalRequire = StatusApprovalRequire::find()
                                ->andWhere(['require_status_approval_id' => $statusActual])
                                ->asArray()->all();

                            $require = [];
                            foreach ($modelStatusApprovalRequire as $key => $$dataStatusApprovalRequire) {
                                $require[$key] = false;

                                foreach ($checkLogApproval as $dataCheckLogStatusApproval) {
                                    if ($$dataStatusApprovalRequire['status_approval_id'] == $dataCheckLogStatusApproval['status_approval_id']) {
                                        $require[$key] = true;
                                        break;
                                    }
                                }
                            }

                            foreach ($require as $value) {
                                $result = $result && $value;
                            }
                        }

                        if (($flag = $result) && $modelStatusApproval['branch'] != 0) {
                            $modelLogStatusApproval = LogStatusApproval::find()
                                ->andWhere(['status_approval_id' => $statusActual])
                                ->andWhere(['application_business_id' => $modelApplicationBusiness['id']])
                                ->andWhere(['application_business_counter' => $modelApplicationBusiness['counter']])
                                ->one();

                            $modelLogStatusApproval->is_actual = 0;

                            $flag = $modelLogStatusApproval->save();
                        }

                        if ($modelStatusApproval['branch'] == 0) {

                            if ($modelStatusApproval['status'] != 'Finished-Fail') {

                                $requireStatusApprovalId = [];
                                foreach ($modelStatusApproval['statusApprovalRequires'] as $dataStatusApprovalRequire) {
                                    $requireStatusApprovalId[] = $dataStatusApprovalRequire['require_status_approval_id'];
                                }

                                $checkLogStatusApproval = LogStatusApproval::find()
                                    ->andWhere(['application_business_id' => $modelApplicationBusiness['id']])
                                    ->andWhere(['status_approval_id' => $requireStatusApprovalId])
                                    ->asArray()->all();

                                $result = true;
                                foreach ($checkLogStatusApproval as $dataCheckLogStatusApproval) {
                                    $result = $result && $dataCheckLogStatusApproval['is_actual'];
                                }

                                if ($result) {
                                    $flag = LogStatusApproval::updateAll(['is_actual' => false], ['AND', ['application_business_id' => $modelApplicationBusiness['id'], 'status_approval_id' => $requireStatusApprovalId]]) > 0;
                                }
                            } else {
                                $flag = LogStatusApproval::updateAll(['is_actual' => false], 'is_actual = TRUE AND status_approval_id != :said AND application_business_id = :appbid', ['said' => $post['status_approval_id'], 'appbid' => $modelApplicationBusiness['id']]) > 0;
                            }
                        }
                    }
                }

                if ($flag) {
                    if (!empty($modelStatusApproval['execute_action'])) {
                        $flag = $this->run($modelStatusApproval['execute_action'], ['appBId' => $modelApplicationBusiness['id'], 'regBId' => $rbid]);
                    }
                }

                if ($flag) {

                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Update Status Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses update status sukses. Data telah berhasil disimpan.');

                    $transaction->commit();
                } else {

                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Status Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses update status gagal. Data gagal disimpan.');

                    $transaction->rollBack();
                }
            } else {

                $msg = '';

                if (!empty($err1)) {
                    $msg = 'Data ini belum melewati status: (<b>' . $err1 . '</b>)';

                    if (!empty($err2))
                        $msg .= ' dan ';
                }

                if (!empty($err2)) {
                    $msg .= 'Data ini belum melewati action: (<b>' . trim($err2, ', ') . '</b>)';
                }

                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update ' . $post['status_approval_id'] . ' Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update status gagal. ' . $msg);
            }

            return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['/approval/status/view-application', 'id' => $rbid, 'appBId' => $modelApplicationBusiness['id']]));
        }
    }

    private function indexApplication($statusApproval, $title) {

        $searchModel = new RegistryBusinessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['log_status_approval.status_approval_id' => $statusApproval])
            ->andWhere(['log_status_approval.is_actual' => 1])
            ->andWhere('registry_business.application_business_counter = application_business.counter')
            ->distinct();

        Yii::$app->formatter->timeZone = 'Asia/Jakarta';

        return $this->render('list_application', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => $title,
            'statusApproval' => $statusApproval,
        ]);
    }
}
