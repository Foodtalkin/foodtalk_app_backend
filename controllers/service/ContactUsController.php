<?php
class ContactUsController extends ServiceBaseController
{
    /**
     * Create new contactUs
     */
    public function actionAdd()
    {
        $apiName = 'contactUs/add';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['message']) || empty($_JSON['message']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter your message.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $contactUs = new ContactUs('create_api');
                    $contactUs->userId = $userId;
                    $contactUs->message = filter_var($_JSON['message'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    //save record
                    $contactUs->save();
                    if ($contactUs->hasErrors()) 
                    {
                        throw new Exception(print_r($contactUs->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Your message is saved successfully.',
                        'status' => 'OK',
                        'contactUsId' => $contactUs->id
                    );
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * List contactUss
     */
    public function actionList()
    {
        $apiName = 'contactUs/list';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $contactUsList = ContactUs::getByUserId($userId);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'ContactUs records fetched successfully.',
                        'status' => 'OK',
                        'contactUs' => $contactUsList
                    );
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
}