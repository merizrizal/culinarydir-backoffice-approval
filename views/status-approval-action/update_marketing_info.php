<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusiness */
/* @var $modelCategory core\models\Category */
/* @var $modelProductCategoryParent core\models\ProductCategory */
/* @var $modelProductCategoryChild core\models\ProductCategory */
/* @var $modelFacility core\models\Facility */
/* @var $dataRegistryBusinessCategory core\models\RegistryBusinessCategory */
/* @var $modelRegistryBusinessCategory core\models\RegistryBusinessCategory */
/* @var $dataRegistryBusinessProductCategoryParent core\models\RegistryBusinessProductCategory */
/* @var $dataRegistryBusinessProductCategoryChild core\models\RegistryBusinessProductCategory */
/* @var $modelRegistryBusinessProductCategory core\models\RegistryBusinessProductCategory */
/* @var $dataRegistryBusinessFacility core\models\RegistryBusinessFacility */
/* @var $modelRegistryBusinessFacility core\models\RegistryBusinessFacility */
/* @var $dataRegistryBusinessHour core\models\RegistryBusinessHour */
/* @var $modelRegistryBusinessHour core\models\RegistryBusinessHour */
/* @var $id backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $appBId backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $actid backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $logsaid backoffice\modules\approval\controllers\StatusApprovalActionController */
/* @var $form yii\widgets\ActiveForm */
/* @var $day string */

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

$this->title = 'Update ' . Yii::t('app', 'Marketing Information') . ' : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application'), 'url' =>  ['status/view-application', 'id' => $id, 'appBId' => $appBId]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['check-set-picture', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]];
$this->params['breadcrumbs'][] = 'Update ' . Yii::t('app', 'Marketing Information');

echo $ajaxRequest->component(); ?>

<div class="registry-business-update">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="registry-business-form">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'registry-business-form',
                        'action' => ['update-marketing-info', 'id' => $id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid],
                        'options' => [

                        ],
                        'fieldConfig' => [
                            'template' => '{input}{error}',
                        ]
                    ]); ?>

                        <div class="x_title">
                            <h4><?= Yii::t('app', 'Marketing Information') ?></h4>
                        </div>

                        <div class="x_content">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                    
                                        <?= Html::label(Yii::t('app', 'Business Category')) ?>
                                        
                                    </div>
                                    <div class="col-xs-12">

                                        <?php
                                        $selectedDataCategory = [];

                                        if (!empty($dataRegistryBusinessCategory)) {

                                            foreach ($dataRegistryBusinessCategory as $registryBusinessCategory) {

                                                $selectedDataCategory[$registryBusinessCategory['category_id']] = ['selected' => true];
                                            }
                                        }

                                        echo $form->field($modelRegistryBusinessCategory, 'category_id')->dropDownList(
                                            ArrayHelper::map(
                                                $modelCategory,
                                                'id',
                                                'name'
                                            ),
                                            [
                                                'multiple' => 'multiple',
                                                'prompt' => '',
                                                'style' => 'width: 100%',
                                                'options' => $selectedDataCategory
                                            ]) ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                    
                                        <?= Html::label(Yii::t('app', 'Product Category')) ?>
                                        
                                    </div>
                                    <div class="col-xs-12">

                                        <?php
                                        $selectedDataProductParent = [];

                                        if (!empty($dataRegistryBusinessProductCategoryParent)) {

                                            foreach ($dataRegistryBusinessProductCategoryParent as $registryBusinessProductCategoryParent) {

                                                $selectedDataProductParent[$registryBusinessProductCategoryParent['product_category_id']] = ['selected' => true];
                                            }
                                        }
                                        
                                        echo $form->field($modelRegistryBusinessProductCategory, 'product_category_id[parent]')->dropDownList(
                                            ArrayHelper::map(
                                                $modelProductCategoryParent,
                                                'id',
                                                'name'
                                            ),
                                            [
                                                'multiple' => 'multiple',
                                                'prompt' => '',
                                                'style' => 'width: 100%',
                                                'options' => $selectedDataProductParent
                                            ]) ?>

                                    </div>
                                    <div class="col-xs-12">

                                        <?php
                                        $selectedDataProductChild = [];

                                        if (!empty($dataRegistryBusinessProductCategoryChild)) {

                                            foreach ($dataRegistryBusinessProductCategoryChild as $registryBusinessProductCategoryChild) {

                                                $selectedDataProductChild[$registryBusinessProductCategoryChild['product_category_id']] = ['selected' => true];
                                            }
                                        }

                                        echo $form->field($modelRegistryBusinessProductCategory, 'product_category_id[child]')->dropDownList(
                                            ArrayHelper::map(
                                                $modelProductCategoryChild,
                                                'id',
                                                'name'
                                            ),
                                            [
                                                'multiple' => 'multiple',
                                                'prompt' => '',
                                                'style' => 'width: 100%',
                                                'options' => $selectedDataProductChild
                                            ]) ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                    
                                        <?= Html::label(Yii::t('app', 'Facility')) ?>
                                    
                                    </div>
                                    <div class="col-xs-12">

                                        <?php
                                        $selectedDataFacility = [];

                                        if (!empty($dataRegistryBusinessFacility)) {

                                            foreach ($dataRegistryBusinessFacility as $registryBusinessFacility) {

                                                $selectedDataFacility[$registryBusinessFacility['facility_id']] = ['selected' => true];
                                            }
                                        }

                                        echo $form->field($modelRegistryBusinessFacility, 'facility_id')->dropDownList(
                                            ArrayHelper::map(
                                                $modelFacility,
                                                'id',
                                                'name'
                                            ),
                                            [
                                                'multiple' => 'multiple',
                                                'prompt' => '',
                                                'style' => 'width: 100%',
                                                'options' => $selectedDataFacility
                                            ]) ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row mb-10">
                                    <div class="col-xs-12">
                                    
                                    	<?= Html::label(Yii::t('app', 'Business Hour')) ?>
                                        <?= Html::button(Yii::t('app', 'Set All'), ['class' => 'btn btn-primary btn-xs set-all-business-hour']) ?>
                                        
                                    </div>
                                </div>

                                <?php
                                $days = Yii::$app->params['days'];
                                $hours = Yii::$app->params['hours'];

                                foreach ($days as $i => $day):

                                    $i++;
                                    $is24Hour = false;

                                    foreach ($dataRegistryBusinessHour as $registryBusinessHour) {

                                        if ($registryBusinessHour['day'] == $i) {

                                            $modelRegistryBusinessHour->is_open = $registryBusinessHour['is_open'];
                                            $modelRegistryBusinessHour->open_at = $registryBusinessHour['open_at'];
                                            $modelRegistryBusinessHour->close_at = $registryBusinessHour['close_at'];

                                            if ($modelRegistryBusinessHour->open_at == '00:00:00' && $modelRegistryBusinessHour->close_at == '24:00:00') {
                                                $is24Hour = true;
                                            }

                                            break;
                                        }
                                    } ?>

                                    <div class="row">
                                        <div class="col-xs-2 col-sm-1">
                                            
                                            <?= Yii::t('app', $days[$i - 1]) ?>
                                        
                                        </div>
                                        <div class="col-xs-3 col-sm-2">

                                            <?= $form->field($modelRegistryBusinessHour, '[day' . $i . ']is_open')
                                                ->checkbox([
                                                    'label' => Yii::t('app', 'Open'),
                                                    'class' => 'business-hour-is-open day-' . $i,
                                                    'data-day' => $i,
                                                ]); ?>

                                        </div>
                                        <div class="col-xs-3 col-sm-2">
                                            <div class="form-group">

                                                <?= Html::checkbox('always24', $is24Hour, [
                                                    'label' => Yii::t('app', '24 Hours'),
                                                    'data-day' => $i,
                                                    'class' => 'business-hour-24h',
                                                    'disabled' => !$modelRegistryBusinessHour->is_open,
                                                    'id' => 'business-hour-24h-' . $i
                                                ]); ?>

                                            </div>
                                        </div>

                                        <div class="visible-xs clearfix"></div>

                                        <div class="col-xs-4 col-sm-3 col-lg-2">

                                            <?= $form->field($modelRegistryBusinessHour, '[day' . $i . ']open_at')
                                                ->dropDownList($hours, [
                                                    'prompt' => '',
                                                    'class' => 'business-hour-time open',
                                                    'style' => 'width: 100%',
                                                    'disabled' => !$modelRegistryBusinessHour->is_open,
                                                ]); ?>

                                        </div>
                                        <div class="col-xs-4 col-sm-3 col-lg-2">

                                            <?= $form->field($modelRegistryBusinessHour, '[day' . $i . ']close_at')
                                                ->dropDownList($hours, [
                                                    'prompt' => '',
                                                    'class' => 'business-hour-time close',
                                                    'style' => 'width: 100%',
                                                    'disabled' => !$modelRegistryBusinessHour->is_open,
                                                ]); ?>

                                        </div>
                                    </div>

                                <?php
                                endforeach; ?>
                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9">

                                        <?= $form->field($model, 'note_business_hour')->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'Note')]) ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                    
                                        <?= Html::label(Yii::t('app', 'Average Spending')) ?>
                                    
                                    </div>
                                    <div class="col-xs-5 col-sm-4 col-lg-3">

                                        <?= $form->field($model, 'price_min')->widget(TouchSpin::className(), [
                                            'options' => [
                                                'placeholder' => Yii::t('app', 'Price Min'),
                                            ],
                                            'pluginOptions' => [
                                                'min' => 0,
                                                'max' => 1000000,
                                                'step' => 10000,
                                                'prefix' => 'Rp',
                                                'verticalbuttons' => true,
                                                'verticalup' => '<i class="glyphicon glyphicon-plus"></i>',
                                                'verticaldown' => '<i class="glyphicon glyphicon-minus"></i>'
                                            ],
                                        ]); ?>
    
                                    </div>
                                    <div class="col-xs-1 text-center">
                                        -
                                    </div>
                                    <div class="col-xs-5 col-sm-4 col-lg-3">
    
                                        <?= $form->field($model, 'price_max')->widget(TouchSpin::className(), [
                                            'options' => [
                                                'placeholder' => Yii::t('app', 'Price Max'),
                                            ],
                                            'pluginOptions' => [
                                                'min' => 0,
                                                'max' => 1000000,
                                                'step' => 10000,
                                                'prefix' => 'Rp',
                                                'verticalbuttons' => true,
                                                'verticalup' => '<i class="glyphicon glyphicon-plus"></i>',
                                                'verticaldown' => '<i class="glyphicon glyphicon-minus"></i>'
                                            ],
                                        ]); ?>
    
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12">

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

$cssscript = '
    .select2-grid-system ul.select2-results__options > li.select2-results__option {
        float: left;
        width: 50%;
    }

    @media (min-width: 768px) {
        .select2-grid-system ul.select2-results__options > li.select2-results__option {
            float: left;
            width: 33.33333333%;
        }
    }

    @media (min-width: 1200px) {
        .select2-grid-system ul.select2-results__options > li.select2-results__option {
            float: left;
            width: 20%;
        }
    }
';

$this->registerCss($cssscript);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/icheck.min.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $("#registrybusinesscategory-category_id").select2({
        theme: "krajee",
        dropdownCssClass: "select2-grid-system",
        placeholder: "' . Yii::t('app', 'Business Category') . '",
    });

    $("#registrybusinessproductcategory-product_category_id-parent").select2({
        theme: "krajee",
        dropdownCssClass: "select2-grid-system",
        placeholder: "' . Yii::t('app', 'Product Category General') . '"
    });

    $("#registrybusinessproductcategory-product_category_id-child").select2({
        theme: "krajee",
        dropdownCssClass: "select2-grid-system",
        placeholder: "' . Yii::t('app', 'Product Category Specific') . '"
    });

    $("#registrybusinessfacility-facility_id").select2({
        theme: "krajee",
        dropdownCssClass: "select2-grid-system",
        placeholder: "' . Yii::t('app', 'Facility') . '"
    });

    $(".business-hour-time.open").select2({
        theme: "krajee",
        placeholder: "' . Yii::t('app', 'Time Open') . '"
    });

    $(".business-hour-time.close").select2({
        theme: "krajee",
        placeholder: "' . Yii::t('app', 'Time Close') . '"
    });

    $(".business-hour-is-open").on("ifChecked",function(e){

        var elemDay = $(this).data("day");

        $("#business-hour-24h-" + elemDay).iCheck("enable");

        $("#registrybusinesshour-day"  + elemDay + "-open_at").removeAttr("disabled");
        $("#registrybusinesshour-day"  + elemDay + "-close_at").removeAttr("disabled");
    });

    $(".business-hour-is-open").on("ifUnchecked",function(e){

        var elemDay = $(this).data("day");

        $("#business-hour-24h-" + elemDay).iCheck("disable");
        $("#business-hour-24h-" + elemDay).iCheck("uncheck");

        $("#registrybusinesshour-day"  + elemDay + "-open_at").attr("disabled","disabled");
        $("#registrybusinesshour-day"  + elemDay + "-open_at").val(null).trigger("change");

        $("#registrybusinesshour-day"  + elemDay + "-close_at").attr("disabled","disabled");
        $("#registrybusinesshour-day"  + elemDay + "-close_at").val(null).trigger("change");
    });

    $(".business-hour-24h").on("ifChecked",function(e){

        var elemDay = $(this).data("day");

        $("#registrybusinesshour-day"  + elemDay + "-open_at").val("00:00:00").trigger("change");
        $("#registrybusinesshour-day"  + elemDay + "-close_at").val("24:00:00").trigger("change");
    });

    $(".business-hour-24h").on("ifUnchecked",function(e){

        var elemDay = $(this).data("day");

        $("#registrybusinesshour-day"  + elemDay + "-open_at").val(null).trigger("change");
        $("#registrybusinesshour-day"  + elemDay + "-close_at").val(null).trigger("change");
    });

    $(".set-all-business-hour").on("click", function() {

        $(".business-hour-is-open").each(function() {

            var thisObj = $(this);
            var rootParentThisObj = thisObj.parent().parent().parent().parent().parent();

            var businessHourIsOpenDay1 = $(".business-hour-is-open.day-1");
            var rootParentbusinessHourIsOpen = $(".business-hour-is-open.day-1").parent().parent().parent().parent().parent();

            var businessHourIsOpen = "uncheck";
            var businessHour24h = "uncheck";

            if (businessHourIsOpenDay1.is(":checked")) {
                businessHourIsOpen = "check";
            }

            if (rootParentbusinessHourIsOpen.find(".business-hour-24h").is(":checked")) {
                businessHour24h = "check";
            }

            $(this).iCheck(businessHourIsOpen);
            rootParentThisObj.find(".business-hour-24h").iCheck(businessHour24h);
            rootParentThisObj.find(".business-hour-time.open").val(rootParentbusinessHourIsOpen.find(".business-hour-time.open").val()).trigger("change");
            rootParentThisObj.find(".business-hour-time.close").val(rootParentbusinessHourIsOpen.find(".business-hour-time.close").val()).trigger("change");
        });

        return false;
    });
';

$this->registerJs(Yii::$app->params['checkbox-radio-script']() . $jscript); ?>