<?php

namespace app\components\niloosoft\niloosoft\api;

use Yii;
/**
 * Description of NlsCards
 *
 * @author nurielmeni
 */
class NlsCards extends NlsService
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getJobById($jobId)
    {
        try {
            if (!$jobId)
                throw new \Exception('Job Id parameter is missing');
            $cache_key = 'getJobById' . $jobId;
            $res = $this->isCache() ? $this->cache->get($cache_key) : false;
            if (!$res) {
                $res = $this->fetcher->GET('JobUtility/' . $jobId);
                if ($this->isCache())
                    $this->cache->set($cache_key, $res, $this->cacheTime);
            }
            return $res;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage());
            return false;
        }
    }
}
