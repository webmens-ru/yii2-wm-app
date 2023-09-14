<?php

namespace app\controllers\handlers\robots;

use yii\base\Action;
use Yii;
use wm\b24tools\b24Tools;

class BaseRobotAction extends Action
{
    public function run()
    {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $event_token = $request->post('event_token');
        $properties = $request->post('properties');
        $component = new b24Tools();
        $b24App = $component->connectFromUser($auth);
        $returnValues = $this->logicRobot($properties, $b24App);
        $obB24 = new \Bitrix24\Bizproc\Event($b24App);
        $obB24->send($event_token, $returnValues);
        return '';
    }

    protected function logicRobot($properties, $b24App)
    {
        return [];
    }
}
