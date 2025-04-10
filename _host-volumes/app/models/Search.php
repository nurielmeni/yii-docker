<?php

namespace app\models;

use app\components\niloosoft\niloosoft\api\NlsSearch;
use Yii;
use yii\base\Model;
use app\components\niloosoft\api;
use app\helpers\Helper;
use stdClass;

/**
 * ContactForm is the model behind the contact form.
 */
class Search extends Model
{

    private const LANG_HEB = '1037';
    private const LANG_ENG = '1033';

    private $supplierId;
    private $sellStatus;

    public function __construct($supplierId = null)
    {
        parent::__construct();
        if ($supplierId === null) {
            Yii::error('No supplier id provided for search ', 'Niloos Search');
            die;
        }

        $this->supplierId = $supplierId;
    }

    public function suppliersGetByFilter2()
    {
        $suppliers = [
            ['cardId' => 1, 'entityLocalName' => 'supplier']
        ];

        return $suppliers;
    }



    public function jobsByCategories($categories = [])
    {
        $cacheKey = $this->supplierId . implode('', $categories);

        $search = new NlsSearch();

        return $search->jobHunterQueryUtility();
    }

    public function getJobById($id)
    {
        return [];
    }

    public function jobs($full = false)
    {
        return [];
    }
}
