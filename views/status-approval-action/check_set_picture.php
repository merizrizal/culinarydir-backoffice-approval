<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\ModalDialog;
use sycomponent\NotificationDialog;
use sycomponent\Tools;
use backoffice\components\AppComponent;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusiness */
/* @var $id backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $appBId backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $actid backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $logsaid backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $form yii\widgets\ActiveForm */

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this);

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

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = $this->title;

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

                        echo Html::hiddenInput('check_set_picture', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);

                        echo Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']) ?>

                        <div class="clearfix" style="margin-top: 15px"></div>

                        <div class="row">
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'Membership Type') ?></strong> : <?= $model['membershipType']['name'] ?> | <strong><?= Yii::t('app', 'Status') ?></strong> : <?= $model['applicationBusiness']['logStatusApprovals'][0]['statusApproval']['name'] ?></h4>
                            </div>
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'User In Charge') ?></strong> : <?= $model['userInCharge']['full_name'] ?></h4>
                            </div>
                        </div>
                        
                        <hr>

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
                            <div class="col-xs-6 col-sm-3">
                            
                                <?= Html::label(Yii::t('app', 'City ID')) ?><br>
                                <?= $model['city']['name'] ?>
                                
                            </div>
                        </div>

                        <div class="row mb-20">
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
                            
                                <?= Html::label(Yii::t('app', 'Note')) ?><br>
                                <?= !empty($model['note']) ? $model['note'] : '-' ?>
                                
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-xs-12">

                                <?= Html::label(Yii::t('app', 'Business Location')) ?><br>

                                <?php
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

                        <div class="row">
                            <div class="col-xs-12">
                                <h4><strong><?= Yii::t('app', 'Marketing Information') ?></strong></h4>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Business Category')) ?>
                                
                            </div>                            
                        </div>
                        
                        <div class="row">
                        
                        	<?php
                            if (!empty($model['registryBusinessCategories'])) {
                                
                                foreach ($model['registryBusinessCategories'] as $registryBusinessCategory) {

                                    echo '
                                        <div class="col-xs-6 col-sm-2">
                                            ' . $registryBusinessCategory['category']['name'] . '
                                        </div>';
                                }
                            } ?>
                        
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Product Category')) ?>
                                
                            </div>
                        </div>
                        
                        <div class="row">

                            <?php
                            $productCategoryParent = [];
                            $productCategoryChild = [];

                            if (!empty($model['registryBusinessProductCategories'])) {
                                
                                foreach ($model['registryBusinessProductCategories'] as $value) {

                                    if ($value['productCategory']['is_parent']) {

                                        $productCategoryParent[$value['product_category_id']] = $value['productCategory']['name'];
                                    } else {

                                        $productCategoryChild[$value['product_category_id']] = $value['productCategory']['name'];
                                    }
                                }

                                if (!empty($productCategoryParent)) {

                                    echo '
                                        <div class="col-xs-12">
                                            - ' . Html::label(Yii::t('app', 'Product Category General')) . ' -
                                        </div>';

                                    foreach ($productCategoryParent as $productCategory) {

                                        echo '
                                            <div class="col-xs-6 col-sm-2">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }

                                if (!empty($productCategoryChild)) {

                                    echo '
                                        <div class="col-xs-12">
                                            - ' . Html::label(Yii::t('app', 'Product Category Specific')) . ' -
                                        </div>';

                                    foreach ($productCategoryChild as $productCategory) {

                                        echo '
                                            <div class="col-xs-6 col-sm-2">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }
                            } ?>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Business Hour')) ?>
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">

                                <?php
                                $days = Yii::$app->params['days'];

                                if (!empty($model['registryBusinessHours'])):
                                
                                    foreach ($model['registryBusinessHours'] as $businessHour):

                                        $is24Hour = (($businessHour['open_at'] == '00:00:00') && ($businessHour['close_at'] == '24:00:00')); ?>

                                        <div class="row">
                                            <div class="col-md-2 col-xs-6 col-sm-2">
                                            
                                                <?= Html::label(Yii::t('app', $days[$businessHour['day'] - 1])) ?>
                                                
                                            </div>
                                            <div class="col-md-8 col-xs-6 col-sm-4">
                                            	
                                            	<?php
                                                echo $is24Hour ? Yii::t('app','24 Hours') : Yii::$app->formatter->asTime($businessHour['open_at'], 'short') . ' - ' . Yii::$app->formatter->asTime($businessHour['close_at'], 'short');
                                                
                                                if (!empty($businessHour['registryBusinessHourAdditionals'])) {
                                                    
                                                    foreach ($businessHour['registryBusinessHourAdditionals'] as $businessHourAdditional): ?>
                                                        
                                                    	<?= ', ' . Yii::$app->formatter->asTime($businessHourAdditional['open_at'], 'short') . ' - ' . Yii::$app->formatter->asTime($businessHourAdditional['close_at'], 'short'); ?>
                                                        
                                                    <?php
                                                    endforeach;
                                                } ?>
                                            
                                            </div>
                                        </div>

                                    <?php
                                    endforeach;
                                endif; ?>

                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Note')) ?><br>
                                <?= !empty($model['note_business_hour']) ? $model['note_business_hour'] : '-' ?>
                                
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Average Spending')) ?>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                            
                                <?= Html::label(Yii::t('app', 'Price Min')) ?><br>
                                <?= Yii::$app->formatter->asCurrency($model['price_min']); ?>
                                
                            </div>
                            <div class="col-xs-12 col-md-3">
                            
                                <?= Html::label(Yii::t('app', 'Price Max')) ?><br>
                                <?= Yii::$app->formatter->asCurrency($model['price_max']); ?>
                                
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Facility')) ?>
                                
                            </div>
                        </div>
                        
                        <div class="row">

                            <?php
                            if (!empty($model['registryBusinessFacilities'])) {
                                
                                foreach ($model['registryBusinessFacilities'] as $registryBusinessFacility) {

                                    echo '
                                        <div class="col-xs-6 col-sm-2">
                                            ' . $registryBusinessFacility['facility']['name'] . '
                                        </div>';
                                }
                            } ?>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                            
                                <?= Html::label(Yii::t('app', 'Photo')) ?>
                                
                            </div>
                        </div>
                        
                        <div class="row">

                            <?php
                            if (!empty($model['registryBusinessImages'])):
                            
                                foreach ($model['registryBusinessImages'] as $registryBusinessImage): ?>

                                    <div class="col-xs-6 col-sm-3">
                                        <div class="thumbnail">
                                            <div class="image view view-first">
                                            
                                                <?= Html::img(Yii::getAlias('@uploadsUrl') . Tools::thumb('/img/registry_business/', $registryBusinessImage['image'], 200, 150), ['style' => 'width: 100%; display: block;']);  ?>
                                                
                                                <div class="mask">
                                                    <p>&nbsp;</p>
                                                    <div class="tools tools-bottom">
                                                        <a class="show-image direct" href="<?= Yii::getAlias('@uploadsUrl') . '/img/registry_business/' . $registryBusinessImage['image'] ?>"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-10">
                                            
                                            	<?= Html::dropDownList('category['. $registryBusinessImage['id'] .']', null, ['Ambience' => 'Suasana', 'Menu' => 'Menu'], ['class' => 'photo-category']) ?>
                                            	
                                            	<div class="clearfix" style="margin-bottom: 5px"></div>
                                                
                                                <?= Html::checkbox('profile['. $registryBusinessImage['id'] .']', false, ['label' => 'Set as Profile']) ?>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <?= Html::radio('thumbnail', false, ['label' => 'Set as Thumbnail', 'value' => $registryBusinessImage['id']]) ?>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <?php
                                                echo Html::a('<i class="fa fa-arrow-left"></i>', ['up', 'id' => $registryBusinessImage['id'], 'bid' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default', 'title' => 'Left']);
                                                echo Html::a('<i class="fa fa-arrow-right"></i>', ['down', 'id' => $registryBusinessImage['id'], 'bid' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default', 'title' => 'Right']); ?>
                                                
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                endforeach;
                            endif; ?>

                        </div>
                        
                        <hr>
                        
                        <div class="row">
                        	<div class="col-md-12">
                            
                                <h4><strong><?= Html::label('Contact Person', null, ['class' => 'control-label']) ?></strong></h4>
                                
                                <hr>
                            </div>
                        </div>
                    		
        				<?php
    				    if (!empty($model['registryBusinessContactPeople'])):
    			            
    			            foreach ($model['registryBusinessContactPeople'] as $i => $person):
    			            	
        			            $is_primary = !empty($person['is_primary_contact']) ? ' - ' . Yii::t('app', 'Primary Contact') : '';
        			            
    			                echo '<strong>' . Yii::t('app', 'Contact') . ' ' . ($i+1) . $is_primary . '</strong><br><br>'; ?>
    			            	
    			            	<div class="row mb-20">
    			            		<div class="col-md-3">
    			            		
        			            		<?php
        			            		echo Html::label(Yii::t('app', 'Name')) . '<br>';
            				            
            			                echo $person['person']['first_name'] . ' ' . $person['person']['last_name']; ?>
        			                
        			                </div>
        			                
        			                <div class="col-md-3">
        			                	
        			                	<?php
        			                	echo Html::label(Yii::t('app', 'Position')) .  '<br>';
        			                	
        			                	echo $person['position']; ?>
        			                	
        			                </div>
    			                </div>
    			                
    			                <div class="row mb-20">
    			                	<div class="col-md-3">
    			                		
    			                		<?php
        			            		echo Html::label(Yii::t('app', 'Email')) . '<br>';
            				            
        			            		echo !empty($person['person']['email']) ? $person['person']['email'] : '-'; ?>
    			                		
    			                	</div>
    			                	
    			                	<div class="col-md-3">
    			                		
    			                		<?php
        			            		echo Html::label(Yii::t('app', 'Phone')) . '<br>';
            				            
        			            		echo !empty($person['person']['phone']) ? $person['person']['phone'] : '-'; ?>
    			                		
    			                	</div>
    			                	
    			                	<div class="col-md-6">
    			                		
    			                		<?php
        			            		echo Html::label(Yii::t('app', 'Note')) . '<br>';
            				            
        			            		echo !empty($person['note']) ? $person['note'] : '-'; ?>
    			                		
    			                	</div>
    			                	
    			                </div>
    			                
    			                <hr>
    			                
    			            <?php
    			            endforeach;
			            
			            else: ?>
			         	
    			         	<div class="row mb-20">
    			         		<div class="col-md-3">
    			         		
    	         		  			<?= '-' ?>
    			         		  
    		         		  	</div>
    			         	</div>
		            
		            	<?php
    				    endif;

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);

                        echo Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);

                    ActiveForm::end(); ?>

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
$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\YiiAsset']);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/icheck.min.js', ['depends' => 'yii\web\YiiAsset']);
$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $(".photo-category").select2({
        theme: "krajee",
        minimumResultsForSearch: -1
    });

    $(".thumbnail").magnificPopup({
        delegate: "a.show-image",
        type: "image",
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        image: {
            tError: "The image could not be loaded."
        }
    });
';

$this->registerJs(Yii::$app->params['checkbox-radio-script']() . $jscript); ?>