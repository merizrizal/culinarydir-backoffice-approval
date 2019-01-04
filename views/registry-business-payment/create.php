<?php

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusinessPayment */
/* @var $modelRegistryBusiness core\models\RegistryBusiness */
/* @var $appBId backoffice\modules\approval\controllers\RegistryBusinessPaymentController */
/* @var $actid backoffice\modules\approval\controllers\RegistryBusinessPaymentController */
/* @var $logsaid backoffice\modules\approval\controllers\RegistryBusinessPaymentController */

$this->title = 'Create ' . Yii::t('app', 'Payment Methods');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = ['label' => $modelRegistryBusiness['name'], 'url' => ['status-approval-action/check-set-picture', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Methods'), 'url' => ['index', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="registry-business-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelRegistryBusiness' => $modelRegistryBusiness,
        'appBId' => $appBId,
        'logsaid' => $logsaid,
        'actid' => $actid
    ]) ?>

</div>