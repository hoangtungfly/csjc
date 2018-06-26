<?php

namespace backend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\EmailsettingEnum;
use common\models\settings\ErrorLog;
use common\models\user\LoginFormAdmin;
use common\utilities\UltilityEmail;
use common\utilities\UtilityUrl;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
//class SiteController extends MemberBaseController
class SiteController extends GlobalController {
    /**
     * @inheritdoc
     */
    public $menu_admin;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogin() {
        $this->layout = 'main_login';
        $urlb = $this->getParam('urlb');
        if (!user()->isGuest && user()->identity->app_type == APP_TYPE_ADMIN) {
            return $this->goHome();
        }

        $model = new LoginFormAdmin();
        if (r()->isPost) {
            $post_data = Yii::$app->request->post();
            $model->load($post_data);
            $model->trimAttributes();
            if (r()->isAjax) {
                app()->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->loginAdmin()) {
                if($urlb != "") {
                    $urlb = base64_decode($urlb);
                    return $this->redirect($urlb);
                }
                return $this->goHome();
            }
        }

        return $this->render('login', [
                    'model' => $model,
                    'urlb'  => $urlb,
        ]);
    }

    public function actionLogout() {
        user()->logout();
        if(isset($_SERVER['HTTP_REFERER'])) {
            $urlb = $_SERVER['HTTP_REFERER'];
            if(str_replace(HOST_BACKEND, "", $urlb) != $urlb) {
                $urlb = base64_encode($urlb);
                return $this->redirect($this->createUrl('/site/login',['urlb' => $urlb]));
            } else {
                return $this->redirect($this->createUrl('/site/login'));
            }
        } else {
            return $this->redirect($this->createUrl('/site/login'));
        }
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
                return $this->PRender('error',[
                    'code' => $code,
                    'name' => "'" . $messages."' code = " . $code,
                    'messages'  => $content_message,
                ]);
            }
        }
    }
}
