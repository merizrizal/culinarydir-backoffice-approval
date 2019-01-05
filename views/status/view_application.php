<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusiness */
/* @var $form yii\widgets\ActiveForm */

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this);

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusiness'
]);

$ajaxRequest->form();

$status = Yii::$app->session->getFlash('status');
$message1 = Yii::$app->session->getFlash('message1');
$message2 = Yii::$app->session->getFlash('message2');

if ($status !== null) {

    $notif = new NotificationDialog([
        'status' => $status,
        'message1' => $message1,
        'message2' => $message2,
    ]);
    
    $notif->theScript();
    echo $notif->renderDialog();
}

$this->title = Yii::t('app', 'Application') . ' ' . $model['name'];
$this->params['breadcrumbs'][] = Yii::t('app', 'Application');

echo $ajaxRequest->component(false); ?>

<div class="registry-business-view">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h4><?= $model['membershipType']['name'] ?></h4>
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4><?= Yii::t('app', 'User In Charge') ?> : <?= $model['userInCharge']['full_name'] ?></h4>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Name')) ?><br>
                            <?= $model['name'] ?>
                        </div>
                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Unique Name')) ?><br>
                            <?= $model['unique_name'] ?>
                        </div>
                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Email')) ?><br>
                            <?= $model['email'] ?>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Address Type')) ?><br>
                            <?= $model['address_type'] ?>
                        </div>
                        <div class="col-md-9">
                            <?= Html::label(Yii::t('app', 'Address')) ?><br>
                            <?= $model['address'] ?>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'City ID')) ?><br>
                            <?= $model['city']['name'] ?>
                        </div>

                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'District ID')) ?><br>
                            <?= $model['district']['name'] ?>
                        </div>

                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'Village ID')) ?><br>
                            <?= $model['village']['name'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h4><?= Yii::t('app', 'Application Status') ?></h4>
                </div>

                <div class="x_content">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'action' => ['update-status', 'id' => $model['applicationBusiness']['id'], 'rbid' => $model['id']],
                    ]);

                        foreach ($model['applicationBusiness']['logStatusApprovals'] as $dataLogStatusApproval):
                        
                            if ($dataLogStatusApproval['is_actual']): ?>

                                <div class="row">
                                    <div class="col-md-3">
                                        <h4><strong><?= $dataLogStatusApproval['status_approval_id'] ?> <small><?= $dataLogStatusApproval['statusApproval']['name'] ?></small></strong></h4>
                                    </div>

                                    <div class="col-md-5">

                                        <?= $dataLogStatusApproval['statusApproval']['note'] ?>
                                        
                                        <div class="clearfix" style="margin-bottom: 5px"></div>

                                        <?php
                                        foreach ($dataLogStatusApproval['statusApproval']['statusApprovalActions'] as $dataStatusApprovalAction) {

                                            $btn = ' btn-default';
                                            $url = [$dataStatusApprovalAction['url'], 'id' => $model['id'], 'appBId' => $model['applicationBusiness']['id'], 'actid' => $dataStatusApprovalAction['id'], 'logsaid' => $dataLogStatusApproval['id']];

                                            if (!empty($dataStatusApprovalAction['logStatusApprovalActions'])) {

                                                foreach ($dataStatusApprovalAction['logStatusApprovalActions'] as $value) {

                                                    if ($value['logStatusApproval']['application_business_counter'] == $model['applicationBusiness']['counter']) {
                                                        
                                                        $btn = ' btn-success btn-action';
                                                        $url = '';
                                                        break;
                                                    }
                                                }
                                            }

                                            echo Html::a('<i class="fa fa-external-link-alt"></i> '. $dataStatusApprovalAction['name'], $url, ['class' => 'btn btn-xs' . $btn, 'data-method' => 'post']);
                                        } ?>

                                    </div>

                                    <div class="col-md-4">

                                        <?php
                                        foreach ($dataLogStatusApproval['statusApproval']['statusApprovalRequires0'] as $dataStatusApprovalRequire) {

                                            $flag = true;

                                            if (!empty($dataStatusApprovalRequire['statusApproval']['logStatusApprovals'])) {
                                                
                                                foreach ($dataStatusApprovalRequire['statusApproval']['logStatusApprovals'] as $value) {
                                                    
                                                    if ($value['application_business_counter'] == $model['applicationBusiness']['counter']) {
                                                        
                                                        $flag = false;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($flag) {
                                                
                                                $btn = $dataStatusApprovalRequire['statusApproval']['condition'] ? ' btn-primary' : ' btn-danger';

                                                echo Html::button('<i class="fa fa-arrow-circle-right"></i> ' . $dataStatusApprovalRequire['statusApproval']['id'], [
                                                    'class' => 'btn' . $btn . ' submit-approval',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-status-approval-id' => $dataStatusApprovalRequire['statusApproval']['id'],
                                                    'title' => $dataStatusApprovalRequire['statusApproval']['name']
                                                ]);

                                                echo Html::hiddenInput('status_approval_actual-' . $dataStatusApprovalRequire['status_approval_id'], $dataLogStatusApproval['status_approval_id']);
                                                echo Html::hiddenInput('log_status_approval_actual-' . $dataStatusApprovalRequire['status_approval_id'], $dataLogStatusApproval['id']);
                                                echo '&nbsp;&nbsp;&nbsp;';
                                            }
                                        } ?>

                                    </div>
                                </div>
                                
                            <?php
                            endif;
                        endforeach;

                        echo Html::hiddenInput('status_approval_id', null, ['class' => 'status-approval-id']);

                    ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\YiiAsset']);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $(\'[data-toggle="tooltip"]\').tooltip();

    $(".submit-approval").on("click", function() {

        $(".status-approval-id").val($(this).data("status-approval-id"));
        $("#registry-business-form").trigger("submit");
    });
';

$this->registerJs($jscript); ?>