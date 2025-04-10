<?php

namespace app\components\niloosoft\niloosoft\api;

use app\components\niloosoft\niloosoft\api\NlsHelper;

/**
 * Description of NlsSearch
 *
 * @author nurielmeni
 */
class NlsSearch extends NlsService
{
    public const CACHE_GROUP = 'nsl_search';

    /**
     * 
     * @param  array $config the $auth and $settings
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function jobHunterQueryUtility($hunterId = null, $queryConfig = [], $queryInfo = [], $cacheKey = null)
    {
        $hunterId = $hunterId ?? NlsHelper::newGuid();
        $cacheKey = $cacheKey ?: 'JobHunterQueryUtility';
        $res = $this->isCache() ? $this->cache->get($cacheKey) : false;
        try {
            if (!$res) {
                $data = [
                    'HunterId' => $hunterId,
                    'QueryConfig' => $queryConfig,
                    'QueryInfo' => $queryInfo,
                ];
                $res = $this->fetcher->POST('JobHunterQueryUtility', $data);
                return $res;

                if ($this->isCache())
                    wp_cache_set($cacheKey, $res, self::CACHE_GROUP, $this->cacheTime);
            }
            return $res;
        } catch (\Exception $ex) {
            $this->errors[] = 'getListByName:' . $ex->getMessage();
            return false;
        }
    }

    public function filterJobsByExtendedProperty($jobs, $propName, $propValue)
    {
        if (!$propName || !$propValue)
            return $jobs;

        $filteredJobs = array_filter($jobs, function ($job) use ($propName, $propValue) {
            $val = NlsHelper::getExtendedProperty($job->ExtendedProperties, $propName);
            return $val == $propValue;
        });

        return $filteredJobs;
    }
}
