<?php
/**
 * Auth Generator class file.
 * Behavior that automatically checks if the user has access to the current controller action.
 *
 * @author HuuDoan nguyenhuudoan86@gmail.com
 * @package common.core.rights
 */

namespace common\core\rights;

use yii\web\User as BaseUser;

/**
 * User is the class for the "user" application component that manages the user authentication status.
 *
 * @property User $identity The identity object associated with the currently logged user. Null
 * is returned if the user is not logged in (not authenticated).
 *
 * @author Ricardo Obregón <robregonm@gmail.com>
 */
class User extends BaseUser
{
	public $identityClass = '\common\core\userIdentity\UserIdentity';

	public $enableAutoLogin = true;

	public $loginUrl = ['/home/login'];
        /**
         * reset 
         * @param type $identity
         * @param type $cookieBased
         * @param type $duration
         */
	protected function afterLogin($identity, $cookieBased, $duration)
	{
            parent::afterLogin($identity, $cookieBased, $duration);
	}

	public function getIsSuperAdmin()
	{
		if ($this->isGuest) {
			return false;
		}
                
		return $this->identity->getIsSuperAdmin();
	}

	public function checkAccess($operation, $params = [], $allowCaching = true)
	{
		// Always return true when SuperAdmin user
		if ($this->getIsSuperAdmin()) {
			return true;
		}
                
		return parent::can($operation, $params, $allowCaching);
	}
}