<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use sycomponent\AjaxRequest;
use sycomponent\ModalDialog;
use sycomponent\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusinessDelivery */
/* @var $appBId backoffice\modules\approval\controllers\RegistryBusinessDeliveryController */
/* @var $actid backoffice\modules\approval\controllers\RegistryBusinessDeliveryController */
/* @var $logsaid backoffice\modules\approval\controllers\RegistryBusinessDeliveryController */

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusinessDelivery',
]);

$ajaxRequest->view();

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

$this->title = $model->deliveryMethod->delivery_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $model->registry_business_id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = ['label' => $model->registryBusiness->name, 'url' => ['status-approval-action/check-set-picture', 'id' => $model->registry_business_id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Delivery Methods'), 'url' => ['index', 'id' => $model->registry_business_id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = $this->title;

echo $ajaxRequest->component(); ?>

<div class="registry-business-delivery-view">

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_content">

                    <?= Html::a('<i class="fa fa-upload"></i> ' . 'Create', ['create', 'id' => $model->registry_business_id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], [ 'class' => 'btn btn-success']) ?>

                    <?= Html::a('<i class="fa fa-pencil-alt"></i> ' . 'Edit', ['update', 'id' => $model->id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']) ?>

                    <?= Html::a('<i class="fa fa-trash-alt"></i> ' . 'Delete', ['delete', 'id' => $model->id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], [
                            'id' => 'delete',
                            'class' => 'btn btn-danger',
                            'style' => 'color:white',
                            'data-not-ajax' => 1,
                            'model-id' => $model->id,
                            'model-name' => $model->deliveryMethod->delivery_name,
                        ]) ?>

                    <?= Html::a('<i class="fa fa-times"></i> ' . 'Cancel', ['index', 'id' => $model->registry_business_id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default']) ?>

                    <div class="clearfix" style="margin-top: 15px"></div>

                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table'
                            ],
                            'attributes' => [
                                'deliveryMethod.delivery_name',
                                'note:ntext',
                                [
                                    'attribute' => 'is_active',
                                    'format' => 'raw',
                                    'value' => Html::checkbox('is_active', $model->is_active, ['value' => $model->is_active, 'disabled' => 'disabled']),
                                ],
                            ],
                        ]) ?>

                </div>
            </div>
        </div>
    </div>

</div>

<?php
$modalDialog = new ModalDialog([
    'clickedComponent' => 'a#delete',
    'modelAttributeId' => 'model-id',
    'modelAttributeName' => 'model-name',
]);

$modalDialog->theScript(false);

echo $modalDialog->renderDialog();

$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/skins/all.css', ['depends' => 'yii\web\YiiAsset']);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/icheck.min.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = Yii::$app->params['checkbox-radio-script']()
    . '$(".iCheck-helper").parent().removeClass("disabled");
';

$this->registerJs($jscript); ?>