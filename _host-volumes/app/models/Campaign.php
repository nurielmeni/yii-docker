<?php

namespace app\models;

use app\models\Search;

use Yii;

/**
 * This is the model class for table "campaign".
 *
 * @property int $id
 * @property int $fbf
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $campaign
 * @property string $image
 * @property string $mobile_image
 * @property string $logo
 * @property string $sid
 * @property int $show_store
 * @property int $show_cv
 * @property string $button_color
 * @property string $contact
 * @property string $tag_header
 * @property string $tag_body
 * @property int $hits
 * @property int $apply
 * @property string $youtube_video_id
 */
class Campaign extends \yii\db\ActiveRecord
{
    public $start_date_int;
    public $end_date_int;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'youtube_video_id'], 'required'],
            ['start_date', 'date', 'format' => 'php:d/m/Y', 'timestampAttribute' => 'start_date'],
            ['start_date', 'default', 'value' => date('d/m/Y', time())],
            ['end_date', 'date', 'format' => 'php:d/m/Y', 'timestampAttribute' => 'end_date'],
            ['end_date', 'default', 'value' => null],
            [['show_store', 'show_cv', 'fbf'], 'integer'],
            [['name', 'sid'], 'string', 'max' => 64],
            [['contact'], 'string', 'max' => 128],
            [['tag_header', 'tag_body'], 'string', 'max' => 2048],
            [['campaign', 'youtube_video_id', 'mobile_image', 'image', 'logo'], 'string', 'max' => 1024],
            [['button_color'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fbf' => Yii::t('app', 'Friend Brings Friend'),
            'name' => Yii::t('app', 'Name'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'campaign' => Yii::t('app', 'Campaign'),
            'image' => Yii::t('app', 'Image'),
            'mobile_image' => Yii::t('app', 'Mobile Image'),
            'logo' => Yii::t('app', 'Logo'),
            'sid' => Yii::t('app', 'Sid'),
            'show_store' => Yii::t('app', 'Show Store'),
            'show_cv' => Yii::t('app', 'Show Cv'),
            'button_color' => Yii::t('app', 'Button Color'),
            'contact' => Yii::t('app', 'Contact Text'),
            'tag_header' => Yii::t('app', 'Header Tag Manager'),
            'tag_body' => Yii::t('app', 'Body Tag Manager'),
            'hits' => Yii::t('app', 'Hits'),
            'apply' => Yii::t('app', 'Apply'),
            'youtube_video_id' => Yii::t('app', 'Youtube Video Id'),
        ];
    }

    public function getSupplierOptions()
    {
        return Yii::$app->cache->getOrSet('suppliers_options', function () {
            $options = [];
            $search = new Search(Yii::$app->params['supplierId']);
            $suppliersResult = $search->suppliersGetByFilter2();
            foreach ($suppliersResult as $supplier) {
                if (key_exists('CardId', $supplier) && key_exists('EntityLocalName', $supplier)) {
                    $options[$supplier['CardId']] = $supplier['EntityLocalName'];
                }
            }
            return $options;
        }, 60 * 10);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->start_date_int = $this->start_date;
        $this->end_date_int = $this->end_date;
        $this->start_date = empty($this->start_date) ? $this->start_date : date('d/m/Y', $this->start_date);
        $this->end_date = empty($this->end_date) ? $this->end_date : date('d/m/Y', $this->end_date);
    }
}
