<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\ApplicationBusiness;
use core\models\Business;
use core\models\BusinessLocation;
use core\models\BusinessCategory;
use core\models\BusinessProductCategory;
use core\models\BusinessFacility;
use core\models\BusinessHour;
use core\models\BusinessDetail;
use core\models\BusinessImage;
use core\models\BusinessContactPerson;
use core\models\LogStatusApproval;
use core\models\RegistryBusiness;
use core\models\ContractMembership;
use yii\filters\VerbFilter;


/**
 * StatusApprovalController implements the CRUD actions for Status model.
 */
class StatusApprovalController extends \backoffice\controllers\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
            $this->getAccess(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [

                    ],
                ],
            ]);
    }

    public function actionResubmit($appBId, $regBId)
    {
        $flag = false;

        if (($flag = LogStatusApproval::updateAll(['is_actual' => 0], ['application_business_id' => $appBId]) > 0)) {

            $modelApplicationBusiness = ApplicationBusiness::findOne($appBId);
            $modelApplicationBusiness->counter = $modelApplicationBusiness->counter + 1;

            if (($flag = $modelApplicationBusiness->save())) {

                $modelLogStatusApproval = new LogStatusApproval();
                $modelLogStatusApproval->application_business_id = $appBId;
                $modelLogStatusApproval->status_approval_id = 'PNDG';
                $modelLogStatusApproval->application_business_counter = $modelApplicationBusiness->counter;
                $modelLogStatusApproval->is_actual = 1;

                if (($flag = $modelLogStatusApproval->save())) {

                    $modelRegistryBusiness = RegistryBusiness::findOne(['id' => $regBId]);
                    $modelRegistryBusiness->application_business_counter = $modelApplicationBusiness->counter;

                    $flag = $modelRegistryBusiness->save();
                }
            }
        }

        return $flag;
    }

    public function actionApprove($regBId)
    {
        $flag = false;

        $modelRegistryBusiness = RegistryBusiness::findOne(['id' => $regBId]);

        if (($flag = $modelRegistryBusiness->save())) {

            $modelBusiness = new Business();
            $modelBusiness->membership_type_id = $modelRegistryBusiness->membership_type_id;
            $modelBusiness->application_business_id = $modelRegistryBusiness->application_business_id;
            $modelBusiness->about = $modelRegistryBusiness->about;
            $modelBusiness->name = $modelRegistryBusiness->name;
            $modelBusiness->unique_name = $modelRegistryBusiness->unique_name;
            $modelBusiness->email = $modelRegistryBusiness->email;
            $modelBusiness->phone1 = $modelRegistryBusiness->phone1;
            $modelBusiness->phone2 = $modelRegistryBusiness->phone2;
            $modelBusiness->phone3 = $modelRegistryBusiness->phone3;
            $modelBusiness->note = $modelRegistryBusiness->note;
            $modelBusiness->user_in_charge = $modelRegistryBusiness->user_in_charge;

            if (($flag = $modelBusiness->save())) {

                $modelBusinessLocation = new BusinessLocation();
                $modelBusinessLocation->business_id = $modelBusiness->id;
                $modelBusinessLocation->address_type = $modelRegistryBusiness->address_type;
                $modelBusinessLocation->address = $modelRegistryBusiness->address;
                $modelBusinessLocation->address_info = $modelRegistryBusiness->address_info;
                $modelBusinessLocation->city_id = $modelRegistryBusiness->city_id;
                $modelBusinessLocation->district_id = $modelRegistryBusiness->district_id;
                $modelBusinessLocation->village_id = $modelRegistryBusiness->village_id;
                $modelBusinessLocation->coordinate = $modelRegistryBusiness->coordinate;

                $flag = $modelBusinessLocation->save();
            }

            if ($flag) {

                foreach ($modelRegistryBusiness->registryBusinessCategories as $value) {

                    $modelBusinessCategory = new BusinessCategory();
                    $modelBusinessCategory->unique_id = $modelBusiness->id . '-' . $value->category_id;
                    $modelBusinessCategory->business_id = $modelBusiness->id;
                    $modelBusinessCategory->category_id = $value->category_id;
                    $modelBusinessCategory->is_active = $value->is_active;

                    if (!($flag = $modelBusinessCategory->save())) {
                        break;
                    }
                }
            }

            if ($flag) {

                foreach ($modelRegistryBusiness->registryBusinessProductCategories as $value) {

                    $modelBusinessProductCategory = new BusinessProductCategory();
                    $modelBusinessProductCategory->unique_id = $modelBusiness->id . '-' . $value->product_category_id;
                    $modelBusinessProductCategory->business_id = $modelBusiness->id;
                    $modelBusinessProductCategory->product_category_id = $value->product_category_id;
                    $modelBusinessProductCategory->is_active = $value->is_active;

                    if (!($flag = $modelBusinessProductCategory->save())) {
                        break;
                    }
                }
            }

            if ($flag) {

                foreach ($modelRegistryBusiness->registryBusinessFacilities as $value) {

                    $modelBusinessFacility = new BusinessFacility();
                    $modelBusinessFacility->unique_id = $modelBusiness->id . '-' . $value->facility_id;
                    $modelBusinessFacility->business_id = $modelBusiness->id;
                    $modelBusinessFacility->facility_id = $value->facility_id;
                    $modelBusinessFacility->is_active = $value->is_active;

                    if (!($flag = $modelBusinessFacility->save())) {
                        break;
                    }
                }
            }

            if ($flag) {

                foreach ($modelRegistryBusiness->registryBusinessHours as $value) {

                    $modelBusinessHour = new BusinessHour();
                    $modelBusinessHour->unique_id = $modelBusiness->id . '-' . $value->day;
                    $modelBusinessHour->business_id = $modelBusiness->id;
                    $modelBusinessHour->day = $value->day;
                    $modelBusinessHour->is_open = $value->is_open;
                    $modelBusinessHour->open_at = $value->open_at;
                    $modelBusinessHour->close_at = $value->close_at;

                    if (!($flag = $modelBusinessHour->save())) {
                        break;
                    }
                }
            }

            if ($flag) {

                $modelBusinessDetail = new BusinessDetail();
                $modelBusinessDetail->business_id = $modelBusiness->id;
                $modelBusinessDetail->price_min = $modelRegistryBusiness->price_min;
                $modelBusinessDetail->price_max = $modelRegistryBusiness->price_max;

                $flag = $modelBusinessDetail->save();
            }

            if ($flag) {

                foreach ($modelRegistryBusiness->registryBusinessImages as $value) {

                    $modelBusinessImage = new BusinessImage();
                    $modelBusinessImage->business_id = $modelBusiness->id;
                    $modelBusinessImage->image = $value->image;
                    $modelBusinessImage->type = $value->type;
                    $modelBusinessImage->is_primary = $value->is_primary;
                    $modelBusinessImage->category = $value->category;

                    if (!($flag = $modelBusinessImage->save())) {
                        break;
                    }
                }
            }

            if ($flag) {

                if (!empty($modelRegistryBusiness->registryBusinessContactPeople)) {

                    foreach ($modelRegistryBusiness->registryBusinessContactPeople as $value) {

                        $modelBusinessContactPerson = new BusinessContactPerson();
                        $modelBusinessContactPerson->business_id = $modelBusiness->id;
                        $modelBusinessContactPerson->person_id = $value->person_id;
                        $modelBusinessContactPerson->is_primary_contact = $value->is_primary_contact;
                        $modelBusinessContactPerson->note = $value->note;
                        $modelBusinessContactPerson->position = $value->position;

                        if (!($flag = $modelBusinessContactPerson->save())) {
                            break;
                        }
                    }
                }
            }

            if ($flag) {

                $modelContractMembership = new ContractMembership();
                $modelContractMembership->registry_business_id = $modelRegistryBusiness->id;
                $modelContractMembership->business_id = $modelBusiness->id;
                $modelContractMembership->membership_type_id = $modelRegistryBusiness->membership_type_id;
                $modelContractMembership->price = $modelRegistryBusiness->membershipType->price;
                $modelContractMembership->started_at = Yii::$app->formatter->asDatetime(time());

                if (empty($modelRegistryBusiness->membershipType->time_limit)) {
                    
                    $modelContractMembership->due_at = null;
                } else {
                    
                    $modelContractMembership->due_at = Yii::$app->formatter->asDatetime(time() + ($modelRegistryBusiness->membershipType->time_limit * 30 * 24 * 3600));
                }

                $flag = $modelContractMembership->save();
            }
        }

        return $flag;
    }
}