<?php

namespace backoffice\modules\approval;

use Yii;

/**
 * approval module definition class
 */
class ApprovalModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backoffice\modules\approval\controllers';
    public $defaultRoute = 'status/index';
    public $name = 'Approval';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        Yii::configure($this, require __DIR__ . '/config/navigation.php');
    }
}
