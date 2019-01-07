<?php

namespace backoffice\modules\approval\controllers;

use Yii;
use core\models\Person;
use core\models\RegistryBusiness;
use core\models\RegistryBusinessCategory;
use core\models\RegistryBusinessImage;
use core\models\RegistryBusinessProductCategory;
use core\models\RegistryBusinessHour;
use core\models\RegistryBusinessHourAdditional;
use core\models\RegistryBusinessFacility;
use core\models\RegistryBusinessContactPerson;
use sycomponent\AjaxRequest;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * RegistryBusinessController implements the CRUD actions for RegistryBusiness model.
 */
class RegistryBusinessController extends \backoffice\controllers\BaseController
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

    public function actionUpdateBusinessInfo($id, $save = null, $appBId, $actid, $logsaid)
    {
        $model = RegistryBusiness::findOne($id);
        
        if ($model->load(Yii::$app->request->post())) {
            
            if (empty($save)) {
                
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                
                $model->setCoordinate();
                
                if ($model->save()) {
                    
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
                } else {
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                }
            }
        }
        
        return $this->render('update_business_info', [
            'model' => $model,
            'id' => $id,
            'appBId' => $appBId,
            'actid' => $actid,
            'logsaid' => $logsaid
        ]);
    }
    
    public function actionUpdateMarketingInfo($id, $save = null, $appBId, $actid, $logsaid)
    {
        $model = RegistryBusiness::find()
            ->joinWith([
                'registryBusinessCategories' => function ($query) {
                
                    $query->andOnCondition(['registry_business_category.is_active' => true]);
                },
                'registryBusinessCategories.category',
                'registryBusinessProductCategories' => function ($query) {
                
                    $query->andOnCondition(['registry_business_product_category.is_active' => true]);
                },
                'registryBusinessProductCategories.productCategory',
                'registryBusinessFacilities' => function ($query) {
                
                    $query->andOnCondition(['registry_business_facility.is_active' => true]);
                },
                'registryBusinessFacilities.facility',
            ])
            ->andWhere(['registry_business.id' => $id])
            ->one();
            
        $modelRegistryBusinessCategory = new RegistryBusinessCategory();
        $dataRegistryBusinessCategory = [];
        
        $modelRegistryBusinessProductCategory = new RegistryBusinessProductCategory();
        $dataRegistryBusinessProductCategoryParent = [];
        $dataRegistryBusinessProductCategoryChild = [];
        
        $modelRegistryBusinessFacility = new RegistryBusinessFacility();
        $dataRegistryBusinessFacility = [];
        
        if ($model->load(($post = Yii::$app->request->post()))) {
            
            if (!empty($save)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                $flag = false;
                
                $model->price_min = !empty($model->price_min) ? $model->price_min : 0;
                $model->price_max = !empty($model->price_max) ? $model->price_max : 0;
                
                if (($flag = $model->save())) {
                    
                    if (!empty($post['RegistryBusinessCategory']['category_id'])) {
                        
                        foreach ($post['RegistryBusinessCategory']['category_id'] as $categoryId) {
                            
                            $newModelRegistryBusinessCategory = RegistryBusinessCategory::findOne(['unique_id' => $model->id . '-' . $categoryId]);
                            
                            if (!empty($newModelRegistryBusinessCategory)) {
                                
                                $newModelRegistryBusinessCategory->is_active = true;
                            } else {
                                
                                $newModelRegistryBusinessCategory = new RegistryBusinessCategory();
                                $newModelRegistryBusinessCategory->unique_id = $model->id . '-' . $categoryId;
                                $newModelRegistryBusinessCategory->registry_business_id = $model->id;
                                $newModelRegistryBusinessCategory->category_id = $categoryId;
                                $newModelRegistryBusinessCategory->is_active = true;
                            }
                            
                            if (!($flag = $newModelRegistryBusinessCategory->save())) {
                                
                                break;
                            } else {
                                
                                array_push($dataRegistryBusinessCategory, $newModelRegistryBusinessCategory->toArray());
                            }
                        }
                        
                        if ($flag) {
                            
                            foreach ($model->registryBusinessCategories as $existModelRegistryBusinessCategory) {
                                
                                $exist = false;
                                
                                foreach ($post['RegistryBusinessCategory']['category_id'] as $categoryId) {
                                    
                                    if ($existModelRegistryBusinessCategory['category_id'] == $categoryId) {
                                        
                                        $exist = true;
                                        break;
                                    }
                                }
                                
                                if (!$exist) {
                                    
                                    $existModelRegistryBusinessCategory->is_active = false;
                                    
                                    if (!($flag = $existModelRegistryBusinessCategory->save())) {
                                        
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    if (!empty($post['RegistryBusinessProductCategory']['product_category_id']['parent'])) {
                        
                        foreach ($post['RegistryBusinessProductCategory']['product_category_id']['parent'] as $productCategoryId) {
                            
                            $newModelRegistryBusinessProductCategory = RegistryBusinessProductCategory::findOne(['unique_id' => $model->id . '-' . $productCategoryId]);
                            
                            if (!empty($newModelRegistryBusinessProductCategory)) {
                                
                                $newModelRegistryBusinessProductCategory->is_active = true;
                            } else {
                                
                                $newModelRegistryBusinessProductCategory = new RegistryBusinessProductCategory();
                                $newModelRegistryBusinessProductCategory->unique_id = $model->id . '-' . $productCategoryId;
                                $newModelRegistryBusinessProductCategory->registry_business_id = $model->id;
                                $newModelRegistryBusinessProductCategory->product_category_id = $productCategoryId;
                                $newModelRegistryBusinessProductCategory->is_active = true;
                            }
                            
                            if (!($flag = $newModelRegistryBusinessProductCategory->save())) {
                                
                                break;
                            } else {
                                
                                array_push($dataRegistryBusinessProductCategoryParent, $newModelRegistryBusinessProductCategory->toArray());
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    if (!empty($post['RegistryBusinessProductCategory']['product_category_id']['child'])) {
                        
                        foreach ($post['RegistryBusinessProductCategory']['product_category_id']['child'] as $productCategoryId) {
                            
                            $newModelRegistryBusinessProductCategory = RegistryBusinessProductCategory::findOne(['unique_id' => $model->id . '-' . $productCategoryId]);
                            
                            if (!empty($newModelRegistryBusinessProductCategory)) {
                                
                                $newModelRegistryBusinessProductCategory->is_active = true;
                            } else {
                                
                                $newModelRegistryBusinessProductCategory = new RegistryBusinessProductCategory();
                                $newModelRegistryBusinessProductCategory->unique_id = $model->id . '-' . $productCategoryId;
                                $newModelRegistryBusinessProductCategory->registry_business_id = $model->id;
                                $newModelRegistryBusinessProductCategory->product_category_id = $productCategoryId;
                                $newModelRegistryBusinessProductCategory->is_active = true;
                            }
                            
                            if (!($flag = $newModelRegistryBusinessProductCategory->save())) {
                                
                                break;
                            } else {
                                
                                array_push($dataRegistryBusinessProductCategoryChild, $newModelRegistryBusinessProductCategory->toArray());
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    if (!empty($post['RegistryBusinessProductCategory']['product_category_id']['parent']) && !empty($post['RegistryBusinessProductCategory']['product_category_id']['child'])) {
                        
                        foreach ($model->registryBusinessProductCategories as $existModelRegistryBusinessProductCategory) {
                            
                            $exist = false;
                            
                            foreach ($post['RegistryBusinessProductCategory']['product_category_id'] as $dataProductCategory) {
                                
                                foreach ($dataProductCategory as $productCategoryId) {
                                    
                                    if ($existModelRegistryBusinessProductCategory['product_category_id'] == $productCategoryId) {
                                        
                                        $exist = true;
                                        break 2;
                                    }
                                }
                            }
                            
                            if (!$exist) {
                                
                                $existModelRegistryBusinessProductCategory->is_active = false;
                                
                                if (!($flag = $existModelRegistryBusinessProductCategory->save())) {
                                    
                                    break;
                                }
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    if (!empty($post['RegistryBusinessFacility']['facility_id'])) {
                        
                        foreach ($post['RegistryBusinessFacility']['facility_id'] as $facilityId) {
                            
                            $newModelRegistryBusinessFacility = RegistryBusinessFacility::findOne(['unique_id' => $model->id . '-' . $facilityId]);
                            
                            if (!empty($newModelRegistryBusinessFacility)) {
                                
                                $newModelRegistryBusinessFacility->is_active = true;
                            } else {
                                
                                $newModelRegistryBusinessFacility = new RegistryBusinessFacility();
                                $newModelRegistryBusinessFacility->unique_id = $model->id . '-' . $facilityId;
                                $newModelRegistryBusinessFacility->registry_business_id = $model->id;
                                $newModelRegistryBusinessFacility->facility_id = $facilityId;
                                $newModelRegistryBusinessFacility->is_active = true;
                            }
                            
                            if (!($flag = $newModelRegistryBusinessFacility->save())) {
                                
                                break;
                            } else {
                                
                                array_push($dataRegistryBusinessFacility, $newModelRegistryBusinessFacility->toArray());
                            }
                        }
                        
                        if ($flag) {
                            
                            foreach ($model->registryBusinessFacilities as $existModelRegistryBusinessFacility) {
                                
                                $exist = false;
                                
                                foreach ($post['RegistryBusinessFacility']['facility_id'] as $facilityId) {
                                    
                                    if ($existModelRegistryBusinessFacility['facility_id'] == $facilityId) {
                                        
                                        $exist = true;
                                        break;
                                    }
                                }
                                
                                if (!$exist) {
                                    
                                    $existModelRegistryBusinessFacility->is_active = false;
                                    
                                    if (!($flag = $existModelRegistryBusinessFacility->save())) {
                                        
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
                    
                    $transaction->commit();
                } else {
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                    
                    $transaction->rollBack();
                }
            }
        }
        
        $registryBusinessProductCategoryParent = [];
        $registryBusinessProductCategoryChild = [];
        
        foreach ($model->registryBusinessProductCategories as $existModelRegistryBusinessProductCategory) {
            
            if ($existModelRegistryBusinessProductCategory['productCategory']['is_parent']) {
                
                $registryBusinessProductCategoryParent[] = $existModelRegistryBusinessProductCategory;
            } else {
                
                $registryBusinessProductCategoryChild[] = $existModelRegistryBusinessProductCategory;
            }
        }
        
        $dataRegistryBusinessCategory = empty($dataRegistryBusinessCategory) ? $model->registryBusinessCategories : $dataRegistryBusinessCategory;
        $dataRegistryBusinessProductCategoryParent = empty($dataRegistryBusinessProductCategoryParent) ? $registryBusinessProductCategoryParent : $dataRegistryBusinessProductCategoryParent;
        $dataRegistryBusinessProductCategoryChild = empty($dataRegistryBusinessProductCategoryChild) ? $registryBusinessProductCategoryChild : $dataRegistryBusinessProductCategoryChild;
        $dataRegistryBusinessFacility = empty($dataRegistryBusinessFacility) ? $model->registryBusinessFacilities : $dataRegistryBusinessFacility;
        
        return $this->render('update_marketing_info', [
            'model' => $model,
            'modelRegistryBusinessCategory' => $modelRegistryBusinessCategory,
            'dataRegistryBusinessCategory' => $dataRegistryBusinessCategory,
            'modelRegistryBusinessProductCategory' => $modelRegistryBusinessProductCategory,
            'dataRegistryBusinessProductCategoryParent' => $dataRegistryBusinessProductCategoryParent,
            'dataRegistryBusinessProductCategoryChild' => $dataRegistryBusinessProductCategoryChild,
            'modelRegistryBusinessFacility' => $modelRegistryBusinessFacility,
            'dataRegistryBusinessFacility' => $dataRegistryBusinessFacility,
            'id' => $id,
            'appBId' => $appBId,
            'actid' => $actid,
            'logsaid' => $logsaid
        ]);
    }
    
    public function actionUpdateGalleryPhoto($id, $save = null, $appBId, $actid, $logsaid)
    {
        $model = RegistryBusiness::find()
            ->joinWith([
                'registryBusinessImages' => function ($query) {
                
                    $query->orderBy(['order' => SORT_ASC]);
                }
            ])
            ->andWhere(['registry_business.id' => $id])
            ->one();
            
        if (!empty(($post = Yii::$app->request->post()))) {
            
            if (!empty($save)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                
                foreach ($model->registryBusinessImages as $modelRegistryBusinessImage) {
                    
                    $modelRegistryBusinessImage->type = !empty($post['profile'][$modelRegistryBusinessImage->id]) ? 'Profile' : 'Gallery';
                    $modelRegistryBusinessImage->is_primary = !empty($post['thumbnail']) && $post['thumbnail'] == $modelRegistryBusinessImage->id ? true : false;
                    $modelRegistryBusinessImage->category = $post['category'][$modelRegistryBusinessImage->id];
                    
                    if (!($flag = $modelRegistryBusinessImage->save())) {
                        
                        break;
                    }
                }
                
                if ($flag) {
                    
                    $transaction->commit();
                    
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
                } else {
                    
                    $transaction->rollBack();
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                }
            }
        }
        
        return $this->render('update_gallery_photo', [
            'model' => $model,
            'id' => $id,
            'appBId' => $appBId,
            'actid' => $actid,
            'logsaid' => $logsaid
        ]);
    }
    
    public function actionUpdateContactPerson($id, $save = null, $appBId, $actid, $logsaid)
    {
        $model = RegistryBusiness::find()
            ->joinWith([
                'registryBusinessContactPeople' => function ($query) {
                
                    $query->orderBy(['registry_business_contact_person.id' => SORT_ASC]);
                },
                'registryBusinessContactPeople.person'
            ])
            ->andWhere(['registry_business.id' => $id])
            ->one();
                
        $modelPerson = new Person();
        $modelRegistryBusinessContactPerson = new RegistryBusinessContactPerson();
        $dataRegistryBusinessContactPerson = [];
        
        $isEmpty = false;
        
        if (!empty($post = Yii::$app->request->post())) {
            
            if (!empty($save)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                $flag = true;
                
                $isEmpty = empty($post['Person']) && empty($post['RegistryBusinessContactPerson']);
                
                if (!empty($post['RegistryBusinessContactPersonDeleted'])) {
                    
                    if (($flag = RegistryBusinessContactPerson::deleteAll(['person_id' => $post['RegistryBusinessContactPersonDeleted']]))) {
                        
                        $flag = Person::deleteAll(['id' => $post['RegistryBusinessContactPersonDeleted']]);
                    }
                }
                
                if (!empty($post['Person']) && !empty($post['RegistryBusinessContactPerson'])) {
                    
                    foreach ($post['Person'] as $i => $dataPerson) {
                        
                        if (!empty($post['RegistryBusinessContactPersonExisted'][$i])) {
                            
                            $newModelPerson = Person::findOne(['id' => $post['RegistryBusinessContactPersonExisted'][$i]]);
                        } else {
                            
                            $newModelPerson = new Person();
                        }
                        
                        $newModelPerson->first_name = $dataPerson['first_name'];
                        $newModelPerson->last_name = !empty($dataPerson['last_name']) ? $dataPerson['last_name'] : null;
                        $newModelPerson->phone = !empty($dataPerson['phone']) ? $dataPerson['phone'] : null;
                        $newModelPerson->email = !empty($dataPerson['email']) ? $dataPerson['email'] : null;
                        
                        if (!($flag = $newModelPerson->save())) {
                            
                            break;
                        } else {
                            
                            $newModelRegistryBusinessContactPerson = RegistryBusinessContactPerson::findOne(['person_id' => $newModelPerson->id]);
                            
                            if (empty($newModelRegistryBusinessContactPerson)) {
                                
                                $newModelRegistryBusinessContactPerson = new RegistryBusinessContactPerson();
                                $newModelRegistryBusinessContactPerson->registry_business_id = $model->id;
                                $newModelRegistryBusinessContactPerson->person_id = $newModelPerson->id;
                            }
                            
                            $newModelRegistryBusinessContactPerson->position = $post['RegistryBusinessContactPerson'][$i]['position'];
                            $newModelRegistryBusinessContactPerson->is_primary_contact = !empty($post['RegistryBusinessContactPerson'][$i]['is_primary_contact']) ? true : false;
                            $newModelRegistryBusinessContactPerson->note = !empty($post['RegistryBusinessContactPerson'][$i]['note']) ? $post['RegistryBusinessContactPerson'][$i]['note'] : null;
                            
                            if (!($flag = $newModelRegistryBusinessContactPerson->save())) {
                                
                                break;
                            } else {
                                
                                array_push($dataRegistryBusinessContactPerson, ArrayHelper::merge($newModelRegistryBusinessContactPerson->toArray(), $newModelPerson->toArray()));
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
                    
                    $transaction->commit();
                } else {
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                    
                    $transaction->rollBack();
                }
            }
        }
        
        $dataContactPerson = [];
        
        foreach ($model->registryBusinessContactPeople as $dataRegistryBusinessContactPeople) {
            
            $dataContactPerson[] = ArrayHelper::merge($dataRegistryBusinessContactPeople->toArray(), $dataRegistryBusinessContactPeople->person->toArray());
        }
        
        $dataRegistryBusinessContactPerson = empty($dataRegistryBusinessContactPerson) && !$isEmpty ? $dataContactPerson : $dataRegistryBusinessContactPerson;
        
        return $this->render('update_contact_person', [
            'model' => $model,
            'modelPerson' => $modelPerson,
            'modelRegistryBusinessContactPerson' => $modelRegistryBusinessContactPerson,
            'dataRegistryBusinessContactPerson' => $dataRegistryBusinessContactPerson,
            'id' => $id,
            'appBId' => $appBId,
            'actid' => $actid,
            'logsaid' => $logsaid,
        ]);
    }
    
    public function actionUpdateBusinessHour($id, $save = null, $appBId, $actid, $logsaid)
    {
        $model = RegistryBusiness::find()
            ->joinWith([
                'registryBusinessHours' => function ($query) {
                
                    $query->orderBy(['registry_business_hour.day' => SORT_ASC]);
                },
                'registryBusinessHours.registryBusinessHourAdditionals',
            ])
            ->andWhere(['registry_business.id' => $id])
            ->one();
            
        $modelRegistryBusinessHour = new RegistryBusinessHour();
        $dataRegistryBusinessHour = [];
        
        $modelRegistryBusinessHourAdditional = new RegistryBusinessHourAdditional();
        $dataRegistryBusinessHourAdditional = [];
        
        if (!empty($post = Yii::$app->request->post())) {
            
            if (!empty($save)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                $flag = false;
                
                $loopDays = ['1', '2', '3', '4', '5', '6', '7'];
                
                foreach ($loopDays as $day) {
                    
                    $dayName = 'day' . $day;
                    
                    if (!empty($post['RegistryBusinessHour'][$dayName])) {
                        
                        $newModelRegistryBusinessHourDay = RegistryBusinessHour::findOne(['unique_id' => $model->id . '-' . $day]);
                        
                        if (empty($newModelRegistryBusinessHourDay)) {
                            
                            $newModelRegistryBusinessHourDay = new RegistryBusinessHour();
                            $newModelRegistryBusinessHourDay->registry_business_id = $model->id;
                            $newModelRegistryBusinessHourDay->unique_id = $model->id . '-' . $day;
                            $newModelRegistryBusinessHourDay->day = $day;
                        }
                        
                        $newModelRegistryBusinessHourDay->is_open = !empty($post['RegistryBusinessHour'][$dayName]['is_open']) ? true : false;
                        $newModelRegistryBusinessHourDay->open_at = !empty($post['RegistryBusinessHour'][$dayName]['open_at']) ? $post['RegistryBusinessHour'][$dayName]['open_at'] : null;
                        $newModelRegistryBusinessHourDay->close_at = !empty($post['RegistryBusinessHour'][$dayName]['close_at']) ? $post['RegistryBusinessHour'][$dayName]['close_at'] : null;
                        
                        if (!($flag = $newModelRegistryBusinessHourDay->save())) {
                            
                            break;
                        } else {
                            
                            array_push($dataRegistryBusinessHour, $newModelRegistryBusinessHourDay->toArray());
                        }
                    }
                    
                    if ($flag && !empty($post['RegistryBusinessHourAdditionalDeleted'][$dayName])) {
                        
                        $flag = RegistryBusinessHourAdditional::deleteAll(['id' => $post['RegistryBusinessHourAdditionalDeleted'][$dayName]]);
                    }
                    
                    if ($flag && !empty($post['RegistryBusinessHourAdditional'][$dayName])) {
                        
                        foreach ($post['RegistryBusinessHourAdditional'][$dayName] as $i => $registryBusinessHourAdditional) {
                            
                            if (!empty($registryBusinessHourAdditional['open_at']) || !empty($registryBusinessHourAdditional['close_at'])) {
                                
                                $newModelRegistryBusinessHourAdditional = RegistryBusinessHourAdditional::findOne(['unique_id' => $newModelRegistryBusinessHourDay->id . '-' . $day . '-' . $i]);
                                
                                if (empty($newModelRegistryBusinessHourAdditional)) {
                                    
                                    $newModelRegistryBusinessHourAdditional = new RegistryBusinessHourAdditional();
                                    $newModelRegistryBusinessHourAdditional->unique_id = $newModelRegistryBusinessHourDay->id . '-' . $day . '-' . $i;
                                    $newModelRegistryBusinessHourAdditional->registry_business_hour_id = $newModelRegistryBusinessHourDay->id;
                                    $newModelRegistryBusinessHourAdditional->day = $day;
                                }
                                
                                $newModelRegistryBusinessHourAdditional->is_open = $newModelRegistryBusinessHourDay->is_open;
                                $newModelRegistryBusinessHourAdditional->open_at = !empty($registryBusinessHourAdditional['open_at']) ? $registryBusinessHourAdditional['open_at'] : null;
                                $newModelRegistryBusinessHourAdditional->close_at = !empty($registryBusinessHourAdditional['close_at']) ? $registryBusinessHourAdditional['close_at'] : null;
                                
                                if (!($flag = $newModelRegistryBusinessHourAdditional->save())) {
                                    
                                    break;
                                } else {
                                    
                                    if (empty($dataRegistryBusinessHourAdditional[$dayName])) {
                                        
                                        $dataRegistryBusinessHourAdditional[$dayName] = [];
                                    }
                                    
                                    array_push($dataRegistryBusinessHourAdditional[$dayName], $newModelRegistryBusinessHourAdditional->toArray());
                                }
                            }
                        }
                    }
                }
                
                if ($flag) {
                    
                    $model->note_business_hour = !empty($post['RegistryBusiness']['note_business_hour']) ? $post['RegistryBusiness']['note_business_hour'] : null;
                    
                    $flag = $model->save();
                }
                
                if ($flag) {
                    
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Success'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is success. Data has been saved'));
                    
                    $transaction->commit();
                } else {
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                    Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                    
                    $transaction->rollBack();
                }
            }
        }
        
        $dataRegistryBusinessHour = empty($dataRegistryBusinessHour) ? $model->registryBusinessHours : $dataRegistryBusinessHour;
        
        if (empty($dataRegistryBusinessHourAdditional)) {
            
            foreach ($dataRegistryBusinessHour as $registryBusinessHour) {
                
                $dayName = 'day' . $registryBusinessHour['day'];
                
                $dataRegistryBusinessHourAdditional[$dayName] = [];
                
                if (!empty($registryBusinessHour['registryBusinessHourAdditionals'])) {
                    
                    foreach ($registryBusinessHour['registryBusinessHourAdditionals'] as $registryBusinessHourAdditional) {
                        
                        array_push($dataRegistryBusinessHourAdditional[$dayName], $registryBusinessHourAdditional);
                    }
                }
            }
        }
        
        return $this->render('update_business_hour', [
            'model' => $model,
            'modelRegistryBusinessHour' => $modelRegistryBusinessHour,
            'dataRegistryBusinessHour' => $dataRegistryBusinessHour,
            'modelRegistryBusinessHourAdditional' => $modelRegistryBusinessHourAdditional,
            'dataRegistryBusinessHourAdditional' => $dataRegistryBusinessHourAdditional,
            'id' => $id,
            'appBId' => $appBId,
            'actid' => $actid,
            'logsaid' => $logsaid,
        ]);
    }
    
    public function actionUp($id, $bid, $appBId, $actid, $logsaid)
    {
        $modelRegistryBusinessImage = RegistryBusinessImage::findOne($id);
        
        $modelRegistryBusinessImageTemp = RegistryBusinessImage::find()
            ->andWhere(['registry_business_id' => $modelRegistryBusinessImage->registry_business_id])
            ->andWhere(['order' => $modelRegistryBusinessImage->order - 1])
            ->one();
        
        if ($modelRegistryBusinessImage->order > 1) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            $modelRegistryBusinessImageTemp->order = $modelRegistryBusinessImage->order;
            
            if (($flag = $modelRegistryBusinessImageTemp->save())) {
                
                $modelRegistryBusinessImage->order -= 1;
                
                $flag = $modelRegistryBusinessImage->save();
            }
            
            if ($flag) {
                
                $transaction->commit();
            } else {
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                
                $transaction->rollBack();
            }
        }
        
        return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['approval/registry-business/update-gallery-photo', 'id' => $bid, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]));
    }
    
    public function actionDown($id, $bid, $appBId, $actid, $logsaid)
    {
        $modelRegistryBusinessImage = RegistryBusinessImage::findOne($id);
        
        $modelRegistryBusinessImageTemp = RegistryBusinessImage::find()
            ->andWhere(['registry_business_id' => $modelRegistryBusinessImage->registry_business_id])
            ->andWhere(['order' => $modelRegistryBusinessImage->order + 1])
            ->one();
        
        if ($modelRegistryBusinessImageTemp !== null) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            $modelRegistryBusinessImageTemp->order = $modelRegistryBusinessImage->order;
            
            if (($flag = $modelRegistryBusinessImageTemp->save())) {
                
                $modelRegistryBusinessImage->order += 1;
                
                $flag = $modelRegistryBusinessImage->save();
            }
            
            if ($flag) {
                
                $transaction->commit();
            } else {
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', Yii::t('app', 'Update Data Is Fail'));
                Yii::$app->session->setFlash('message2', Yii::t('app', 'Update data process is fail. Data fail to save'));
                
                $transaction->rollBack();
            }
        }
        
        return AjaxRequest::redirect($this, Yii::$app->urlManager->createUrl(['approval/registry-business/update-gallery-photo', 'id' => $bid, 'appBId' => $appBId, 'actid' => $actid, 'logsaid' => $logsaid]));
    }
}