<?php

namespace app\components\niloosoft\niloosoft\api;

use app\components\niloosoft\Niloosoft\NlsOptions;
use yii;

/**
 * Description of NlsService
 * @author nurielmeni
 */
if (!class_exists('NlsService')) {

    class NlsService
    {
        /**
         * @var number the language code (he: 1033, en: 1037);
         */
        protected $langCode;

        protected $cacheTime;

        /**
         * @var array the errors
         */
        protected $errors = [];

        protected $options;

        protected $cache;

        protected $fetcher;

        /**
         * @param array $config the $auth and $settings
         */
        public function __construct($config = [])
        {
            $this->options = NlsOptions::loadFromFile(Yii::$app->params['configPath'] ?? null);

            $this->langCode = $config['langCode'] ?? NlsHelper::languageCode('he_IL');

            $this->cache = Yii::$app->cache;

            $this->cacheTime = $config['cacheTime'] ?? $this->options->getCacheTimeSec();

            $this->fetcher = new NlsFetcher($this->options->getRestApiUrl());
        }

        protected function isCache()
        {
            return $this->cacheTime && $this->cacheTime > 0;
        }

        protected function jobFilterWhere($filters, $condition)
        {
            $filterWhere = new \stdClass();
            $filterWhere->Filters = $filters;
            $filterWhere->Condition = $condition;
            return $filterWhere;
        }

        protected function jobFilterField($field, $searchPhrase, $value)
        {
            $filterField = new \stdClass();
            $filterField->Field = $field;
            $filterField->SearchPhrase = $searchPhrase;
            $filterField->Value = $value;
            return $filterField;
        }

        protected function jobFilterEntry(&$filter, $entity, $filterKeyword, $phrase = "Exact")
        {
            if (!empty($entity)) {

                if (is_array($entity)) {
                    $jobFilterArray = [];
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, $phrase, $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray, "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(
                        [$this->jobFilterField($filterKeyword, $phrase, $entity)]
                        ,
                        "AND"
                    );
                }
            }
        }

        protected function jobFilterSort($field, $direction)
        {
            $filterSort = new \stdClass();
            $filterSort->Field = $field;
            $filterSort->Direction = $direction;
            return $filterSort;
        }

        /**
         * @return filter
         */
        /**
         * Create filter
         * @param string view string, from which view
         * @param array params array of filter options
         * Fields:
         *   $keywords = "",
         *   $categoryIds = [], // professionalFields
         *   $expertise = [],
         *   $regionIds = [],
         *   $employmentTypes = [],
         *   $jobscops = [],
         *   $jobLocations = [], // areas
         *   $employerIds = [],
         *   $updateDate = "",
         *   $suplierId = "",
         *   $lastId = 0,
         *   $countPerPage = NlsHunter_modules::NLS_SEARCH_COUNT_PER_PAGE,
         */
        public function createFilter($view, $params = [])
        {
            if (empty($view) || count($params) === 0)
                return [];

            $filter = new \stdClass();
            $filter->FromView = $view;

            $filter->SelectFilterFields = [
                //  "CategoryId",
                "JobId",
                "JobTitle",
                "JobCode",
                "RegionValue",
                "RegionText",
                "UpdateDate",
                "ExpertiseId",
                "EmploymentType",
                "EmployerId",
                "EmployerName",
                "JobScope",
                "Rank",
                "CityId",
                "Description",
            ];

            $filter->NumberOfRows = key_exists('countPerPage', $params) ? $params['countPerPage'] : null;
            $filter->OffsetIndex = key_exists('lastId', $params) ? $params['lastId'] : null;
            $filter->WhereFilters = [];
            $filter->OrderByFilterSort = array(
                $this->jobFilterSort("UpdateDate", "Descending"),
                $this->jobFilterSort("JobCode", "Ascending"),
            );

            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'keywords':
                        if (!empty($value)) {
                            $keywords = explode(',', $value);
                            foreach ($keywords as $key => $keyword) {
                                $keywordsFilter[] = $this->jobFilterField("Description", "Like", $keyword);
                                $keywordsFilter[] = $this->jobFilterField("Requiremets", "Like", $keyword);
                                $keywordsFilter[] = $this->jobFilterField("JobTitle", "Like", $keyword);
                                $keywordsFilter[] = $this->jobFilterField("Skills", "Like", $keyword);
                                $keywordsFilter[] = $this->jobFilterField("JobCode", "Exact", $keyword);
                                if (is_numeric($keyword)) {
                                    $keywordsFilter[] = $this->jobFilterField("JobId", "Exact", $keyword);
                                }

                                $filter->WhereFilters[] = $this->jobFilterWhere($keywordsFilter, "OR");
                            }
                        }
                        break;
                    case 'categoryId':
                    case 'expertise':
                    case 'regionValue':
                    case 'employmentType':
                    case 'jobScope':
                    case 'jobLocation':
                    case 'employerId':
                    case 'supplierId':
                    case 'status':
                        if (!empty($value)) {
                            $this->jobFilterEntry($filter, $value, ucfirst($key));
                        }
                        break;
                    case 'updateDate':
                        if (!empty($value)) {
                            $fromDate = \DateTime::createFromFormat('j/m/Y', $value);
                            if ($fromDate) {
                                $dateDateStr = $fromDate->format('m/j/Y H:i') . '-' . date('m/j/Y H:i');
                                $filter->WhereFilters[] = $this->jobFilterWhere([$this->jobFilterField("UpdateDate", "BetweenDates", $dateDateStr)], "AND");
                            }
                        }
                        break;
                    default:
                        break;
                }
            }

            return $filter;
        }
    }
}
