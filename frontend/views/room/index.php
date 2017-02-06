<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rooms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Room', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'label' => 'Access Level',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->access_level == 0 ? 'Public' : 'Private';
                }
            ],
            [
                'label' => 'Access URL',
                'format' => 'raw',
                'value' => function($data) {

                    return strlen($data->token) > 10 ? Url::to(['room/open/' . $data->id, 'token' => $data->token], true) : '';
                }
            ],
            [
                'label' => 'Image',
                'format' => 'raw',
                'value' => function($data) {
                    /** @var common\models\Room $data */
                    $imagePath = $data->getImagePath();

                    return HTML::img($imagePath);
                }
            ],
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($data) {
                    return HTML::a('Join', ['room/open/' . $data->id], ['class' => 'btn btn-primary']);
                }
            ]
        ],
    ]); ?>
</div>
