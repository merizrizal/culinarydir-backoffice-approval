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

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusiness',
]);

$ajaxRequest->form();

$status = Yii::$app->session->getFlash('status');
$message1 = Yii::$app->session->getFlash('message1');
$message2 = Yii::$app->session->getFlash('message2');

if ($status !== null) :
    $notif = new NotificationDialog([
        'status' => $status,
        'message1' => $message1,
        'message2' => $message2,
    ]);

    $notif->theScript();
    echo $notif->renderDialog();

endif;

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = $this->title; ?>

<?= $ajaxRequest->component() ?>

<div class="registry-business-view">

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">

                <div class="x_content">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'options' => [

                        ]
                    ]); ?>

                        <?= Html::hiddenInput('check_set_picture', true) ?>

                        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . 'OK & Save', ['class' => 'btn btn-success']) ?>

                        <?= Html::a('<i class="fa fa-times"></i> ' . 'Cancel',
                            ['status/view-application', 'id' => $id, 'appBId' => $appBId],
                            [
                                'class' => 'btn btn-default',
                            ]) ?>

                        <div class="clearfix" style="margin-top: 15px"></div>

                        <div class="row">
                            <div class="col-lg-12">
                                <h4><strong><?= Yii::t('app', 'Membership Type') ?></strong> : <?= $model['membershipType']['name'] ?> | <strong><?= Yii::t('app', 'Status') ?></strong> : <?= $model['applicationBusiness']['logStatusApprovals'][0]['statusApproval']['name'] ?></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <h4><strong><?= Yii::t('app', 'User In Charge') ?></strong> : <?= $model['userInCharge']['full_name'] ?></h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4><strong><?= Yii::t('app', 'Business Information') ?></strong></h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row mb-20">

                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Name')) ?><br>
                                <?= $model['name'] ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Unique Name')) ?><br>
                                <?= $model['unique_name'] ?>
                            </div>

                        </div>

                        <div class="row mb-20">

                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Address Type')) ?><br>
                                <?= $model['address_type'] ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Address')) ?><br>
                                <?= $model['address'] ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Address Info')) ?><br>
                                <?= $model['address_info'] ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'City ID')) ?><br>
                                <?= $model['city']['name'] ?>
                            </div>

                        </div>

                        <div class="row mb-20">

                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'District ID')) ?><br>
                                <?= $model['district']['name'] ?>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Village ID')) ?><br>
                                <?= $model['village']['name'] ?>
                            </div>
                            <div class="col-lg-6 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Coordinate')) ?><br>
                                <?= $model['coordinate'] ?>
                            </div>

                        </div>

                        <div class="row mb-20">

                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Email')) ?><br>
                                <?= $model['email'] ?>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Phone1')) ?><br>
                                <?= $model['phone1'] ?>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Phone2')) ?><br>
                                <?= $model['phone2'] ?>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <?= Html::label(Yii::t('app', 'Phone3')) ?><br>
                                <?= $model['phone3'] ?>
                            </div>

                        </div>

                        <div class="row mb-20">
                            <div class="col-md-12">

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

                        <div class="row">
                            <div class="col-md-12">
                                <h4><strong><?= Yii::t('app', 'Marketing Information') ?></strong></h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Business Category'), null, ['class' => 'control-label']) ?>
                            </div>
                        </div>

                        <div class="row">

                            <?php
                            if (!empty($model['registryBusinessCategories'])) {
                                foreach ($model['registryBusinessCategories'] as $registryBusinessCategory) {

                                    echo '
                                        <div class="col-sm-2 col-xs-6">
                                            ' . $registryBusinessCategory['category']['name'] . '
                                        </div>';
                                }
                            } ?>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Product Category'), null, ['class' => 'control-label']) ?>
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
                                        <div class="clearfix"></div>
                                        <div class="col-sm-3 col-xs-6 mt-10">
                                            - ' . Html::label(Yii::t('app', 'Product Category General')) . ' -
                                        </div>
                                        <div class="clearfix"></div>';

                                    foreach ($productCategoryParent as $productCategory) {

                                        echo '
                                            <div class="col-sm-2 col-xs-6">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }

                                if (!empty($productCategoryChild)) {

                                    echo '
                                        <div class="clearfix"></div>
                                        <div class="col-sm-3 col-xs-6 mt-10">
                                            - ' . Html::label(Yii::t('app', 'Product Category Specific')) . ' -
                                        </div>
                                        <div class="clearfix"></div>';

                                    foreach ($productCategoryChild as $productCategory) {

                                        echo '
                                            <div class="col-sm-2 col-xs-6">
                                                ' . $productCategory . '
                                            </div>';
                                    }
                                }
                            } ?>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Business Hour'), null, ['class' => 'control-label']) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                $days = Yii::$app->params['days'];

                                if (!empty($model['registryBusinessHours'])):
                                    foreach ($model['registryBusinessHours'] as $businessHour):

                                        $is24Hour = (($businessHour['open_at'] == '00:00:00') && ($businessHour['close_at'] == '24:00:00')) ? true : false; ?>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <?= Html::label(Yii::t('app', $days[$businessHour['day'] - 1])) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $is24Hour ? Yii::t('app','24 Hours') : Yii::$app->formatter->asTime($businessHour['open_at']) . ' - ' . Yii::$app->formatter->asTime($businessHour['close_at']);?>
                                            </div>
                                        </div>

                                    <?php
                                    endforeach;
                                endif; ?>

                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Price Range'), null, ['class' => 'control-label']) ?>
                            </div>
                        </div>

                        <div class="row mb-20">

                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Price Min')) ?><br>
                                <?= Yii::$app->formatter->asCurrency($model['price_min']); ?>
                            </div>

                            <div class="col-md-3">
                                <?= Html::label(Yii::t('app', 'Price Max')) ?><br>
                                <?= Yii::$app->formatter->asCurrency($model['price_max']); ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Facility'), null, ['class' => 'control-label']) ?>
                            </div>
                        </div>
                        <div class="row">

                            <?php
                            if (!empty($model['registryBusinessFacilities'])) {
                                foreach ($model['registryBusinessFacilities'] as $registryBusinessFacility) {

                                    echo '
                                        <div class="col-sm-2 col-xs-6">
                                            ' . $registryBusinessFacility['facility']['name'] . '
                                        </div>';
                                }
                            } ?>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::label(Yii::t('app', 'Photo'), null, ['class' => 'control-label']) ?>
                            </div>
                        </div>
                        <div class="row">

                            <?php
                            if (!empty($model['registryBusinessImages'])):
                                foreach ($model['registryBusinessImages'] as $registryBusinessImage): ?>

                                    <div class="col-xs-3">
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
                                                <?= Html::checkbox('profile['. $registryBusinessImage['id'] .']', false, ['class' => 'form-control', 'label' => 'Set as Profile']) ?>
                                                <?= Html::checkbox('primary['. $registryBusinessImage['id'] .']', false, ['class' => 'form-control', 'label' => 'Set as Primary']) ?>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                endforeach;
                            endif; ?>

                        </div>

                        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . 'OK & Save', ['class' => 'btn btn-success']) ?>

                        <?= Html::a('<i class="fa fa-times"></i> ' . 'Cancel',
                            ['status/view-application', 'id' => $id, 'appBId' => $appBId],
                            [
                                'class' => 'btn btn-default',
                            ]) ?>

                    <?php
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