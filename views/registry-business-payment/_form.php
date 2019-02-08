<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use core\models\PaymentMethod;
use sycomponent\AjaxRequest;
use sycomponent\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model core\models\RegistryBusinessPayment */
/* @var $modelRegistryBusiness core\models\RegistryBusiness */
/* @var $appBId string */
/* @var $actid string */
/* @var $logsaid string */
/* @var $form yii\widgets\ActiveForm */

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this);

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusinessPayment',
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

echo $ajaxRequest->component(); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="registry-business-payment-form">

                <?php
                $form = ActiveForm::begin([
                    'id' => 'registry-business-payment-form',
                    'action' => $model->isNewRecord ? ['create', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid] : ['update', 'id' => $model->id, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid],
                    'options' => [

                    ],
                    'fieldConfig' => [
                        'parts' => [
                            '{inputClass}' => 'col-lg-6'
                        ],
                        'template' => '
                            <div class="row">
                                <div class="col-lg-3">
                                    {label}
                                </div>
                                <div class="{inputClass}">
                                    {input}
                                </div>
                                <div class="col-lg-3">
                                    {error}
                                </div>
                            </div>',
                    ]
                ]); ?>

                    <div class="x_title">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                
                                    <?php
                                    if (!$model->isNewRecord)
                                        echo Html::a('<i class="fa fa-upload"></i> ' . 'Create', ['create', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-success']); ?>
                                        
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="x_content">

                        <?= $form->field($model, 'payment_method_id')->dropDownList(
                            ArrayHelper::map(
                                PaymentMethod::find()->andWhere(['not_active' => false])->orderBy(['id' => SORT_ASC])->asArray()->all(),
                                'id',
                                function ($data) {
                                    
                                    return $data['payment_name'];
                                }
                            ),
                            [
                                'prompt' => '',
                                'style' => 'width: 100%'
                            ]) ?>
                    
                        <?= $form->field($model, 'note')->textarea(['rows' => 2]) ?>
                        
                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                        
                        <?= $form->field($model, 'is_active')->checkbox(['value' => true], false) ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-offset-3 col-lg-6">
                                
                                    <?php
                                    $icon = '<i class="fa fa-save"></i> ';
                                    echo Html::submitButton($model->isNewRecord ? $icon . 'Save' : $icon . 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                                    echo Html::a('<i class="fa fa-times"></i> Cancel', ['index', 'id' => $modelRegistryBusiness['id'], 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid], ['class' => 'btn btn-default']); ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<?php
$this->registerCssFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/skins/all.css', ['depends' => 'yii\web\YiiAsset']);

$this->registerJsFile($this->params['assetCommon']->baseUrl . '/plugins/icheck/icheck.min.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $("#registrybusinesspayment-payment_method_id").select2({
        theme: "krajee",
        placeholder: "' . Yii::t('app', 'Payment Methods') . '"
    });

    function notes(executeRemote) {

        var setNotes = function(remoteData) {

            $("#registrybusinesspayment-note").val(remoteData.note);
            $("#registrybusinesspayment-description").val(remoteData.description);
        };

        if (executeRemote) {
            
            $.ajax({
                dataType: "json",
                cache: false,
                url: "' . Yii::$app->urlManager->createUrl(['masterdata/payment-method/get-notes-by-payment-method']) . '?id=" + $("#registrybusinesspayment-payment_method_id").select2("data")[0].id,
                success: function(response) {
                    
                    setNotes(response);
                }
            });
        } else {

            setNotes([]);
        }
    };

    $("#registrybusinesspayment-payment_method_id").on("select2:select", function() {
        
        notes(true);
    });
';

$this->registerJs(Yii::$app->params['checkbox-radio-script']() . $jscript); ?>