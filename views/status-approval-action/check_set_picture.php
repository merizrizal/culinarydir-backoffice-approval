<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;
use sycomponent\Tools;

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

$this->title = 'Check & Set Picture : ' . $model['name'];
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

                        echo Html::hiddenInput('check_set_picture', true);

                        echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
                        echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-gallery-photo', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
                        echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']); ?>

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
                                <?= Html::label(Yii::t('app', 'Business Category')) ?>
                            </div>
                        </div>
    
                        <div class="row">
    
                            <?php
                            if (!empty($model['registryBusinessCategories'])) {
                                
                                foreach ($model['registryBusinessCategories'] as $dataBusinessCategory) {
    
                                    echo '
                                        <div class="col-xs-4 col-sm-2">
                                            ' . $dataBusinessCategory['category']['name'] . '
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
                            
                                foreach ($model['registryBusinessImages'] as $dataRegistryBusinessImage): ?>

                                    <div class="col-xs-6 col-sm-3">
                                        <div class="thumbnail">
                                            <div class="image view view-first">
                                            
                                                <?= Html::img(Yii::getAlias('@uploadsUrl') . Tools::thumb('/img/registry_business/', $dataRegistryBusinessImage['image'], 200, 150), ['style' => 'width: 100%; display: block;']);  ?>
                                                
                                                <div class="mask">
                                                    <p>&nbsp;</p>
                                                    <div class="tools tools-bottom">
                                                        <a class="show-image direct" href="<?= Yii::getAlias('@uploadsUrl') . '/img/registry_business/' . $dataRegistryBusinessImage['image'] ?>"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                endforeach;
                            endif; ?>

                        </div>
                        
                        <hr>

						<?php
						echo Html::submitButton('<i class="fa fa-check-circle"></i> OK & Save', ['class' => 'btn btn-success']);
						echo ' ' . Html::a('<i class="fa fa-pencil-alt"></i> Edit', ['registry-business/update-gallery-photo', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-primary']);
						echo ' ' . Html::a('<i class="fa fa-times"></i> Cancel', ['status/view-application', 'id' => $id, 'appBId' => $appBId], ['class' => 'btn btn-default']);
                        
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

$this->registerJs($jscript); ?>