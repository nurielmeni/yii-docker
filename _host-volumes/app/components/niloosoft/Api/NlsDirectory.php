<?php

namespace app\components\niloosoft\niloosoft\api;

use app\components\niloosoft\niloosoft\api\NlsFetcher;
use app\components\niloosoft\niloosoft\api\NlsSearch;

/**
 * Description of NlsDirectory
 *
 * @author nurielmeni
 */

if (!class_exists('NlsDirectory')) {

    class NlsDirectory extends NlsService
    {
        const CACHE_GROUP = 'nsl_directory';

        private $fetcher;

        /**
         * 
         */
        public function __construct($fetcher = null)
        {
            parent::__construct();
            $this->fetcher = $fetcher ?? new NlsFetcher();
        }

        public function getListByName($listname = null, $forceGetFromDB = false, $parentItemId = null)
        {
            $cache_key = 'getListByName_' . $listname;
            $res = $this->isCache() ? wp_cache_get($cache_key, self::CACHE_GROUP) : false;
            try {
                if (!$res) {
                    $data = [
                        'LanguageId' => $this->langCode,
                        'ListName' => $listname,
                        'ForceGetFromDB' => $forceGetFromDB,
                        'ParentItemId' => $parentItemId,
                    ];
                    $res = $this->fetcher->POST('TranslationLists/GetListItems', $data);
                    if ($this->isCache())
                        wp_cache_set($cache_key, $res, self::CACHE_GROUP, $this->cacheTime);
                }
                return $res;
            } catch (\Exception $ex) {
                $this->errors[] = 'getListByName:' . $ex->getMessage();
                return false;
            }
        }

        public function getListItemById($list = false, $id = false)
        {
        }

        public function getCategories($parentId = null)
        {
            $categories = $parentId == null
                ? $this->getListByName('ProfessionalCategories')
                : $this->getListByName('ProfessionalFields');

            return $categories;
        }

        public function getLocations($parentId = null)
        {
        }

        public function getJobScopes()
        {
        }

        public function getJobRanks()
        {
        }

        public function getJobEmploymentType()
        {
        }

        public function getJobEmploymentForm()
        {
        }

        public function getjoblocations()
        {
        }

        public function getApplicantByUserName($username)
        {
        }

        public function getJobArea()
        {
        }

        public function getProfessionalFields()
        {
        }

        public function getRegions()
        {
        }

        public function getUserIdByCardId($cardId)
        {
        }

        public function createEmployersList()
        {

            $search = new NlsSearch(['fetcher' => $this->fetcher]);
            $hunterId = NlsHelper::newGuid();
            $cacheKey = 'all_employers';

            $queryConfig = [
                "ResultRowLimit" => 1000,
                "ResultRowOffset" => 0
            ];

            $filter = new NlsFilter();
            $filter->createFilter([]);
            $queryInfo = $filter;

            $res = $search->JobHunterQueryUtility($hunterId, $queryConfig, $queryInfo, $cacheKey);
            if (!is_object($res) || !property_exists($res, 'SearchEngineResult') || !property_exists($res->SearchEngineResult, 'Results'))
                return [];

            $options = [];
            foreach ($res->SearchEngineResult->Results as $item) {
                $options[$item->EmployerId] = $item->EmployerName;
            }
            return $options;
        }

        public function getStaticCustomList()
        {
            $listitems = array(
                1 => 'ס. נשיא לפרויקטים אסטרטגיים',
                2 => 'נציבות למניעת הטרות מיניות בטכניון',
                3 => 'משרות סטודנטים',
                4 => 'מוסד הטכניון',
                5 => 'היוזמה לבריאות האדם בטכניון',
                6 => '299. מרכז לוקיי',
                7 => '294. היחידה למינהל ולוגיסטיקה',
                8 => '292. מכרזים ולוגיסטיקה',
                9 => '289. בית הכנסת',
                10 => '287. אגודת דורשי הטכניון',
                11 => '285. יחידת הדפוס',
                12 => '284. דואר',
                13 => '283. הלשכה המשפטית',
                14 => '282. אגף תקציבים',
                15 => '281. אגף חשבות',
                16 => '280. יחידת שיווק',
                17 => '279 - אגף מחשוב ומערכות מידע',
                18 => '275. ארגון ההנדסאים',
                19 => '268. מרכז לקידום הוראה',
                20 => '267. סגן משנה בכיר לנשיא',
                21 => '265. לשכת משנה נשיא למחקר',
                22 => '264. יחידת הבטיחות',
                23 => '263. מעונות',
                24 => '258. אגף בינוי ותחזוקה',
                25 => '257. ס. נשיא לקשרי חוץ ופיתוח משאבים',
                26 => '255. קשרי ציבור',
                27 => '254. המחסן הכימי',
                28 => '253. רכש',
                29 => '252. המרכז הבינלאומי',
                30 => '251. אגף כספים ובקרה',
                31 => '250. שירותים מנהליים',
                32 => '249. ביקורת פנימית',
                33 => '248. אגף נכסים והשקעות',
                34 => '247. מרכז רישום וקבלה',
                35 => '246. אגף משאבי אנוש',
                36 => '245. דיקן הסטודנטים',
                37 => '244. לימודי הסמכה',
                38 => '243. לשכת המנל"א',
                39 => '242. לשכת משנה לנשיא ומנכ"ל',
                40 => '241. לשכת הנשיא',
                41 => '240. סמנכ"ל תפעול',
                42 => '239. יחידת הביטחון',
                43 => '238. לשכת משנה בכיר לנשיא',
                44 => '237. חינוך קדם אקדמי',
                45 => '234. ביולוגיה',
                46 => '233. הנדסה ביורפואית',
                47 => '232. מכון למצב מוצק',
                48 => '231. מכון לחקר החלל',
                49 => '229. ספריה מרכזית',
                50 => '227. רפואה',
                51 => '226. בי"ס לתארים מתקדמים',
                52 => '224. מעבדות דנציגר',
                53 => '222. חינוך מדע וטכנולוגיה',
                54 => '220. לימודים הומניסטיים',
                55 => '219. תעשייה וניהול',
                56 => '218. פס"ק (סכנות קרינה)',
                57 => '216. אוירונאוטיקה וחלל',
                58 => '212. מדעי המחשב',
                59 => '210. מתמטיקה',
                60 => '209. פיסיקה',
                61 => '208. ביוטכנולוגיה ומזון',
                62 => '207. הנדסה כימית',
                63 => '206. כימיה',
                64 => '205. הנדסת חשמל',
                65 => '204. חומרים',
                66 => '203. מכונות',
                67 => '202. ארכיטקטורה',
                68 => '201. הנדסה אזרחית'
            );

            return $listitems;
        }
        /**
         * Gets the user info by the user id
         * $userId user id (not GUID)
         * $utilizerId the utilizer Id
         * @return array of Applicants
         */
        public function userGetById($userId, $utilizerId = 5806) //3856
        {
            return [];
        }
    }
}
