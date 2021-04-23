<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\services_groups;
use app\models\services;

class ServicesController extends Controller
{
    public function actionIndex()
    {
        $services_groups = services_groups::find()->all();
        return $this->render('index', compact("services_groups"));
    
        $services = services::find()->all();
        return $this->render('index', compact("services"));
}

public function actionQuest1()
    {
        $services_groups = services_groups::find()->all();
        return $this->renderPartial('quest1', compact("services_groups"));
    
        $services = services::find()->all();
        return $this->renderPartial('quest1', compact("services"));
}


}



/*public function actionGrid(){

    return $this -> render('index') ;
        
    }

?>*/