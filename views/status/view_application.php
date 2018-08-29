<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;
use sycomponent\Tools;
use backoffice\components\AppComponent;

/* @var $this yii\web\View */
/* @var $searchModel core\models\search\RegistryBusinessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this);

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusiness'
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

$this->title = Yii::t('app', 'Application') . ' ' . $model['name'];
$this->params['breadcrumbs'][] = Yii::t('app', 'Application'); ?>

<?= $ajaxRequest->component(false) ?>

<div class="registry-business-view">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h4><?= $model['membershipType']['name'] ?></h4>
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4><?= Yii::t('app', 'User In Charge') ?>: <?= $model['userInCharge']['full_name'] ?></h4>
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
                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Email')) ?><br>
                            <?= $model['email'] ?>
                        </div>
                    </div>

                    <div class="row mb-20">

                        <div class="col-md-3">
                            <?= Html::label(Yii::t('app', 'Address Type')) ?><br>
                            <?= $model['address_type'] ?>
                        </div>
                        <div class="col-md-9">
                            <?= Html::label(Yii::t('app', 'Address')) ?><br>
                            <?= $model['address'] ?>
                        </div>

                    </div>

                    <div class="row mb-20">

                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'City ID')) ?><br>
                            <?= $model['city']['name'] ?>
                        </div>

                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'District ID')) ?><br>
                            <?= $model['district']['name'] ?>
                        </div>

                        <div class="col-lg-3 col-xs-6">
                            <?= Html::label(Yii::t('app', 'Village ID')) ?><br>
                            <?= $model['village']['name'] ?>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Yii::t('app', 'More Detail') ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">

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
                                    </div>
                                </div>

                            <?php
                            endforeach;
                        endif; ?>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h4><?= Yii::t('app', 'Application Status') ?></h4>
                </div>

                <div class="x_content">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'action' => ['update-status', 'id' => $model['applicationBusiness']['id'], 'rbid' => $model['id']],
                    ]);

                        foreach ($model['applicationBusiness']['logStatusApprovals'] as $dataLogStatusApproval):
                            if ($dataLogStatusApproval['is_actual']): ?>

                                <div class="row">
                                    <div class="col-md-3">
                                        <h4><strong><?= $dataLogStatusApproval['status_approval_id'] ?> <small><?= $dataLogStatusApproval['statusApproval']['name'] ?></small></strong></h4>
                                    </div>

                                    <div class="col-md-5">

                                        <?= $dataLogStatusApproval['statusApproval']['note'] ?>
                                        <div class="clearfix" style="margin-bottom: 5px"></div>

                                        <?php
                                        foreach ($dataLogStatusApproval['statusApproval']['statusApprovalActions'] as $dataStatusApprovalAction) {

                                            $btn = ' btn-default';
                                            $url = [$dataStatusApprovalAction['url'], 'id' => $model['id'], 'appBId' => $model['applicationBusiness']['id'], 'actid' => $dataStatusApprovalAction['id'], 'logsaid' => $dataLogStatusApproval['id']];

                                            if (!empty($dataStatusApprovalAction['logStatusApprovalActions'])) {

                                                foreach ($dataStatusApprovalAction['logStatusApprovalActions'] as $value) {

                                                    if ($value['logStatusApproval']['application_business_counter'] == $model['applicationBusiness']['counter']) {
                                                        $btn = ' btn-success btn-action';
                                                        $url = '';
                                                        break;
                                                    }
                                                }
                                            }

                                            echo Html::a('<i class="fa fa-external-link-alt"></i> '. $dataStatusApprovalAction['name'], $url, ['class' => 'btn btn-xs' . $btn, 'data-method' => 'post']);
                                            echo "&nbsp;";
                                        } ?>

                                    </div>

                                    <div class="col-md-4">

                                        <?php
                                        foreach ($dataLogStatusApproval['statusApproval']['statusApprovalRequires0'] as $dataStatusApprovalRequire) {

                                            $flag = true;

                                            if (!empty($dataStatusApprovalRequire['statusApproval']['logStatusApprovals'])) {
                                                foreach ($dataStatusApprovalRequire['statusApproval']['logStatusApprovals'] as $value) {
                                                    if ($value['application_business_counter'] == $model['applicationBusiness']['counter']) {
                                                        $flag = false;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($flag) {
                                                $btn = $dataStatusApprovalRequire['statusApproval']['condition'] ? ' btn-primary' : ' btn-danger';

                                                echo Html::button('<i class="fa fa-arrow-circle-right"></i> ' . $dataStatusApprovalRequire['statusApproval']['id'], [
                                                    'class' => 'btn' . $btn . ' submit-approval',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-status-approval-id' => $dataStatusApprovalRequire['statusApproval']['id'],
                                                    'title' => $dataStatusApprovalRequire['statusApproval']['name']
                                                ]);

                                                echo Html::hiddenInput('status_approval_actual-' . $dataStatusApprovalRequire['status_approval_id'], $dataLogStatusApproval['status_approval_id']);
                                                echo Html::hiddenInput('log_status_approval_actual-' . $dataStatusApprovalRequire['status_approval_id'], $dataLogStatusApproval['id']);
                                                echo '&nbsp;&nbsp;&nbsp;';
                                            }
                                        } ?>

                                    </div>
                                </div>
                            <?php
                            endif;
                        endforeach;

                        echo Html::hiddenInput('status_approval_id', null, ['class' => 'status-approval-id']);

                    ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$jscript = '
    $(\'[data-toggle="tooltip"]\').tooltip();

    $(".submit-approval").on("click", function() {

        $(".status-approval-id").val($(this).data("status-approval-id"));
        $("#registry-business-form").trigger("submit");
    });

    $(".collapse-link").trigger("click");
';

$this->registerJs($jscript); ?>