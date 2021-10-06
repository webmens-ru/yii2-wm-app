<?php

namespace app\controllers;

use Yii;
use \yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AppController extends Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
//        if (in_array($action->id, ['add-portal-auth'])) {
//            return parent::beforeAction($action);
//        }

        $request = Yii::$app->request;
//        $session = Yii::$app->session;

        if ($request->get('DOMAIN') && $request->post('member_id') && $request->post('AUTH_ID') && $request->post('REFRESH_ID')) {
            $component24 = new \wm\b24tools\b24Tools();
            $arAccessParams = $component24->prepareFromRequest(Yii::$app->request->post(), Yii::$app->request->get());
            $errors = $component24->checkB24Auth();
            if ($errors) {
                throw new HttpException(403, 'В доступе отказано');
            }

//            $session->set('accessAllowed', true);
//            $session['AccessParams'] = $arAccessParams;
        }
//        if ($session['AccessParams']) {
//            return parent::beforeAction($action);
//        }

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
        Yii::warning('actionIndex');
        $request = Yii::$app->request;
        $placementOptions = json_decode($request->post('PLACEMENT_OPTIONS'));
        if (isset($placementOptions->routing)) {
             Yii::warning('$placementOptions->routing');
            $this->routing(ArrayHelper::toArray($placementOptions->routing));
        }
        
        return $this->render('index');
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