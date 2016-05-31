<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class SiteBaseController extends Controller
{
    //methods that will control authorization
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', 'expression'=>'Yii::app()->controller->checkAuth()',),
            array('deny', 'users'=>array('*'),),
        );
    }

    public function checkAuth($controllerId = null, $actionId = null)
    {
        if($controllerId == null)
            $controllerId = Yii::app()->controller->id;

        if($actionId == null)
            $actionId = Yii::app()->controller->action->id;

        $task = $controllerId . '.' . $actionId;

        $taskList = array();
        if(Yii::app()->user->isGuest)
            $taskList = Yii::app()->controller->guestTasks();
//        else if(Yii::app()->user->role === 'user')
//            $taskList = Yii::app()->controller->userTasks();
        else if(Yii::app()->user->role === 'manager')
            $taskList = Yii::app()->controller->managerTasks();

//        echo Yii::trace(CVarDumper::dumpAsString($task),'vardump');
//        echo Yii::trace(CVarDumper::dumpAsString($taskList),'vardump');

        if(in_array(strtolower($task), array_map('strtolower', $taskList)))
            return true;
        else
            return false;
    }

    public function guestTasks()
    {
        return array(
            'site.index',
            'site.contact',
            'site.about',
            'site.login',
            'site.error',
            'site.forgotPassword',
        );
    }

//    public function userTasks()
//    {
//        return array(
//            'site.index',
//            'site.contact',
//            'site.about',
//            'site.logout',
//            'site.error',
//            'liveCard.update',
//            'liveCard.changePassword',
//            'liveCard.view',
//            'staticCard.create',
//            'staticCard.view',
//            'staticCard.update',
//            'staticCard.delete',
//            'staticCard.restore',
//            'staticCard.admin',
//        );
//    }

    public function managerTasks()
    {
        return array(
            'site.index',
            'site.contact',
            'site.about',
            'site.logout',
            'site.error',
            'manager.update',
            'manager.changePassword',
            'user.admin',
            'user.view',
            'user.update',
            'user.delete',
            'user.restore',
       		'user.disabled',        		
            'restaurant.admin',
            'restaurant.create',
            'restaurant.update',
            'restaurant.view',
            'restaurant.reported',
            'restaurant.delete',
        	'restaurant.suggestion',
            'restaurant.restore',
        		
       		'restaurant.verified',
       		'restaurant.unverified',
       		'restaurant.inactive',
       		'restaurant.disabled',
       		'restaurant.duplicate',
        		
            'post.admin',
        	'post.create',
       		'post.disabled',
       		'post.update',
            'post.view',
            'post.export',
            'post.delete',
            'post.restore',
        	'post.approve',
        	'dish.admin',
        	'dish.disable',
        	'dish.delete',
        		
        	'dish.view',
        	'dish.restore',

        		
        	'cuisine.admin',
        	'cuisine.removedish',
            'cuisine.create',
            'cuisine.update',
            'cuisine.view',
            'cuisine.export',
            'cuisine.delete',
            'cuisine.restore',
        		
       		'comment.admin',
       		'comment.reported',
       		'comment.delete',
       		'comment.disabled',
       		'comment.restore',
        	'comment.approve',

        		
        	'notification.index',
        	'notification.create',
        	'notification.view',
        	'notification.admin',
        	'notification.pending',
        	'notification.notified',
        	'notification.delete',
        		
        		
        		'activityPoints.index',
        		'activityPoints.create',
        		'activityPoints.view',
        		'activityPoints.admin',
        		'activityPoints.update',
//         		'activityPoints.notified',
        		'activityPoints.delete',
        		
        		'activityScore.view',
        		'activityScore.admin',
        		
        		'region.index',
        		'region.view',
        		
            'city.admin',
            'city.create',
            'city.update',
            'city.view',
            'city.export',
            'city.delete',
            'city.restore',
        	'contactus.admin',
        );
    }
}