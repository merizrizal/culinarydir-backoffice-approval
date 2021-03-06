<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use sycomponent\AjaxRequest;
use sycomponent\ModalDialog;
use sycomponent\NotificationDialog;
use core\models\District;
use core\models\Village;
use core\models\MembershipType;

/* @var $this yii\web\View */
/* @var $searchModel core\models\search\RegistryBusinessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this);

$ajaxRequest = new AjaxRequest([
    'modelClass' => 'RegistryBusiness'
]);

$ajaxRequest->index();

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

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'New Application'), 'url' => ['status/pndg-application']];
$this->params['breadcrumbs'][] = $this->title; ?>

<?= $ajaxRequest->component(false) ?>

<div class="registry-business-index">

    <?php
    $modalDialog = new ModalDialog([
        'clickedComponent' => 'a#delete',
        'modelAttributeId' => 'model-id',
        'modelAttributeName' => 'model-name',
    ]); ?>

    <?= GridView::widget([
        'id' => 'grid-view-registry-business',
        'dataProvider' => $dataProvider,
        'pjax' => false,
        'bordered' => false,
        'panelHeadingTemplate' => '
            <div class="kv-panel-pager pull-right" style="text-align:right">
                {pager}{summary}
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    ' . Html::dropDownList('RegistryBusinessSearch[district_id]', (!empty(Yii::$app->request->get('RegistryBusinessSearch')['district_id']) ? Yii::$app->request->get('RegistryBusinessSearch')['district_id'] : null),
                            ArrayHelper::map(
                                District::find()->orderBy('name')->asArray()->all(),
                                'id',
                                function($data) {
                                    return $data['name'];
                                }
                            ),
                            [
                                'id' => 'registrybusiness-district_id',
                                'class' => 'form-control',
                                'prompt' => Yii::t('app', 'District'),
                            ]
                    ) . '
                </div>
                <div class="col-lg-4 col-md-4">
                    ' . Html::dropDownList('RegistryBusinessSearch[village_id]', (!empty(Yii::$app->request->get('RegistryBusinessSearch')['village_id']) ? Yii::$app->request->get('RegistryBusinessSearch')['village_id'] : null),
                            ArrayHelper::map(
                                Village::find()->orderBy('name')->asArray()->all(),
                                'id',
                                function($data) {
                                    return $data['name'];
                                }
                            ),
                            [
                                'id' => 'registrybusiness-village_id',
                                'class' => 'form-control',
                                'prompt' => Yii::t('app', 'Village'),
                            ]
                    ) . '
                </div>
            </div>'
        ,
        'panelFooterTemplate' => '
            <div class="kv-panel-pager pull-right" style="text-align:right">
                {summary}{pager}
            </div>
            {footer}
            <div class="clearfix"></div>'
        ,
        'panel' => [
            'heading' => '',
        ],
        'toolbar' => [
            [
                'content' => Html::a('<i class="fa fa-sync-alt"></i>', [strtolower($statusApproval) . '-application'], [
                'id' => 'refresh',
                'class' => 'btn btn-success',
                'data-placement' => 'top',
                'data-toggle' => 'tooltip',
                'title' => 'Refresh'
                ])
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'created_at:date',
            'name',
            'unique_name',

            [
                'attribute' => 'membershipType.name',
                'format' => 'raw',
                'filter' =>  ArrayHelper::map(
                                MembershipType::find()->orderBy('order')->asArray()->all(),
                                'name',
                                function($data) {
                                    return $data['name'];
                                }
                            ),
            ],

            'userInCharge.full_name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
                    <div class="btn-container hide">
                        <div class="visible-lg visible-md">
                            <div class="btn-group btn-group-md" role="group" style="width: 40px">
                                {view-application}
                            </div>
                        </div>
                        <div class="visible-sm visible-xs">
                            <div class="btn-group btn-group-lg" role="group" style="width: 52px">
                                {view-application}
                            </div>
                        </div>
                    </div>',
                'buttons' => [
                    'view-application' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-search-plus"></i>', ['view-application', 'id' => $model->id, 'appBId' => $model->application_business_id], [
                            'id' => 'view',
                            'class' => 'btn btn-primary',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'View Application',
                        ]);
                    },
                ]
            ],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-hover'
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['id' => $model['id'], 'class' => 'row-grid-view-registry-business', 'style' => 'cursor: pointer;'];
        },
        'pager' => [
            'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
        ],
    ]); ?>

</div>

<?= $modalDialog->renderDialog() ?>

<?php
$jscript = ''
    . $modalDialog->getScript() . '

    $("div.container.body").off("click");
    $("div.container.body").on("click", function(event) {

        if ($(event.target).parent(".row-grid-view-registry-business").length > 0) {

            $("td").not(event.target).popover("destroy");
        } else {
            $(".popover.in").popover("destroy");
        }
    });

    $(".row-grid-view-registry-business").popover({
        trigger: "click",
        placement: "top",
        container: ".row-grid-view-registry-business",
        html: true,
        selector: "td",
        content: function () {
            var content = $(this).parent().find(".btn-container").html();

            return $(content);
        }
    });

    $(".row-grid-view-registry-business").on("shown.bs.popover", function(event) {

        $(\'[data-toggle="tooltip"]\').tooltip();

        var popoverId = $(event.target).attr("aria-describedby");

        $(document).on("click", "#" + popoverId + " a", function(event) {

            if ($(this).attr("data-not-ajax") == undefined) {
                ajaxRequest($(this));
            }

            return false;
        });
    });

    $("#registrybusiness-district_id").select2({
        theme: "krajee",
    });

    $("#registrybusiness-village_id").select2({
        theme: "krajee",
    });
';

$this->registerJs($jscript); ?>