<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Користувач',
            'password' => 'Пароль',
            'email' => 'Адреса эл.пошти',
            //'proftpd' => 'Proftpd',
            //'born' => 'Born',
            //'status' => 'Status',
            //'about' => 'About',
        ];
    }
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message' => 'Не може бути пустим.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такий користувач вже зареєстрований.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Така адреса вже задіяна.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
