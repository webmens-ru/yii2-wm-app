<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'users';
    }
//    public function tableName() {
//        return 'users';
//    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['b24_user_id'], 'integer'],
            [['name', 'last_name', 'access_token', 'auth_key'], 'string', 'max' => 255],
            [['date_expired'], 'safe'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Этот логин уже занят'],
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function findByBitrixId($id)
    {
//        Yii::warning($id, '$id');
//        Yii::warning(self::findOne(['b24_user_id' => $id]), 'user');
        return self::findOne(['b24_user_id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return $this->password === $password;
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
$timestamp = time() + 3600 * (11);
        //echo $timestamp;
        $datetimeFormat = 'Y-m-d H:i:s';
        $date = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $date->setTimestamp($timestamp);
        $this->date_expired = $date->format($datetimeFormat);
        Yii::warning($this->date_expired, '');
//------------------------------------

        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->auth_key = $this->access_token;
    }

//    public function generateAccessTokenTest() {        
//        Yii::warning(date( "Y-m-d h:i:s", strtotime( date("Y-m-d h:i:s")."+1 hours")), 'date');
//    }

    public function getAccessToken()
    {
        if (!$this->access_token || $this->date_expired < date('Y-m-d h:i:s')) {
            $this->generateAccessToken();
        }

        return $this->access_token;
    }

    public static function generatePassword($length = 10)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
//    public static function b24Login($b24Id) {
////        if ($this->validate()) {
////            if ($this->rememberMe) {
//                $user = static::findByBitrixId($b24Id);
//                $user->generateAccessToken();
//                $user->save();
////            }
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
////        }
//        return false;
//    }
}
