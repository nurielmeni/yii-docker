<?php

namespace app\models;

use Yii;
use yii\base\Model;
use kartik\mpdf\Pdf;
use app\models\Search;
use yii\helpers\ArrayHelper;

/**
 * ContactForm is the model behind the contact form.
 */
class BaseContactForm extends Model
{
    public $name;
    public $idnumber;
    //public $phone;
    //public $email;
    public $jobTitle;
    public $supplierId;
    public $cvfile;
    public $education;
    public $experiance;
    public $jobDetails;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->experiance = Yii::t('app', 'Experiance');
        $this->education = Yii::t('app', 'Education');
    }
    
    protected $tmpFiles = [];
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'idnumber', 'jobTitle', 'cvfile'], 'required'],
            ['idnumber', 'match', 'pattern' => '/^\d{9}$/i'],
            ['supplierId', 'match', 'pattern' => '/^[a-zA-Z\d-]+$/i'],
            [['name'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            //['phone', 'match', 'pattern' => '/^0[0-9]{1,2}[-\s]{0,1}[0-9]{3}[-\s]{0,1}[0-9]{4}/i'],
            ['cvfile', 'file', 'extensions' => ['doc', 'docx', 'pdf', 'rtf'], 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Applicant Name'),
            'idnumber' => Yii::t('app', 'Id Number'),
            //'phone' => Yii::t('app', 'Phone'),
            //'email' => Yii::t('app', 'Email'),
            'jobTitle' => Yii::t('app', 'Job Title'),
            'jobCode' => Yii::t('app', 'Job Code'),
            'searchArea' => Yii::t('app', 'Search Area'),
            'cvfile' => Yii::t('app', 'Attach CV file'),
            'supplierId' => Yii::t('app', 'Supplier Id'),
            'education' => Yii::t('app', 'Education'),
            'experiance' => Yii::t('app', 'Experiance'),
        ];
    }
    
    public function getJobs() {
        $search = new Search($this->supplierId);
        ArrayHelper::map($search->jobs(), 'JobId', 'JobTitle');
    }

    public function getStores() {
        $search = new Search($this->supplierId);
        return ArrayHelper::map($search->jobs(), 'RegionValue', 'RegionText');
    }

    public function getSearchAreaOptions() {
        return [
            //Yii::t('app', 'Center') => Yii::t('app', 'Center'),
            Yii::t('app', 'Jerusalem') => Yii::t('app', 'Jerusalem'),
            Yii::t('app', 'South') => Yii::t('app', 'South'),
            Yii::t('app', 'North') => Yii::t('app', 'North'),
            //Yii::t('app', 'East') => Yii::t('app', 'East'),
        ];
    }

    public function getYesnoOptions() {
        return [
            Yii::t('app', 'Yes') => Yii::t('app', 'Yes'),
            Yii::t('app', 'No') => Yii::t('app', 'No'),
        ];
    }
    
    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email, $content)
    {
        Yii::debug('Contact: Started', 'meni');
        if ($this->jobDetails) {
            $subject = Yii::t('app', 'New request - Elbit Campaign') . ' [' . $this->jobDetails->JobCode . ']';
        } else {
            $subject = Yii::t('app', 'New request - Elbit Campaign') . ' [הגשה למאגר הכללי]';
        }
        Yii::debug('Contact: Subject ' . $subject, 'meni');
        if (!$this->cvfile || empty($this->cvfile)) {
            $this->generateCv($content);
            Yii::debug('Contact: cv generated', 'meni');
        }
        $this->generateNcai();
        Yii::debug('Contact: NCAI generated', 'meni');

        Yii::debug("Contact: Mail: $email, Content: $content" , 'meni');

        $message = Yii::$app->mailer->compose()
            ->setTo($email)
            //->setFrom([$email => Yii::$app->params['cvWebMailName']])
            //->setBcc('nurielmeni@gmail.com')
            ->setSubject($subject)
            ->setHtmlBody($content)
            ->setTextBody(strip_tags($content));
        
        foreach ($this->tmpFiles as $tmpFile) {
            $message->attach($tmpFile);
        }
                
        $res = $message->send();

        //Yii::debug("Contact: res: $res" , 'meni');
        return $res;
    }
        
    public function removeTmpFiles() {
        foreach ($this->tmpFiles as $tmpFile) {
            if (file_exists($tmpFile)) unlink($tmpFile);
        }
    }
    
    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether email sent successfully
     */
    public function followUpMail($content)
    {
        $subject = 'אתר משרות אלביט - בקשתך התקבלה';
            return Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['fromMail'] => Yii::$app->params['fromName']])
                ->setSubject($subject)
                ->setHtmlBody($content)
                ->setTextBody(strip_tags($content))
                ->send();
    }
    
    public function generateCv($content) {
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_FILE, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'אלביט - קובץ קורות חיים אוטומטי'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['אלביט - קורות חיים למועמד'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        $tmpfile = Yii::getAlias('@webroot') . '/uploads/cvFile' . date('s', time()) . '.pdf';
        $pdf->output($content, $tmpfile, Pdf::DEST_FILE);
        $this->tmpFiles[] = $tmpfile;
        return true;
    }
    
    protected function sanitizeFileName($file, $ext = null) {
        // Remove anything which isn't a word, whitespace, number
        // or any of the following caracters -_~,;[]().
        // If you don't need to handle multi-byte characters
        // you can use preg_replace rather than mb_ereg_replace
        // Thanks @Łukasz Rysiak!
        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
        // Remove any runs of periods (thanks falstro!)
        $file = mb_ereg_replace("([\.]{2,})", '', $file);
        return $ext ? ($file . '.' . $ext) : $file;
    }
    
    public function upload()
    {
        $tmpFile = 'uploads/' . $this->sanitizeFileName($this->cvfile->baseName, $this->cvfile->extension);
        if ($this->cvfile->saveAs($tmpFile)) {
            $this->tmpFiles[] = $tmpFile;
        }
        return true;
    }
    
    public function getJobCode() {
        $jobCode = $this->jobTitle;
        return $jobCode;
    }

}
