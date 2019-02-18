<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;

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

$this->title = 'Check & Set ' . Yii::t('app', 'Delivery Methods') . ' : ' . $model['name'];
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

                        echo Html::hiddenInput('check_set_delivery_method', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business-delivery/index', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

                        <div class="clearfix" style="margin-top: 15px"></div>
                        
                        <div class="row">
                            <div class="col-sm-3 col-xs-5">
                                <?= Html::label(Yii::t('app', 'Delivery Methods')) ?>
                            </div>
                            <div class="col-sm-9 col-xs-7">
                                <?= Html::label(Yii::t('app', 'Note')) ?>
                            </div>
                        </div>
                        
                        <hr>
    					
    					<div class="row">
    					
                            <?php
                            if (!empty($model['registryBusinessDeliveries'])) {
                                
                                foreach ($model['registryBusinessDeliveries'] as $dataRegistryBusinessDelivery) {
    
                                    echo '
                                        <div class="col-sm-3 col-xs-5 mb-10">
                                            ' . $dataRegistryBusinessDelivery['deliveryMethod']['delivery_name'] . '
                                        </div>
                                        <div class="col-sm-9 col-xs-7 mb-10">
                                            ' . (!empty($dataRegistryBusinessDelivery['note']) ? $dataRegistryBusinessDelivery['note'] : '-') . '
                                        </div>
                                        <div class="col-sm-offset-3 col-sm-9 col-xs-offset-5 col-xs-7 mb-10">
                                            ' . (!empty($dataRegistryBusinessDelivery['description']) ? $dataRegistryBusinessDelivery['description'] : '-') . '
                                        </div>';
                                }
                            } else {
                                
                                echo '
                                    <div class="col-sm-3 col-xs-5"> - </div>
                                    <div class="col-sm-9 col-xs-7"> - </div>';
                            } ?>
                            
                        </div>
                        
                        <hr>
    				    
    				    <?php
                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business-delivery/index', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                    
                    ActiveForm::end(); ?>
                        
                </div>
            </div>
        </div>
    </div>
</div>