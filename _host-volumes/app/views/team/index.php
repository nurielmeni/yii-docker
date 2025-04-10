<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Team'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'title',
            'body:ntext',
            [
                'attribute' => 'image',
                'format' => 'html',
                //'label' => 'ImageColumnLable',
                'value' => function ($data) {
                    return Html::img('@web/' . $data['image'],
                        [
                            'width' => '50px', 
                            'height' => '50px', 
                            'alt' => 'staff image',
                            'class' => 'circled-image'
                        ]);
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
