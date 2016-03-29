<?php

/**
 * This is the model class for table "manager".
 *
 * The followings are the available columns in table 'manager':
 * @property string $id
 * @property string $role
 * @property string $managerName
 * @property string $email
 * @property string $password
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 */
class Manager extends FoodTalkActiveRecord
{
    public $oldPassword;
    public $newPassword;
    public $confirmPassword;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'manager';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('role', 'length', 'max'=>16),
            array('managerName, email, disableReason', 'length', 'max'=>128),
            array('password', 'length', 'max'=>40),
            array('createId, updateId', 'length', 'max'=>10),
            array('updateDate', 'safe'),

            //Change password rules
            array('oldPassword, newPassword, confirmPassword', 'required', 'on'=>'changePassword'),
            array('oldPassword, newPassword, confirmPassword', 'length', 'max'=>40, 'on'=>'changePassword'),
            array('oldPassword', 'validatePassword', 'on' => 'changePassword'),
            array('confirmPassword', 'compare', 'compareAttribute'=>'newPassword', 'on'=>'changePassword'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, role, managerName, email, password, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'role' => 'Role',
            'managerName' => 'Manager Name',
            'email' => 'Email',
            'password' => 'Password',
            'isDisabled' => 'Is Disabled',
            'disableReason' => 'Disable Reason',
            'createDate' => 'Create Date',
            'updateDate' => 'Update Date',
            'createId' => 'Create',
            'updateId' => 'Update',
            'oldPassword' => 'Current Password',
            'newPassword' => 'New Password',
            'confirmPassword' => 'Confirm Password',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('managerName',$this->managerName,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Manager the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    //validate existing password.
    public function validatePassword($attribute, $params)
    {
        $manager = Manager::model()->findByPk(Yii::app()->user->id);
        if ($manager->password != $this->oldPassword) //md5($this->oldPassword)
            $this->addError($attribute, 'Old password is incorrect.');
    }
}
