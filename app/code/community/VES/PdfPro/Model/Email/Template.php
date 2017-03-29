<?php
/**
 * VES_PdfPro_Model_Email_Template
 *
 * @author		VnEcoms Team <support@vnecoms.com>
 * @website		http://www.vnecoms.com
 */
class VES_PdfPro_Model_Email_Template extends Mage_Core_Model_Email_Template
{
    /**
     * Compatible with Aschroder_SMTPPro
     */
    public function isEnableSmtpPro(){
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        if(!isset($modulesArray['Aschroder_SMTPPro'])) return false;

        return $modulesArray['Aschroder_SMTPPro']->is('active') && Mage::helper('smtppro')->isEnabled();
    }

    /**
     * Compatible with Mandrill SMTP
     */
    public function isEnableMandrillSmtp() {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        if(!isset($modulesArray['Ebizmarts_Mandrill'])) return false;

        return $modulesArray['Ebizmarts_Mandrill']->is('active') and Mage::getStoreConfig(Ebizmarts_Mandrill_Model_System_Config::ENABLE);
    }

    /**
     * check enable or active Mage Monkey SMTP
     */
    public function isEnabledMageMonkey() {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        if(!isset($modulesArray['Ebizmarts_MageMonkey'])) return false;

        return $modulesArray['Ebizmarts_MageMonkey']->is('active') && Mage::helper('monkey')->config('active') and Mage::helper('monkey')->useTransactionalService() === FALSE;
    }

    /**
     * Send mail to recipient
     *
     * @param   array|string       $email        E-mail(s)
     * @param   array|string|null  $name         receiver name(s)
     * @param   array              $variables    template variables
     * @return  boolean
     **/
    public function send($email, $name = null, array $variables = array(),$pdf = null)
    {
        Mage::log('send');
        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        $first = Mage::getStoreConfig('pdfpro/config/smtp');
        switch($first) {
            /*             case 'Aschroder_SMTPPro':
                            if($this->isEnableSmtpPro()) {$this->sendAschroderSmtpPro($email,$name,$variables,$pdf);return;}
                        break; */
            case 'Ebizmarts_Mandrill':
                if($this->isEnableMandrillSmtp()) {$this->sendMandrillSmtp($email,$name,$variables,$pdf);return;}
                break;
        }

        Mage::log('normal');

        //if($this->isEnableMandrillSmtp()) {$this->sendMandrillSmtp($email,$name,$variables,$pdf);return;}

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        $mail = $this->getMail();

        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        if ($returnPathEmail !== null) {
            $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
            Zend_Mail::setDefaultTransport($mailTransport);
        }

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }


        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
            $mail->setBodyText($text);
        } else {
            $mail->setBodyHTML($text);
        }

        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        /* Attach the PDF file*/
        if($pdf && is_array($pdf)){
            $at = $mail->createAttachment($pdf['content']);

            //set the attachment type as PDF
            $at->type = 'application/pdf';

            //set the fileame
            $at->filename = $pdf['filename'];
        }

        $transport = null;
        if ($this->isEnableSmtpPro()) {
            $transport = Mage::helper('smtppro')->getTransport($this->getDesignConfig()->getStore());
        }
        try {
            $mail->send($transport);
            $this->_mail = null;
        }
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }

        return true;
    }

    /**
     * Send transactional email to recipient
     *
     * @param   int $templateId
     * @param   string|array $sender sneder informatio, can be declared as part of config path
     * @param   string $email recipient email
     * @param   string $name recipient name
     * @param   array $vars varianles which can be used in template
     * @param   int|null $storeId
     * @param	bool $attachPDF
     * @return  Mage_Core_Model_Email_Template
     */
    public function sendTransactional($templateId, $sender, $email, $name, $vars=array(), $storeId=null,$pdf=null)
    {
        $this->setSentSuccess(false);
        if (($storeId === null) && $this->getDesignConfig()->getStore()) {
            $storeId = $this->getDesignConfig()->getStore();
        }

        if (is_numeric($templateId)) {
            $this->load($templateId);
        } else {
            $localeCode = Mage::getStoreConfig('general/locale/code', $storeId);
            $this->loadDefault($templateId, $localeCode);
        }

        if (!$this->getId()) {
            throw Mage::exception('Mage_Core', Mage::helper('core')->__('Invalid transactional email code: ' . $templateId));
        }

        if (!is_array($sender)) {
            $this->setSenderName(Mage::getStoreConfig('trans_email/ident_' . $sender . '/name', $storeId));
            $this->setSenderEmail(Mage::getStoreConfig('trans_email/ident_' . $sender . '/email', $storeId));
        } else {
            $this->setSenderName($sender['name']);
            $this->setSenderEmail($sender['email']);
        }

        if (!isset($vars['store'])) {
            $vars['store'] = Mage::app()->getStore($storeId);
        }
        $this->setSentSuccess($this->send($email, $name, $vars,$pdf));
        return $this;
    }

    /**
     * @return Mandrill_Message|Zend_Mail
     */
    public function getMail()
    {
        if(!$this->isEnableMandrillSmtp() or Mage::getStoreConfig('pdfpro/config/smtp') !== 'Ebizmarts_Mandrill') return parent::getMail();

        $storeId = Mage::app()->getStore()->getId();
        if(!Mage::getStoreConfig(Ebizmarts_Mandrill_Model_System_Config::ENABLE,$storeId)) {
            return parent::getMail();
        }
        if($this->_mail) {
            return $this->_mail;
        }
        else {
            $storeId = Mage::app()->getStore()->getId();
            Mage::log("store: $storeId API: ".Mage::getStoreConfig( Ebizmarts_Mandrill_Model_System_Config::APIKEY,$storeId ));
            $this->_mail = new Mandrill_Message(Mage::getStoreConfig( Ebizmarts_Mandrill_Model_System_Config::APIKEY,$storeId ));
            return $this->_mail;
        }
    }

    /**
     * @param array|string $email
     * @param null $name
     * @param array $variables
     * @return bool
     */
    public function sendMandrillSmtp($email, $name = null, array $variables = array(),$pdf=null)
    {
        Mage::log('sendMandrillSmtp');
        $storeId = Mage::app()->getStore()->getId();
        if(!Mage::getStoreConfig(Ebizmarts_Mandrill_Model_System_Config::ENABLE,$storeId)) {
            return;
        }
        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }
        $emails = array_values( (array)$email );
        $names = is_array( $name ) ? $name : (array)$name;
        $names = array_values( $names );
        foreach ( $emails as $key => $email ) {
            if ( ! isset( $names[$key] ) ) {
                $names[ $key ] = substr( $email, 0, strpos( $email, '@' ) );
            }
        }

        // Get message
        $this->setUseAbsoluteLinks( true );
        $variables['email'] = reset( $emails );
        $variables['name'] = reset( $names );
        $message = $this->getProcessedTemplate( $variables, true );

        $email = array( 'subject' => $this->getProcessedTemplateSubject( $variables ), 'to' => array() );

        $mail = $this->getMail();

        for ( $i = 0; $i < count( $emails ); $i++ ) {
            if ( isset( $names[ $i ] ) ) {
                $email['to'][] = array(
                    'email' => $emails[ $i ],
                    'name' => $names[ $i ]
                );
            }
            else {
                $email['to'][] = array(
                    'email' => $emails[ $i ],
                    'name' => ''
                );
            }
        }
        foreach($mail->getBcc() as $bcc)
        {
            $email['to'][] = array(
                'email' => $bcc,
                'type' => 'bcc'
            );
        }

        $email['from_name'] = $this->getSenderName();
        $email['from_email'] = $this->getSenderEmail();
        $email['headers'] = $mail->getHeaders();
        if(isset($variables['tags']) && count($variables['tags'])) {
            $email ['tags'] = $variables['tags'];
        }

        if(isset($variables['tags']) && count($variables['tags'])) {
            $email ['tags'] = $variables['tags'];
        }
        else {
            $templateId = (string)$this->getId();
            $templates = parent::getDefaultTemplates();
            if (isset($templates[$templateId]) && isset($templates[$templateId]['label'])) {
                $email ['tags'] =  array(substr($templates[$templateId]['label'], 0, 50));
            } else {
                if($this->getTemplateCode()){
                    $email ['tags'] = array(substr($this->getTemplateCode(), 0, 50));
                } else {
                    if($templateId){
                        $email ['tags'] = array(substr($templateId, 0, 50));
                    }else{
                        $email['tags'] = array('default_tag');
                    }
                }
            }
        }

        /* Attach the PDF file*/
        if($pdf && is_array($pdf)){
            Mage::log('1');
            $at = $mail->createAttachment($pdf['content'], 'application/pdf', Zend_Mime::DISPOSITION_ATTACHMENT, Zend_Mime::ENCODING_BASE64, $pdf['filename']);
        }

        if($att = $mail->getAttachments()) {
            //Mage::log($att);
            Mage::log('2');
            $email['attachments'] = $att;
        }
        if( $this->isPlain() )
            $email['text'] = $message;
        else
            $email['html'] = $message;

        try {
            $result = $mail->messages->send( $email );
            $this->_mail = null;
        }
        catch( Exception $e ) {
            $this->_mail = null;
            Mage::logException( $e );
            return false;
        }
        return true;

    }
}
