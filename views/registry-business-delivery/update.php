<?php

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusinessDelivery */
/* @var $modelRegistryBusiness core\models\RegistryBusiness */
/* @var $appBId string */
/* @var $actid string */
/* @var $logsaid string */

$this->title = 'Update ' . Yii::t('app', 'Delivery Methods');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = ['label' => $modelRegistryBusiness['name'], 'url' => ['status-approval-action/check-set-picture', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Delivery Methods'), 'url' => ['index', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = ['label' => $model->deliveryMethod->delivery_name, 'url' => ['view', 'id' => $model->id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="registry-business-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelRegistryBusiness' => $modelRegistryBusiness,
        'appBId' => $appBId,
        'logsaid' => $logsaid,
        'actid' => $actid
    ]) ?>

</div>
