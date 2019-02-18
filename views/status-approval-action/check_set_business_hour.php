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

$this->title = 'Check & Set ' . Yii::t('app', 'Operational Hours') . ' : ' . $model['name'];
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

                        echo Html::hiddenInput('check_set_business_hour', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-business-hour', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

                        <div class="clearfix" style="margin-top: 15px"></div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'Operational Hours') ?></strong></h4>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="row">
                            <div class="col-xs-12">

                                <?php
                                $days = Yii::$app->params['days'];

                                if (!empty($model['registryBusinessHours'])):
                                
                                    foreach ($model['registryBusinessHours'] as $dataRegistryBusinessHour):

                                        $is24Hour = (($dataRegistryBusinessHour['open_at'] == '00:00:00') && ($dataRegistryBusinessHour['close_at'] == '24:00:00')); ?>

                                        <div class="row">
                                            <div class="col-xs-4 col-sm-2">
                                                <?= Html::label(Yii::t('app', $days[$dataRegistryBusinessHour['day'] - 1])) ?>
                                            </div>
                                            <div class="col-xs-4 col-sm-4">
                                            	
                                            	<?php
                                            	echo $is24Hour ? Yii::t('app','24 Hours') : Yii::$app->formatter->asTime($dataRegistryBusinessHour['open_at'], 'short') . ' - ' . Yii::$app->formatter->asTime($dataRegistryBusinessHour['close_at'], 'short');
                                                
                                            	if (!empty($dataRegistryBusinessHour['registryBusinessHourAdditionals'])) {
                                                    
                                            	    foreach ($dataRegistryBusinessHour['registryBusinessHourAdditionals'] as $dataRegistryBusinessHourAdditional) {
                                            	    
                                            	        echo ', ' . Yii::$app->formatter->asTime($dataRegistryBusinessHourAdditional['open_at'], 'short') . ' - ' . Yii::$app->formatter->asTime($dataRegistryBusinessHourAdditional['close_at'], 'short');
                                            	    }
                                                } ?>
                                            
                                            </div>
                                        </div>

                                    <?php
                                    endforeach;
                                endif; ?>

                            </div>
                        </div>
                        
                        <br>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <?= Html::label(Yii::t('app', 'Note')) ?><br>
                                <?= !empty($model['note_business_hour']) ? $model['note_business_hour'] : '-' ?>
                            </div>
                        </div>

                        <hr>
    				    
    				    <?php
                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-business-hour', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                    
                    ActiveForm::end(); ?>
                        
                </div>
            </div>
        </div>
    </div>
</div>