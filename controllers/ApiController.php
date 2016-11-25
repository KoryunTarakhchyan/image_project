<?php

namespace app\controllers;

use app\models\Image;
use app\models\Users;
use Yii;
use yii\helpers\Url;

class ApiController extends \yii\web\Controller
{


    public function actionAuth()
    {

        $model = New Users();
        foreach (getallheaders() as $name => $value) {
            if ($name == 'device_id') {
                $user = $model->findOne(['device_id' => $value]);
                $jwt = $model->getJWT();
                If (!$user) {
                    $user = New Users();
                    $user->device_id = $value;
                }
                $user->token = $jwt;
                $user->save();

                return json_encode($user->token);
            }
        }

        If (Yii::$app->request->get()) {

            $getter = Yii::$app->request->get();
            $user = $model->findOne(['device_id' => $getter['deviceId']]);
            $jwt = $model->getJWT();
            If (!$user) {
                $user = New Users();
                $user->device_id = $getter['deviceId'];
            }
            $user->token = $jwt;
            $user->save();

            return json_encode($user->token);

        }

        return json_encode('EROOOOOR');
    }

    public function actionImages()
    {
        $users = New Users();
        foreach (getallheaders() as $name => $value) {

            if ($name == 'token') {
                $users->findIdentityByAccessToken($value);
                if (!$users) {
                    return false;
                }

                $result = [];
                $url = Url::to('@web/images/', true);
                $model = New Image();
                $data = $model->find()->asArray()->all();

                foreach ($data as $item) {
                    $result [] = $url . $item['title'];
                }
                return json_encode($result, JSON_UNESCAPED_SLASHES);

            }
        }


    }

    public function actionImagessync()
    {
//        $headerImgids = [1,3,8,11,12];
        $users = New Users();
        foreach (getallheaders() as $name => $value) {
            if ($name == 'token') {
                $users->findIdentityByAccessToken($value);
                if (!$users) {
                    return false;
                }
            } else if ($name == 'imageIds') {
                $headerImgids =  explode(',',$value);

                $result = [];
                $deletion = [];
                $data =[];
                $url = Url::to('@web/images/', true);
                $model = New Image();

                if (!empty($headerImgids)) {
                    $del = [];

                    $seldel = $model->find()->select(['id'])->where(['id' => $headerImgids])->asArray()->all();
                    foreach ($seldel as $del_item) {
                        $del [] = $del_item['id'];
                    }
                    $deletion = array_values(array_diff($headerImgids, $del));
                }
                $images = $model->find()->where(['not in', 'id', $headerImgids])->asArray()->all();
                foreach ($images as $item) {
                    $data [] = [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'url' => $url . $item['title']
                    ];
                }
                $result = [
                    'deletion'=>$deletion,
                    'images'=>$data
                ];

                return json_encode($result, JSON_UNESCAPED_SLASHES);
            }
        }
    }

}
