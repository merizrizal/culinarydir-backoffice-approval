<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;
use backoffice\components\AppComponent;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusiness */
/* @var $id string */
/* @var $appBId string */
/* @var $actid string */
/* @var $logsaid string */
/* @var $form yii\widgets\ActiveForm */

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusiness',
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

$this->title = 'Check & Set ' . Yii::t('app', 'Business Information') . ' : ' . $model['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = $model['name'];

echo $ajaxRequest->component(); ?>

<div class="registry-business-form">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_content">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'options' => [

                        ]
                    ]);

                        echo Html::hiddenInput('check_set_business_info', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-business-info', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

                        <div class="clearfix" style="margin-top: 15px"></div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'Business Information') ?></strong></h4>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="row mb-20">
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Name')) ?><br>
                                <?= $model['name'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Unique Name')) ?><br>
                                <?= $model['unique_name'] ?>
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Address Type')) ?><br>
                                <?= $model['address_type'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Address')) ?><br>
                                <?= $model['address'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Address Info')) ?><br>
                                <?= $model['address_info'] ?>
                            </div>
                        </div>

                        <div class="row mb-20">
                        	<div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'City ID')) ?><br>
                                <?= $model['city']['name'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'District ID')) ?><br>
                                <?= $model['district']['name'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Village ID')) ?><br>
                                <?= $model['village']['name'] ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Coordinate')) ?><br>
                                <?= $model['coordinate'] ?>
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Email')) ?><br>
                                <?= !empty($model['email']) ? $model['email'] : '-' ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Phone1')) ?><br>
                                <?= !empty($model['phone1']) ? $model['phone1'] : '-' ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Phone2')) ?><br>
                                <?= !empty($model['phone2']) ? $model['phone2'] : '-' ?>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <?= Html::label(Yii::t('app', 'Phone3')) ?><br>
                                <?= !empty($model['phone3']) ? $model['phone3'] : '-' ?>
                            </div>
                        </div>
                        
                        <div class="row mb-20">
                            <div class="col-xs-12">
                                <?= Html::label(Yii::t('app', 'About')) ?><br>
                                <?= !empty($model['about']) ? $model['about'] : '-' ?>
                            </div>
                        </div>
                        
                        <div class="row mb-20">
                            <div class="col-xs-12">
                                <?= Html::label(Yii::t('app', 'Note')) ?><br>
                                <?= !empty($model['note']) ? $model['note'] : '-' ?>
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-xs-12">

                                <?php
                                echo Html::label(Yii::t('app', 'Business Location')) . '<br>';
                                
                                $coordinate = explode(',', $model['coordinate']);

                                if (!empty($coordinate) && count($coordinate) > 1) {

                                    $appComponent = new AppComponent;

                                    echo $appComponent->map([
                                        'latitude' => $coordinate[0],
                                        'longitude' => $coordinate[1],
                                    ]);
                                } ?>

                            </div>
                        </div>
                        
                        <hr>
                        
                        <?php
                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-business-info', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                    
                    ActiveForm::end(); ?>
                        
                </div>
            </div>
        </div>
    </div>
</div>