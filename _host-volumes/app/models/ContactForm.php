<?php

namespace app\models;

use Yii;
use yii\base\Model;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends BaseContactForm
{     
    protected function generateNcai() {
        $xmlData = '<NiloosoftCvAnalysisInfo xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">' . "\r\n";
        $xmlData .= '  <ApplyingPerson>' . "\r\n";
        $xmlData .= '    <AccountManagerId xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <BirthDate xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <CardId>00000000-0000-0000-0000-000000000000</CardId>' . "\r\n";
        $xmlData .= '    <CreatedBy>0</CreatedBy>' . "\r\n";
        $xmlData .= '    <CreationDate xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <Email></Email>' . "\r\n";
        $xmlData .= '    <EntityLocalName>' . $this->name . '</EntityLocalName>' . "\r\n";
        $xmlData .= '    <HighestStage xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <IsParseAllAsTransient xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <LockLevelStatus>0</LockLevelStatus>' . "\r\n";
        $xmlData .= '    <ParentCardId xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <Phones/>' . "\r\n";
        $xmlData .= '    <Rank xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <RecruitedBy xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <SellsStatus xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <Status xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <StatusExpirationTime xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <UpdateDate>0001-01-01T00:00:00</UpdateDate>' . "\r\n";
        $xmlData .= '    <UpdatedBy>0</UpdatedBy>' . "\r\n";
        $xmlData .= '    <Gender>mail</Gender>' . "\r\n";
        $xmlData .= '    <NumberOfChildren xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <Role xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <SupplierApplicantId xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <SupplierExpirationTime xsi:nil="true"/>' . "\r\n";
        $xmlData .= '    <SupplierId>' . $this->supplierId . '</SupplierId>' . "\r\n";
        $xmlData .= '    <UserId xsi:nil="true"/>' . "\r\n";      
        $xmlData .= '  </ApplyingPerson>' . "\r\n";
        $xmlData .= '  <Notes>' . "\r\n";
        $xmlData .=      $this->getAttributeLabel('name') . ': ' . $this->name . "\r\n";        
        $xmlData .=      $this->getAttributeLabel('idnumber') . ': ' . $this->idnumber . "\r\n";  
        if($this->jobDetails) {
            $xmlData .=      $this->getAttributeLabel('jobCode') . ': ' . $this->jobDetails->JobCode . "\r\n";        
            $xmlData .=      $this->getAttributeLabel('jobTitle') . ': ' . $this->jobDetails->JobTitle . "\r\n";        
        }      
        $xmlData .= '  </Notes>' . "\r\n";        
        $xmlData .= '  <SupplierId>' . $this->supplierId . '</SupplierId>' . "\r\n";
        $xmlData .= '</NiloosoftCvAnalysisInfo>' . "\r\n";
        
        $tmpFile = 'uploads/NlsCvAnalysisInfo.ncai';
        if (file_put_contents($tmpFile, $xmlData)) {
            $this->tmpFiles[] = $tmpFile;
            return true;
        }
        return false;
    }

}
