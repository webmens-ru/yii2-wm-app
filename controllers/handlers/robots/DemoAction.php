<?php

namespace app\modules\baseapp\controllers\handlers\robots;

use yii\base\Action;
use yii;

class DemoAction extends Action {

    public function run() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $properties = $request->post('properties');
        $component = new \app\components\b24Tools();
        $b24App = $component->connectFromUser($auth);
        $obB24 = new \Bitrix24\App\App($b24App);
        $b24 = $obB24->info();
        return $b24;
    }

}
