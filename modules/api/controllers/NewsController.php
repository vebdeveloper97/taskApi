<?php


namespace app\modules\api\controllers;


use app\modules\api\resources\NewsResources;

class NewsController extends BaseApiController
{
    public $modelClass = NewsResources::class;


}