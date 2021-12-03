<?php

namespace app\controllers;

use Yii;
use \yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Bitrix24\User\User as B24User;
use yii\helpers\Url;
use wm\admin\models\User;
use wm\admin\models\B24ConnectSettings;

class AppController extends Controller {
    
    protected $accessToken = null;

    public $layout = '@app/views/layouts/app.php';

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        $request = Yii::$app->request;
        $session = Yii::$app->session;

        if ($request->get('DOMAIN') && $request->post('member_id') && $request->post('AUTH_ID') && $request->post('REFRESH_ID')) {
            $component24 = new \wm\b24tools\b24Tools();
            $arAccessParams = $component24->prepareFromRequest(Yii::$app->request->post(), Yii::$app->request->get());
            $errors = $component24->checkB24Auth();
            if ($errors) {
                throw new HttpException(403, 'В доступе отказано');
            }
            
            $b24App = $component24->connectFromUser($arAccessParams);
            $obB24 = new B24User($b24App);
            $b24User = $obB24->current()['result'];
            Yii::warning($b24User, '$b24User');
            $user = User::findByBitrixId(ArrayHelper::getValue($b24User, 'ID'));
            Yii::warning($user, '$user');
            if (!$user) { 
                Yii::warning('$user1', '$user1'); 
                $userPassword = User::generatePassword();
                $user = new User();
                $user->username = ArrayHelper::getValue($b24User, 'EMAIL');
                $user->b24_user_id = ArrayHelper::getValue($b24User, 'ID');

                $user->name = ArrayHelper::getValue($b24User, 'NAME');
                $user->last_name = ArrayHelper::getValue($b24User, 'LAST_NAME');

                $user->password = \Yii::$app->security->generatePasswordHash($userPassword);
                $user->getAccessToken();
                $user->save();
                Yii::warning($user->errors, '$user->errors1');           
            }else{
                Yii::warning('$user2', '$user2');
                $user->getAccessToken();
                //$user->generateAccessTokenTest();
                $user->save();
                Yii::warning($user->errors, '$user->errors');
            }
            Yii::warning($user->access_token, '$user->access_token');
            $this->accessToken = $user->access_token;
            
            Yii::$app->user->login($user, 3600*24*30);
            
            $session->set('accessAllowed', true);
            $session['AccessParams'] = $arAccessParams;
            
            
        }


        if (null === $request->get('DOMAIN') or null === $request->post('member_id') or null === $request->post('AUTH_ID') or null === $request->post('REFRESH_ID')) {
            throw new HttpException(404, 'Приложение необходимо запустить из портала Битрикс24');
        }
        return parent::beforeAction($action);
    }

    public function actionInstall() {
            $component24 = new \wm\b24tools\b24Tools();
            $request = Yii::$app->request;
            $arAccessParams = $component24->prepareFromRequest(Yii::$app->request->post(), Yii::$app->request->get());
            $result = $component24->addAuthToDB($this->portalTableName, $arAccessParams);
            if ($result == 1) {
                return $this->render('install');
            }
            return 'Ошибка записи';
    }

    public function actionIndex() {
        //Yii::warning('actionIndex', 'action');
        $request = Yii::$app->request;
        $placementOptions = json_decode($request->post('PLACEMENT_OPTIONS'));        
        $placement = $request->post('PLACEMENT');
        
        $params = [
            'placement' => $placement,
            'placementOptions' => $placementOptions,            
        ];
        
        $appUrl = 'https://'.B24ConnectSettings::getParametrByName('b24PortalName').'/marketplace/app/'.B24ConnectSettings::getParametrByName('appId').'/';

        return $this->render('index', ['params' => json_encode($params), 'accessToken' => $this->accessToken, 'appUrl' => $appUrl] );
    }
  
    
    protected function routing($param) {
        $param['type'] = $this->getType(ArrayHelper::getValue($param, 'type'));
        $param['url'] = $this->getUrl(ArrayHelper::getValue($param, 'url'));
        $param['params'] = $this->getUrl(ArrayHelper::getValue($param, 'params'));
        if(!$param['url']){
            return false;
        }
        
        if($param['type'] == 'app'){
            Yii::warning('app');
            if(is_array($param['params'])){
                return $this->redirect(array_merge([$param['url']], $param['params']));
            }else{
                return $this->redirect([$param['url']]);
            }
            Yii::warning(array_merge([$param['url']], $param['params']));                
        }else {
            header('Location: ' . $param['url'] . '?IFRAME=Y&IFRAME_TYPE=SIDE_SLIDER');
            die;

        }
    }
    
    protected function getType($param){
        
        $baseTypies = ['app', 'portal'];
        if(!$param){
            return 'app';
        }
        
        if(in_array($param, $baseTypies)){
            return $param;
        }else{
            return 'app';
        }        
    }
    
    protected function getUrl($param){ 
        if($param){
            return $param;
        }else{
            return false;
        }
    }
    
    public function getPortalTableName(){
        return 'admin_b24portal';
    }
    

}