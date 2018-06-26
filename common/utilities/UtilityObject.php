<?php

/**
 * Some function for object of system
 * some object types
 * + APP_TYPE_USER
 * + APP_TYPE_ORG
 * + APP_TYPE_ADMIN
 *
 * @author phongphamhong
 * @date 11/22/2013
 */

namespace common\utilities;

use common\models\employer\EmployerAssign;
use common\models\employer\EmployerProfile;
use common\models\sysmessages\SysPrivateMailUser;
use common\models\user\UserModel;
use stdClass;

class UtilityObject {

    /**
     * get data of an object
     * 
     * @param type $id primary_key of object
     * @param type $type:
     * + APP_TYPE_CANDIDATE
     * + APP_TYPE_EMPLOYER
     * + APP_TYPE_ADMIN
     * @return array(
     *  'name'=>
     *  'id'=>
     *  'avatar'=>
     *  'object'=>
     * );
     * 
     */
    public static function getObjectInfo($id, $type) {
        /* @var $item SysPrivateMailUser */
        $id = intval($id);
        $type = intval($type);
        $result = self::_objectClass();
        if (!$id || !$type) {
            return $result;
        }
        $object = null;
        if ($type == APP_TYPE_EMPLOYER) {
            $object = EmployerProfile::getInfoByPk($id);
            $object->CHtmlEncodeAttributes();
            $result->name = $object->employer_name;
            $result->id = $id;
            $result->avatar = $object->renderImg();
            $result->object = $object;
            $result->linkview = UtilityUrl::genPartnerProfileUrl($object, array(), true);
        } else if ($type == APP_TYPE_CANDIDATE && $object = \common\models\user\UserSearch::findOne($id)) {
            $object->CHtmlEncodeAttributes();
            $result->name = $object->renderDisplayName();
            $result->id = $id;
            $result->avatar = $object->getAvatar();
            $result->object = $object;
            $result->linkview = UtilityUrl::genUserProfileUrl($object, array(), true);
        } else if ($type == APP_TYPE_SYSTEM){
            $result->name = DEFAULT_TITLE;
            $result->id = null;
            $result->avatar = bu('/images/logo_system.jpg');
            $result->object = null;
            $result->linkview ='javascript:void(0)';
        } else if ($type == APP_TYPE_ADMIN){
            $employer_id = EmployerAssign::getEmployerIdMc(APP_TYPE_CUSTOMERS)->employer_id;
            $object = EmployerProfile::getInfoByPk($employer_id);
            $result->name = DEFAULT_TITLE;
            $result->id = null;
            $result->avatar = $object->renderImg();
            $result->object = null;
            $result->linkview ='javascript:void(0)';
        } else if ($type == APP_TYPE_CUSTOMERS){
            $employer_id = EmployerAssign::getEmployerIdMc(APP_TYPE_CUSTOMERS)->employer_id;
            $object = EmployerProfile::getInfoByPk($employer_id);
            $object->CHtmlEncodeAttributes();
            $result->name = $object->employer_name;
            $result->id = $id;
            $result->avatar = $object->renderImg();
            $result->object = $object;
            $result->linkview = UtilityUrl::genPartnerProfileUrl($object, array(), true);
        }
        return $result;
    }

    /**
     * create an object class
     * @return stdClass
     */
    private static function _objectClass() {
        $std = new stdClass();
        $std->name = null;
        $std->id = null;
        $std->avatar = null;
        $std->object = null;
        $std->linkview = '';
        return $std;
    }

}
