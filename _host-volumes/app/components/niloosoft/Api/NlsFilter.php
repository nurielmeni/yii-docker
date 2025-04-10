<?php

namespace app\components\niloosoft\niloosoft\api;

use app\components\niloosoft\Niloosoft\Admin\AdminOptions;

abstract class WhereCondition
{
  const C_OR = 'OR';
  const C_AND = 'AND';
}
abstract class SearchPhrase
{
  const ALL = 'All';
  const EXACT = 'Exact';
  const ONE_OR_MORE = 'OneOrMore';
  const BETWEEN_DATES = 'BetweenDates';
  const LIKE = 'Like';
}

abstract class SortDirection
{
  const ASC = 0;
  const DESC = 1;
}

class FilterField
{
  public $SearchPhrase;
  public $IncludeEmptyValues;
  public $Value;
  public $Field;
  public $FieldFilterType;
  public $NestedFields;

  public function __construct($field, $searchPhrase, $value, $fieldType = "")
  {
    $this->SearchPhrase = $searchPhrase;
    $this->IncludeEmptyValues = false;
    $this->FieldFilterType = $fieldType;
    $this->Value = is_array($value) ? implode(',', $value) : $value;
    $this->Field = $field;
  }

  public function setNested($nestedFiled)
  {
    $this->FieldFilterType = 'Nested';
    $this->NestedFields[] = $nestedFiled;
  }
}

class WhereFilter
{
  /**
   * One FilterField
   */
  public $Filters;

  /**
   * One of the Enum class Condition
   */
  public $Condition;

  public function __construct($filters, $condition)
  {
    $this->Filters = is_array($filters) ? $filters : [$filters];
    $this->Condition = $condition;
  }
}

/**
 * Description of NlsFilter
 *
 * @author nurielmeni
 */
class NlsFilter
{
  const NUMERIC_VALUES = 'NumericValues';
  const TEXTUAL_SEARCH = "TextualSearch";
  const TERMS_NON_ANALAYZED = "TermsNonAnalyzed";
  const DATE_TIME_RANGE = "DateTimeRange";
  const NESTED = "Nested";

  public $GeoSortDescriptor = null;
  public $FromView;
  public $LanguageId;
  public $SelectFilterFields = [];
  public $OrderByFilterSort;
  public $WhereFilters = [];

  public function __construct($view = 'Jobs')
  {
    $this->FromView = $view;
    $this->LanguageId = NlsHelper::languageCode();
  }

  /**
   * Sets the Supplier Id for the filter
   * @supplierId - The supplier Id for the search
   */
  public function addSuplierIdFilter($supplierId)
  {
    $sidParentFilterField = new FilterField('PublishedJobSupplier', SearchPhrase::ALL, $supplierId, self::NESTED);
    $sidNestedFilterField = new FilterField('PublishedJobSupplier_SupplierId', SearchPhrase::ALL, $supplierId, self::TERMS_NON_ANALAYZED);
    $sidParentFilterField->setNested($sidNestedFilterField);

    $this->addWhereFilter($sidParentFilterField, WhereCondition::C_AND);
  }

  /**
   * Add select fields for the filter
   * @fields Array || String, names of the select fields
   */
  public function addSelectFilterFields($fields)
  {
    $fieldsArray = !is_array($fields) ? [$fields] : $fields;
    $this->SelectFilterFields = array_merge($this->SelectFilterFields, $fieldsArray);
  }

  /**
   * Add sorting
   */
  public function addSort($field, $direction = SortDirection::ASC)
  {
    $this->OrderByFilterSort[] = [
      'Direction' => $direction,
      'Field' => $field
    ];
  }

  /**
   * @filter - the wher filter to add with the specific condition
   * @condition - Condition Class options
   */
  public function addWhereFilter($filters, $condition)
  {
    $whereFilter = new WhereFilter($filters, $condition);
    $this->WhereFilters[] = $whereFilter;
  }

  /**
   * Create Filter by search params
   */
  public function createFilter($searchParams)
  {
    if (!is_array($searchParams))
      return [];

    $supplierId = key_exists('supplier-id', $searchParams) ? $searchParams['supplier-id'] : get_option(AdminOptions::SUPPLIER_ID);
    if (!$supplierId) {
      throw new \InvalidArgumentException('Invalid or missing Supplier ID');
    }

    $this->addSuplierIdFilter($supplierId);

    if (key_exists('unit-division', $searchParams)) {
      // 92 User Defined: Unit Division
      $filterField = new FilterField(92, SearchPhrase::EXACT, $searchParams['unit-division'], NlsFilter::NUMERIC_VALUES);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('professional-fields', $searchParams)) {
      $filterField = new FilterField('JobProfessionalFields', SearchPhrase::EXACT, $searchParams['professional-fields'], NlsFilter::NESTED);
      $nestedFilterField = new FilterField('JobProfessionalFieldInfo_CategoryId', SearchPhrase::ALL, $searchParams['professional-fields'], NlsFilter::NUMERIC_VALUES);
      $filterField->setNested($nestedFilterField);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('professional-categories', $searchParams)) {
      $filterField = new FilterField('JobProfessionalFields', SearchPhrase::EXACT, $searchParams['professional-categories'], NlsFilter::NESTED);
      $nestedFilterField = new FilterField('JobProfessionalFieldInfo_CategoryId', SearchPhrase::ALL, $searchParams['professional-categories'], NlsFilter::NUMERIC_VALUES);
      $filterField->setNested($nestedFilterField);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('job-scope', $searchParams)) {
      $filterField = [];
      foreach ($searchParams['job-scope'] as $value) {
        $filterFieldOption = new FilterField('JobScope', SearchPhrase::EXACT, $value, NlsFilter::NUMERIC_VALUES);
        $filterField[] = $filterFieldOption;
      }
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('job-rank', $searchParams)) {
      $filterField = new FilterField('Rank', SearchPhrase::EXACT, $searchParams['job-rank'], NlsFilter::NUMERIC_VALUES);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('date-range', $searchParams)) {
      // date("m/d/Y", $start) . " - " . date("m/d/Y", $end)
      $now = new \DateTime('now');
      $endDate = $now->modify('+1 day')->format('m/d/Y');

      switch ($searchParams['date-range']) {
        case 'today':
          $startDate = $now->format('m/d/Y');
          break;
        case 'lastWeek':
          $startDate = $now->modify('-7 day')->format('m/d/Y');
          break;
        case 'lastMonth':
          $startDate = $now->modify('-1 month')->format('m/d/Y');
          break;
        default:
          $startDate = false;
      }

      if ($startDate) {
        $dateSpan = $startDate . '-' . $endDate;

        $filterField = new FilterField('UpdateDate', SearchPhrase::BETWEEN_DATES, $dateSpan, NlsFilter::DATE_TIME_RANGE);
        $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
      }
    }

    if (key_exists('employment-type', $searchParams)) {
      $filterField = new FilterField('EmploymentType', SearchPhrase::EXACT, $searchParams['employment-type'], NlsFilter::NUMERIC_VALUES);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('employment-form', $searchParams)) {
      $filterField = new FilterField('EmploymentForm', SearchPhrase::EXACT, $searchParams['employment-form'], NlsFilter::NUMERIC_VALUES);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('emploter-type', $searchParams)) {
      $filterField = new FilterField('EmployerType', SearchPhrase::EXACT, $searchParams['emploter-type'], NlsFilter::NUMERIC_VALUES);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('employer-id', $searchParams)) {
      $filterField = [];
      foreach ($searchParams['employer-id'] as $value) {
        $filterFieldOption = new FilterField('EmployerId', SearchPhrase::EXACT, $value, NlsFilter::TERMS_NON_ANALAYZED);
        $filterField[] = $filterFieldOption;
      }
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('regions', $searchParams)) {
      $filterField = new FilterField('RegionId', SearchPhrase::EXACT, $searchParams['regions'], NlsFilter::TERMS_NON_ANALAYZED);
      $this->addWhereFilter($filterField, is_array($filterField) ? WhereCondition::C_OR : WhereCondition::C_AND);
    }

    if (key_exists('keywords', $searchParams)) {
      $keywords = preg_split("/[\s,]+/", $searchParams['keywords']);
      $fields = [];

      foreach ($keywords as $term) {
        $this->addSearchTerm($fields, $term);
      }
      $this->addWhereFilter($fields, WhereCondition::C_OR);
    }

    return $this;
  }

  // For keywords search
  private function addSearchTerm(&$arr, $term)
  {
    $field = new FilterField('Description', SearchPhrase::EXACT, $term, NlsFilter::TERMS_NON_ANALAYZED);
    $arr[] = $field;

    $field = new FilterField('Requiremets', SearchPhrase::EXACT, $term, NlsFilter::TERMS_NON_ANALAYZED);
    $arr[] = $field;

    $field = new FilterField('JobTitle', SearchPhrase::EXACT, $term, NlsFilter::TERMS_NON_ANALAYZED);
    $arr[] = $field;

    $field = new FilterField('JobCode', SearchPhrase::EXACT, $term, NlsFilter::TERMS_NON_ANALAYZED);
    $arr[] = $field;
  }
}
