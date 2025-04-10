<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Campaigns');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Campaign'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            // [
            //     'attribute' => 'show_store',
            //     'format' => 'html',
            //     'value' => function($data) { return $data->show_store === 0 ? '<span class="label label-danger">' . Yii::t('app', 'No') . '</span>' : '<span class="label label-success">' . Yii::t('app', 'Yes') . '</span>'; },
            // ],
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($data) { return Html::a($data->name, '@web/' . $data->id, ['data-toggle' => 'tooltip', 'title' => Yii::t('app', 'Show Campaign')]); },
            ],
            'start_date',
            'end_date',
            //'campaign',
            //'image',
            //'logo',
            [
                'attribute' => 'sid',
                'value' => function($data) { 
                    $sids = $data->supplierOptions;
                    return key_exists($data->sid, $sids) ? $sids[$data->sid] : $data->sid;
                },
            ],
            'hits',
            'apply',
            //'show_store',
            //'show_cv',
            //'button_color',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
