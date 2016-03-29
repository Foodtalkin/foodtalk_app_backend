<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    private $_role;
    private $_dispName;
    
    public function __construct($email, $password, $role) {
        parent::__construct($email, $password);
        $this->role = $role;
    }
    
    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $record = null;
        
        if($this->role === 'user')
            $record = User::model()->findByAttributes(array('email' => $this->username, 'isDisabled'=>0));
        else if($this->role === 'manager')
            $record = Manager::model()->findByAttributes(array('email' => $this->username));
        
        if ($record === null) 
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } 
        else if ($record->password !== $this->password) 
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } 
        else 
        {
            $this->_id = $record->id;
            $this->_role = $record->role;
            
            if($record->role==='user')
                $this->_dispName = $record->userName;
            else if($record->role==='restaurant')
                $this->_dispName = $record->restaurantName;
            else
                $this->_dispName = $record->managerName;
            
            $this->setState('role', $this->_role);
            $this->setState('dispName', $this->_dispName);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getRole()
    {
        return $this->_role;
    }
    
    public function setRole($role)
    {
        $this->_role = $role;
    }
    
    public function getDispName()
    {
        return $this->_dispName;
    }
    
    public function setDispName($dispName)
    {
        $this->_dispName = $dispName;
    }
}