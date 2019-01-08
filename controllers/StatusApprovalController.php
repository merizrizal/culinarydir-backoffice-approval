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
use core\models\BusinessHourAdditional;
use core\models\BusinessDetail;
use core\models\BusinessImage;
use core\models\BusinessContactPerson;
use core\models\LogStatusApproval;
use core\models\RegistryBusiness;
use core\models\ContractMembership;
use core\models\BusinessPayment;
use core\models\BusinessDelivery;
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

        $modelRegistryBusiness = RegistryBusiness::find()
            ->joinWith([
                'registryBusinessHours' => function ($query) {
                    
                    $query->orderBy(['registry_business_hour.day' => SORT_ASC]);
                },
                'registryBusinessHours.registryBusinessHourAdditionals' 
            ])
            ->andWhere(['registry_business.id' => $regBId])
            ->one();

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
        }

        if ($flag = $modelBusinessLocation->save()) {

            foreach ($modelRegistryBusiness->registryBusinessCategories as $dataRegistryBusinessCategory) {

                $modelBusinessCategory = new BusinessCategory();
                $modelBusinessCategory->unique_id = $modelBusiness->id . '-' . $dataRegistryBusinessCategory->category_id;
                $modelBusinessCategory->business_id = $modelBusiness->id;
                $modelBusinessCategory->category_id = $dataRegistryBusinessCategory->category_id;
                $modelBusinessCategory->is_active = $dataRegistryBusinessCategory->is_active;

                if (!($flag = $modelBusinessCategory->save())) {
                    
                    break;
                }
            }
        }

        if ($flag) {

            foreach ($modelRegistryBusiness->registryBusinessProductCategories as $dataRegistryBusinessProductCategory) {

                $modelBusinessProductCategory = new BusinessProductCategory();
                $modelBusinessProductCategory->unique_id = $modelBusiness->id . '-' . $dataRegistryBusinessProductCategory->product_category_id;
                $modelBusinessProductCategory->business_id = $modelBusiness->id;
                $modelBusinessProductCategory->product_category_id = $dataRegistryBusinessProductCategory->product_category_id;
                $modelBusinessProductCategory->is_active = $dataRegistryBusinessProductCategory->is_active;

                if (!($flag = $modelBusinessProductCategory->save())) {
                    
                    break;
                }
            }
        }

        if ($flag) {

            foreach ($modelRegistryBusiness->registryBusinessFacilities as $dataRegistryBusinessFacility) {

                $modelBusinessFacility = new BusinessFacility();
                $modelBusinessFacility->unique_id = $modelBusiness->id . '-' . $dataRegistryBusinessFacility->facility_id;
                $modelBusinessFacility->business_id = $modelBusiness->id;
                $modelBusinessFacility->facility_id = $dataRegistryBusinessFacility->facility_id;
                $modelBusinessFacility->is_active = $dataRegistryBusinessFacility->is_active;

                if (!($flag = $modelBusinessFacility->save())) {
                    
                    break;
                }
            }
        }

        if ($flag) {

            foreach ($modelRegistryBusiness->registryBusinessHours as $dataRegistryBusinessHour) {

                $modelBusinessHour = new BusinessHour();
                $modelBusinessHour->unique_id = $modelBusiness->id . '-' . $dataRegistryBusinessHour->day;
                $modelBusinessHour->business_id = $modelBusiness->id;
                $modelBusinessHour->day = $dataRegistryBusinessHour->day;
                $modelBusinessHour->is_open = $dataRegistryBusinessHour->is_open;
                $modelBusinessHour->open_at = $dataRegistryBusinessHour->open_at;
                $modelBusinessHour->close_at = $dataRegistryBusinessHour->close_at;

                if (!($flag = $modelBusinessHour->save())) {
                    
                    break;
                }
                
                foreach ($dataRegistryBusinessHour->registryBusinessHourAdditionals as $i => $dataRegistryBusinessHourAdditional) {
                    
                    $modelBusinessHourAdditional = new BusinessHourAdditional();
                    $modelBusinessHourAdditional->unique_id = $modelBusinessHour->id . '-' . $dataRegistryBusinessHourAdditional->day . '-' . ($i + 1);
                    $modelBusinessHourAdditional->business_hour_id = $modelBusinessHour->id;
                    $modelBusinessHourAdditional->day = $modelBusinessHour->day;
                    $modelBusinessHourAdditional->is_open = $modelBusinessHour->is_open;
                    $modelBusinessHourAdditional->open_at = $dataRegistryBusinessHourAdditional->open_at;
                    $modelBusinessHourAdditional->close_at = $dataRegistryBusinessHourAdditional->close_at;
                    
                    if (!($flag = $modelBusinessHourAdditional->save())) {
                        
                        break;
                    }
                }
            }
        }

        if ($flag) {

            $modelBusinessDetail = new BusinessDetail();
            $modelBusinessDetail->business_id = $modelBusiness->id;
            $modelBusinessDetail->price_min = $modelRegistryBusiness->price_min;
            $modelBusinessDetail->price_max = $modelRegistryBusiness->price_max;
            $modelBusinessDetail->note_business_hour = $modelRegistryBusiness->note_business_hour;

            $flag = $modelBusinessDetail->save();
        }

        if ($flag) {

            foreach ($modelRegistryBusiness->registryBusinessImages as $dataRegistryBusinessImage) {

                $modelBusinessImage = new BusinessImage();
                $modelBusinessImage->business_id = $modelBusiness->id;
                $modelBusinessImage->image = $dataRegistryBusinessImage->image;
                $modelBusinessImage->type = $dataRegistryBusinessImage->type;
                $modelBusinessImage->is_primary = $dataRegistryBusinessImage->is_primary;
                $modelBusinessImage->category = $dataRegistryBusinessImage->category;
                $modelBusinessImage->order = $dataRegistryBusinessImage->order;

                if (!($flag = $modelBusinessImage->save())) {
                    
                    break;
                }
            }
        }

        if ($flag) {

            if (!empty($modelRegistryBusiness->registryBusinessContactPeople)) {

                foreach ($modelRegistryBusiness->registryBusinessContactPeople as $dataRegistryBusinessContactPerson) {

                    $modelBusinessContactPerson = new BusinessContactPerson();
                    $modelBusinessContactPerson->business_id = $modelBusiness->id;
                    $modelBusinessContactPerson->person_id = $dataRegistryBusinessContactPerson->person_id;
                    $modelBusinessContactPerson->is_primary_contact = $dataRegistryBusinessContactPerson->is_primary_contact;
                    $modelBusinessContactPerson->note = $dataRegistryBusinessContactPerson->note;
                    $modelBusinessContactPerson->position = $dataRegistryBusinessContactPerson->position;

                    if (!($flag = $modelBusinessContactPerson->save())) {
                        
                        break;
                    }
                }
            }
        }
            
        if ($flag) {
            
            foreach ($modelRegistryBusiness->registryBusinessPayments as $dataRegistryBusinessPayment) {
            
                $modelBusinessPayment = new BusinessPayment();
                $modelBusinessPayment->business_id = $modelBusiness->id;
                $modelBusinessPayment->payment_method_id = $dataRegistryBusinessPayment->payment_method_id;
                $modelBusinessPayment->is_active = $dataRegistryBusinessPayment->is_active;
                $modelBusinessPayment->note = !empty($dataRegistryBusinessPayment->note) ? $dataRegistryBusinessPayment->note : null;
                $modelBusinessPayment->description = !empty($dataRegistryBusinessPayment->description) ? $dataRegistryBusinessPayment->description : null;
                
                if (!($flag = $modelBusinessPayment->save())) {
                    
                    break;
                }
            }
        }
            
        if ($flag) {
            
            foreach ($modelRegistryBusiness->registryBusinessDeliveries as $dataRegistryBusinessDelivery) {
                
                $modelBusinessDelivery = new BusinessDelivery();
                $modelBusinessDelivery->business_id = $modelBusiness->id;
                $modelBusinessDelivery->delivery_method_id = $dataRegistryBusinessDelivery->delivery_method_id;
                $modelBusinessDelivery->is_active = $dataRegistryBusinessDelivery->is_active;
                $modelBusinessDelivery->note = !empty($dataRegistryBusinessDelivery->note) ? $dataRegistryBusinessDelivery->note : null;
                $modelBusinessDelivery->description = !empty($dataRegistryBusinessDelivery->description) ? $dataRegistryBusinessDelivery->description : null;
                
                if (!($flag = $modelBusinessDelivery->save())) {
                    
                    break;
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

        return $flag;
    }
}