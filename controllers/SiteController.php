<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model=new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes=$_POST['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                        "Reply-To: {$model->email}\r\n".
                        "MIME-Version: 1.0\r\n".
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
                Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact',array('model'=>$model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm('manager');

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(array('user/admin'));
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }
    
    /**
     * Displays the forgot password page
     */
    public function actionForgotPassword()
    {
        $model=new ForgotPasswordForm('manager');

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='forgot-password-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['ForgotPasswordForm']))
        {
            $model->attributes=$_POST['ForgotPasswordForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate())
            {
                $password = randomString(8);
                Manager::model()->updateAll(array('password'=>  $password), 'email="' . $model->email . '"');

                //send email to sender
                $mailBody = "Dear user,";
                $mailBody .= "<p>Your request to reset password is processed successfully. Your new password is $password .</p>";
                $mailBody .= "<p>Please change your password after logging in.</p>";
                $mailBody .= '<p>Kind Regards,<br/>The team at Chef Smart&trade;</p>';

                Yii::import('ext.yii-mail.YiiMailMessage');
                $message = new YiiMailMessage;
                $message->subject = 'Chef Smart - Password reset';
                $message->setBody($mailBody, 'text/html');
                $message->addTo($model->email);
                $message->from = INFO_EMAIL;
                Yii::app()->mail->send($message);

                Yii::app()->user->setFlash('success','Your password is reset successfully. Please check your email.');
                $this->redirect(array('forgotPassword'));
            }
        }
        // display the login form
        $this->render('forgotPassword',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}