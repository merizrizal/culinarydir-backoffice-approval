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

$this->title = 'Check & Set ' . Yii::t('app', 'Marketing Information') . ' : ' . $model['name'];
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

                        echo Html::hiddenInput('check_set_marketing_info', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-marketing-info', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

                        <div class="clearfix" style="margin-top: 15px"></div>
                        
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
                                
                                foreach ($model['registryBusinessCategories'] as $dataRegistryBusinessCategory) {

                                    echo '
                                        <div class="col-xs-4 col-sm-2">
                                            ' . $dataRegistryBusinessCategory['category']['name'] . '
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
                                
                                foreach ($model['registryBusinessProductCategories'] as $dataRegistryBusinessProductCategory) {

                                    if ($dataRegistryBusinessProductCategory['productCategory']['type'] == 'General') {

                                        $productCategoryParent[$dataRegistryBusinessProductCategory['product_category_id']] = $dataRegistryBusinessProductCategory['productCategory']['name'];
                                    } else if ($dataRegistryBusinessProductCategory['productCategory']['type'] == 'Specific' || $dataRegistryBusinessProductCategory['productCategory']['type'] == 'Specific-Menu') {

                                        $productCategoryChild[$dataRegistryBusinessProductCategory['product_category_id']] = $dataRegistryBusinessProductCategory['productCategory']['name'];
                                    }
                                }

                                if (!empty($productCategoryParent)) {

                                    echo '
                                        <div class="col-xs-12 mt-10">
                                            - ' . Html::label(Yii::t('app', 'Product Category General')) . ' -
                                        </div>';

                                    foreach ($productCategoryParent as $productCategory) {

                                        echo '
                                            <div class="col-xs-4 col-sm-2">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }

                                if (!empty($productCategoryChild)) {

                                    echo '
                                        <div class="col-xs-12 mt-10">
                                            - ' . Html::label(Yii::t('app', 'Product Category Specific')) . ' -
                                        </div>';

                                    foreach ($productCategoryChild as $productCategory) {

                                        echo '
                                            <div class="col-xs-4 col-sm-2">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }
                            } ?>

                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <?= Html::label(Yii::t('app', 'Average Spending')) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-4 col-sm-2">
                                <?= Html::label(Yii::t('app', 'Price Min')) ?><br>
                                <?= Yii::$app->formatter->asCurrency($model['price_min']); ?>
                            </div>
                            <div class="col-xs-4 col-sm-2">
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
                                
                                foreach ($model['registryBusinessFacilities'] as $dataRegistryBusinessFacility) {

                                    echo '
                                        <div class="col-xs-4 col-sm-2">
                                            ' . $dataRegistryBusinessFacility['facility']['name'] . '
                                        </div>';
                                }
                            } ?>

                        </div>
                        
                        <hr>
                        
                        <div class="row">
                        	<div class="col-sm-12">
                                <?= Html::label(Yii::t('app', 'Menu')) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                        	<div class="col-sm-12">
                        	
                        		<?php
                        		if (!empty($model['menu'])) {
                        		    
                        		    $listMenu = explode("\n", $model['menu']);
                        		    
                        		    foreach ($listMenu as $dataMenu) {
                        		        
                        		        echo $dataMenu . '<br>';
                        		    }
                        		} else {
                        		    
                        		    echo 'Data menu masih kosong';
                        		} ?>
                        		
                        	</div>
                        </div>
                        
                        <hr>
                        
                        <?php
                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-marketing-info', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                        
                    ActiveForm::end(); ?>
                        
                </div>
            </div>
        </div>
    </div>
</div>