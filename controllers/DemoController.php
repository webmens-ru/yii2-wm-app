<?php

namespace app\controllers;

use wm\admin\controllers\ActiveRestController;
use app\models\Demo;
use app\models\DemoSearch;

class DemoController extends ActiveRestController {

    public $modelClass = Demo::class;
    public $modelClassSearch = DemoSearch::class;
}
