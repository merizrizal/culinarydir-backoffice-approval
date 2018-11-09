<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;
use sycomponent\Tools;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusiness */
/* @var $dataRegistryBusinessImage core\models\RegistryBusinessImage */
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

$this->title = 'Update ' . Yii::t('app', 'Gallery Photo') . ' : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['check-set-picture', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = 'Update ' . Yii::t('app', 'Gallery Photo');

echo $ajaxRequest->component(); ?>

<div class="registry-business-update">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="registry-business-form">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'action' => ['update-gallery-photo', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid],
                        'options' => [

                        ],
                        'fieldConfig' => [
                            'template' => '{input}{error}',
                        ]
                    ]); ?>

                        <div class="x_title">
                            <h4><?= Yii::t('app', 'Gallery Photo') ?></h4>
                        </div>

                        <div class="x_content">

                            <div class="form-group">
                                <div class="row">

                                    <?php
                                    foreach ($model['registryBusinessImages'] as $registryBusinessImage): ?>

                                        <div class="col-xs-6 col-sm-3">
                                            <div class="thumbnail">
                                                <div class="image view view-first">
                                                
                                                    <?= Html::img(Yii::getAlias('@uploadsUrl') . Tools::thumb('/img/registry_business/', $registryBusinessImage['image'], 200, 150), ['style' => 'width: 100%; display: block;']); ?>
                                                    
                                                    <div class="mask">
                                                        <p>&nbsp;</p>
                                                        <div class="tools tools-bottom">
                                                            <a class="show-image direct" href="<?= Yii::getAlias('@uploadsUrl') . '/img/registry_business/' . $registryBusinessImage['image'] ?>"><i class="fa fa-search"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-10">
                                    
                                                	<?= Html::dropDownList('category['. $registryBusinessImage['id'] .']', $registryBusinessImage['category'], ['Ambience' => 'Suasana', 'Menu' => 'Menu'], ['class' => 'photo-category']) ?>
                                                	
                                                	<div class="clearfix" style="margin-bottom: 5px"></div>
                                                    
                                                    <?= Html::checkbox('profile['. $registryBusinessImage['id'] .']', ($registryBusinessImage['type'] == 'Profile'), ['label' => 'Set as Profile']) ?>
                                                    
                                                    <div class="clearfix"></div>
                                                    
                                                    <?= Html::radio('thumbnail', $registryBusinessImage['is_primary'], ['label' => 'Set as Thumbnail', 'value' => $registryBusinessImage['id']]) ?>
                                                    
                                                    <div class="clearfix"></div>
                                                    
                                                    <?php
                                                    echo Html::a('<i class="fa fa-arrow-left"></i>', ['up', 'id' => $registryBusinessImage['id'], 'bid' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default', 'title' => 'Left']);
                                                    echo Html::a('<i class="fa fa-arrow-right"></i>', ['down', 'id' => $registryBusinessImage['id'], 'bid' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default', 'title' => 'Right']); ?>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    endforeach; ?>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">

                                    <?php
                                    echo Html::submitButton('<i class="fa fa-save"></i> Update', ['class' => 'btn btn-primary']);
                                    echo Html::a('<i class="fa fa-times"></i> Cancel', ['check-set-picture', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default']); ?>

                                </div>
                            </div>
                            
                        </div>

                    <?php
                    ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/skins/all.css', ['depends' => 'yii\web\YiiAsset']);
$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\YiiAsset']);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/icheck.min.js', ['depends' => 'yii\web\YiiAsset']);
$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $(".photo-category").select2({
        theme: "krajee",
        minimumResultsForSearch: Infinity
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