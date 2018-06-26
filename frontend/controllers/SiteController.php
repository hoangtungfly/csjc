<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\CategoriesEnum;
use common\core\enums\CategoryEnum;
use common\core\enums\EmailsettingEnum;
use common\core\enums\NewsEnum;
use common\core\enums\product\ProductEnum;
use common\core\enums\SystemTokenEnum;
use common\models\admin\SettingsMessageSearch;
use common\models\category\CategoriesSearch;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;
use common\models\ResetPassword;
use common\models\settings\ErrorLog;
use common\models\settings\SystemSettingSearch;
use common\models\system\SystemTokenSearch;
use common\models\user\LoginForm;
use common\models\user\UserSearch;
use common\utilities\UltilityEmail;
use common\utilities\UtilityUrl;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends GlobalController {

    /**
     * @inheritdoc
     */
    public $layout = '@webmain/views/layouts/main';

    public function actions() {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => null,
                'maxLength' => 6,
            ],
        ];
    }

    public function actionError() {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $code = $exception->getCode();
            if (property_exists($exception, 'statusCode')) {
                $code = $exception->statusCode;
            }
            if ($code) {
                $content_message = '';
                $messages = '';
                if ($code != 404) {
                    $link = UtilityUrl::realURL();
                    $flagError = true;
                    $content_message = $exception->__toString();
                    preg_match("/with message '([^']*)'/", $content_message, $arrayMessages);
                    if (isset($arrayMessages[1])) {
                        $messages = $arrayMessages[1];
                        $arrayLineMessage = explode("\n", $content_message);
                        $error_line = $arrayLineMessage[0];
                        $modelError = ErrorLog::find()->where('error_line = :error_line AND message = :message AND link = :link AND created_time > :created_time', [
                                    ':error_line' => $error_line,
                                    ':message' => $messages,
                                    ':link' => $link,
                                    ':created_time' => time() - 300,
                                ])->one();
                        if (!$modelError) {
                            $modelError = new ErrorLog;
                            $modelError->content = $content_message;
//                            $modelError->content = Yii::$app->errorHandler->renderFile(app()->errorHandler->exceptionView, ['exception' => $exception,]);
                            $modelError->message = $messages;
                            $modelError->error_line = $error_line;
                            $modelError->link = $link;
                            $modelError->code = $code;
                            $modelError->error_ip = r()->getUserIP();
                            $modelError->device = UtilityUrl::isMobile() ? 'moble' : 'desktop';
                            $modelError->save(false);
                            $attributes = $modelError->attributes;
                            $sendMail = new UltilityEmail();
                            $sendMail->getTemplateText(EmailsettingEnum::ERROR_REPORT_SENDMAIL, $attributes, $attributes);
                            $sendMail->mailer->setCc(app()->params['error_report']['cc']);
//                            $sendMail->send(app()->params['error_report']['mail']);
                        }
                    }
                } else {
                    $content_message = $this->_getStatusCodeMessage($code);
                    $messages = 'Page not found';
                }
                return $this->ARender('error', [
                            'code' => $code,
                            'name' => "'" . $messages . "' code = " . $code,
                            'messages' => $content_message,
                ]);
            }
        }
    }

    public function actionSitemap() {
        $listCategoryNews = CategoriesSearch::getAllCategoryByType(0, CategoryEnum::CATEGORY_TYPE_NEWS);
        $listCategoryProduct = CategoriesSearch::getAllCategoryByType(0, CategoryEnum::CATEGORY_TYPE_PRODUCT);
        $listNews = NewsSearch::getArrayByObject(NewsSearch::find()->select(NewsEnum::SELECT)->asArray()->where('status = 1')->orderBy('id desc')->all());
        $listProduct = ProductSearch::getArrayByObject(ProductSearch::find()->select(ProductEnum::SELECT)->asArray()->where('status = 1')->orderBy('id desc')->all());
        $html = $this->renderPartial('xml', array(
            'listCategoryNews' => $listCategoryNews,
            'listNews' => $listNews,
            'listCategoryProduct' => $listCategoryProduct,
            'listProduct' => $listProduct,
            'limitNews' => PAGESIZE,
        ));
        $link = APPLICATION_PATH . '/sitemap.xml';
        file_put_contents($link, '<?xml version="1.0" encoding="UTF-8"?>' . $html);
        echo $html;
    }

    public function actionRss() {
        $alias = $this->getParam('alias');
        $category = CategoriesSearch::findOne(['alias' => $alias]);
        if ($category) {
            $category = CategoriesSearch::getObject($category);
            if ($category['type'] == CategoriesEnum::CATEGORY_TYPE_NEWS) {
                $listNews = NewsSearch::getListByCategoryid($category['id'], $category['level']);
                $model = new NewsSearch();
                if (count($listNews)) {
                    foreach ($listNews as $key => $item) {
                        $listNews[$key]['image'] = $model->getimage([100, 0], $item['image']);
                    }
                }
            } else {
                $listNews = ProductSearch::ProductCategory($category['id'], 100, 0, [100, 0]);
            }

            $system_setting = new SystemSettingSearch();
            $content = $this->renderPartial('rss', [
                'category' => $category,
                'listNews' => $listNews,
                'logo' => $system_setting->getimage([], SystemSettingSearch::getValue('logo')),
                'link_feed' => $this->createAbsoluteUrl(DS . WEBNAME . '/main/feeds'),
                'link_rss' => UtilityUrl::realURL(),
            ]);
            header('Content-Type: application/xml');
            echo '<?xml version="1.0" encoding="utf-8"?>' . "\n" . '<?xml-stylesheet type="text/xsl" href="/rss.xsl" media="screen"?>' . "\n" . $content;
            die();
        }
    }

    public function actionLogout() {
        user()->logout();
        $urlb = $this->getParam('urlb');
        if ($urlb) {
            $urlb = base64decodeUrl($urlb);
            return $this->redirect($urlb);
        }
        return $this->redirect($this->createUrl('/'));
    }

    public function actionLogin() {
        $urlb = $this->getParam('urlb');
        $urlb = $urlb != "" ? base64decodeUrl($urlb) : '/';
        if (!user()->isGuest) {
            return $this->redirect($urlb);
        }
        $model = new LoginForm();
        if (r()->isPost) {
            $post_data = r()->post();
            $model->load($post_data);
            if(isset($post_data['LoginForm']['rememberMe'])) {
                $model->rememberMe = $post_data['LoginForm']['rememberMe'];
            }
            if(isset($post_data['rememberMe'])) {
                $model->rememberMe = $post_data['rememberMe'];
            }
            $model->trimAttributes();
            if (r()->isAjax) {
                app()->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->login()) {
                return $this->redirect($urlb);
            }
        }
        return $this->redirect($urlb);
    }
    
    public function actionForgotpassword() {
        $urlb = $this->getParam('urlb');
        $urlb = $urlb != "" ? base64decodeUrl($urlb) : '/';
        if (!user()->isGuest) {
            return $this->redirect($urlb);
        }
        $model = new ResetPassword();
        $model->setScenario('forgot');
        if (r()->isAjax) {
            $post_data = r()->post();
            $model->load($post_data);
            if($model->validate()) {
                if($model->createTokenToResetPassword()) {
                    $this->jsonencode([
                        'code'      => 200,
                        'title'     => SettingsMessageSearch::t('forgotpassword','message_success_title','Send the password reset instruction'),
                        'message'   => SettingsMessageSearch::t('forgotpassword','message_success_description','An email is already sent to your mailbox. Please check and follow the instruction to change your password.'),
                    ]);
                } else {
                    $this->jsonencode([
                        'code'      => 200,
                        'title'     => 'Send the password reset false',
                        'message'   => 'Error bug',
                    ]);
                }
            } else {
                app()->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->redirect($urlb);
    }
    
    public function actionResetpassword() {
        if (!user()->isGuest) {
            return $this->redirect('/');
        }
        
        $token = trim($this->getParam('token'));
        if ($token && ($tokenModel = SystemTokenSearch::findOne($token)) && $tokenModel->object_type == SystemTokenEnum::TOKEN_RESET_PW_USER) {
            if(r()->isAjax) {
                $model = new ResetPassword();
                $model->setScenario('reset');
                $post_data = r()->post();
                $model->load($post_data);
                if($model->validate()) {
                    $user = UserSearch::findOne($tokenModel->object_id);
                    if ($user && r()->isAjax && r()->isPost) {
                        $user->password = $model->password;
                        $user->save(false);
                        $tokenModel->delete();
                        $this->jsonencode([
                            'code'      => 200,
                            'title'     => SettingsMessageSearch::t('resetpassword','message_success_title','Change password'),
                            'message'   => SettingsMessageSearch::t('resetpassword','message_success_description','Change password successfully'),
                        ]);
                    } else {
                        $this->jsonencode([
                            'code'      => 200,
                            'title'     => SettingsMessageSearch::t('resetpassword','message_error_title','Send the password reset false'),
                            'message'   => SettingsMessageSearch::t('resetpassword','message_error_description','Error bug'),
                        ]);
                    }
                } else {
                    app()->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
            }
        } else {
            return $this->goHome();
        }
    }
   
    public function actionPrint() {
        $body = $this->getParam('body');
        return $this->render('print',[
            'body'      => $body,
        ]);
    }

}