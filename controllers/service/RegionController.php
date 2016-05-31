<?php
class RegionController extends ServiceBaseController
{
    /**
     * List tags
     */
    public function actionList()
    {
        $apiName = 'region/list';
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
//                     $tagName = '';
//                     if(isset($_JSON['tagName']) && $_JSON['tagName'])
//                         $tagName = filter_var($_JSON['tagName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);

		$regions = [];
                    $region = Region::model()->findAll();
                   foreach ($region as $val){
                   	$regions[]['name'] = $val->name;
                   }
                    
//                     var_dump($region);
//                     $tags = Dish::listByName($tagName);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Region fetched successfully.',
                        'status' => 'OK',
                        'regions' => $regions
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