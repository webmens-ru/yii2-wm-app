<?php
namespace app\controllers\handlers\robots;

use Bitrix24\B24Object;
use yii\helpers\ArrayHelper;

class DemoAction extends BaseRobotAction
{
    protected function logicRobot($properties, $b24App)
    {
        $myProperty = ArrayHelper::getValue($properties, 'myProperty');

        $obB24 = new B24Object($b24App);
        $request = $obB24->client->call('method', ['ID' => $myProperty]
        );

        $ids = ArrayHelper::getValue($request, 'result');

        return [
            'ids' => $ids,
            'maxId' => $ids ? max($ids) : null,
            'minId' => $ids ? min($ids) : null,
            'count' => count($ids)
        ];
    }
}

