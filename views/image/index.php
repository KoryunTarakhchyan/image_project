<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

//print_r(  Yii::$app->homeUrl );
//echo Url::base(true);

//echo Yii::getAlias('@web');

$url =  Url::to('@web/images/', true);



//print_r(  Url::canonical() );


// Yii::$app->basePath;
?>
<h1>Images</h1>

<div>
    <?php foreach( $dataProvider as $images ) {
        echo $url . $images['title'] . '<br>';
    } ?>
</div>
