<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Campaign */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campaings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'start_date',
            'end_date',
            'campaign:html',
            [
                'attribute' => 'image',
                'value' => '@web/' . $model->image,
                'format' => ['image',['width'=>'80','height'=>'60']],
            ],
            [
                'attribute' => 'logo',
                'value' => '@web/' . $model->logo,
                'format' => ['image',['width'=>'80','height'=>'60']],
            ],
            'sid',
            [
                'attribute' => 'show_store',
                'value' => function($data) { return $data->show_store ? Yii::t('app', 'Yes') : Yii::t('app', 'No'); },
            ],
            [
                'attribute' => 'show_cv',
                'value' => function($data) { return $data->show_cv ? Yii::t('app', 'Yes') : Yii::t('app', 'No'); },
            ],
            [
                'attribute' => 'button_color',
                'format' => 'html',
                'value' => function($data) {return Html::tag('span', $data->button_color, ['class' => 'badge', 'style' => 'background-color:' . $data->button_color]);},
                
                
            ],
            'contact'
            
        ],
    ]) ?>

</div>
