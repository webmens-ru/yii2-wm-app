<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use wm\admin\models\B24ConnectSettings;
use wm\admin\models\settings\Agents;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{

    /**
     * @return int
     */
    public function actionUpdateConnect()
    {
        $component = new \wm\b24tools\b24Tools();
        $component->connect(
            B24ConnectSettings::getParametrByName('applicationId'),
            B24ConnectSettings::getParametrByName('applicationSecret'),
            B24ConnectSettings::getParametrByName('b24PortalTable'),
            B24ConnectSettings::getParametrByName('b24PortalName')
        );
        return ExitCode::OK;
    }

    public function actionAgentsRun()
    {
        Yii::warning('actionAgentsRun');
        Agents::shedulRun();
        return ExitCode::OK;
    }
}
