<?php

namespace app\modules\baseapp\controllers\handlers\robots;

use yii\base\Action;
use yii;
use app\modules\baseapp\models\B24ConnectSettings;

class DemoAction extends Action {

    public function run() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $properties = $request->post('properties');
        $component = new \app\components\b24Tools();
        $b24App = $component->connect(
                B24ConnectSettings::getParametrByName('applicationId'),
                B24ConnectSettings::getParametrByName('applicationSecret'),
                null,
                B24ConnectSettings::getParametrByName('b24PortalName'),
                null,
                $auth);
        $obB24 = new \Bitrix24\App\App($b24App);
        $b24 = $obB24->info();
        return $b24;
    }

}
