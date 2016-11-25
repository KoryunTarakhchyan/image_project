<?php

namespace app\controllers;

use app\models\Image;

class ImageController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = New Image();

        $dataProvider = $model ->find()->asArray()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
