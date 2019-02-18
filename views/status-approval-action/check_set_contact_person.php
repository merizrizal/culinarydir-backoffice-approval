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

$this->title = 'Check & Set ' . Yii::t('app', 'Contact Person') . ' : ' . $model['name'];
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

                        echo Html::hiddenInput('check_set_contact_person', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-contact-person', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

                        <div class="clearfix" style="margin-top: 15px"></div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'Contact Person') ?></strong></h4>
                            </div>
                        </div>
                        
                        <hr>

                        <?php
    				    if (!empty($model['registryBusinessContactPeople'])):
    		
        				    foreach ($model['registryBusinessContactPeople'] as $i => $person):
			            	
        			            $is_primary = !empty($person['is_primary_contact']) ? ' - ' . Yii::t('app', 'Primary Contact') : ''; ?>
        			            
    			                <div class="row mb-20">
    			            		<div class="col-xs-12 mb-10">
    			            			<strong><?= Yii::t('app', 'Contact') . ' ' . ($i + 1) . $is_primary ?></strong>
        			            	</div>
    			            		<div class="col-sm-3 col-xs-6 mb-10">
        			            		<?= Html::label(Yii::t('app', 'Name')) ?><br>
            			                <?= $person['person']['first_name'] . ' ' . $person['person']['last_name']; ?>
        			                </div>
        			                <div class="col-sm-3 col-xs-6 mb-10">
        			                	<?= Html::label(Yii::t('app', 'Position')) ?><br>
        			                	<?= $person['position']; ?>
        			                </div>
        			                <div class="col-sm-3 col-xs-6">
    			                		<?= Html::label(Yii::t('app', 'Email')) ?><br>
        			            		<?= !empty($person['person']['email']) ? $person['person']['email'] : '-'; ?>
    			                	</div>
    			                	<div class="col-sm-3 col-xs-6">
        			            		<?= Html::label(Yii::t('app', 'Phone')) ?><br>
        			            		<?= !empty($person['person']['phone']) ? $person['person']['phone'] : '-'; ?>
    			                	</div>
    			                </div>
    			                
    			                <div class="row mb-20">
    			                	<div class="col-xs-12">
        			            		<?= Html::label(Yii::t('app', 'Note')) . '<br>'; ?>
        			            		<?= !empty($person['note']) ? $person['note'] : '-'; ?>
    			                	</div>
    			                </div>
    			                
    			                <hr>
			                
    			            <?php
    			            endforeach;
			            else: ?>
			         	
    			         	<div class="row mb-20">
    			         		<div class="col-xs-12">
    	         		  			<?= Yii::t('app', 'Data Not Available') ?>
    		         		  	</div>
    			         	</div>
    			         	
    			         	<hr>
		            
		            	<?php
    				    endif;
    				    
                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-contact-person', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                    
                    ActiveForm::end(); ?>
                        
                </div>
            </div>
        </div>
    </div>
</div>