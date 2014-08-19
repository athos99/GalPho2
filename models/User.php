<?php
namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\web\IdentityInterface;

class User extends UserBase implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;

    public function init()
    {
        parent::init();
        $this->on(ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'eventSave']);
        $this->on(ActiveRecord::EVENT_AFTER_INSERT, [$this, 'eventSave']);
    }

    public function eventSave()
    {
        foreach ($this->userFieldsByField as $userField) {
            $userField->save();
        }
    }


    public function attributes()
    {
        $attributes = parent::attributes();
//        $attributes[] = 'display_name';
        return $attributes;
    }


    public function getdisplay_name()
    {
        return isset($this->userFieldsByField['display_name']) ? $this->userFieldsByField['display_name']->value : null;
    }

    public function setdisplay_name($value)
    {
        if (isset($this->userFieldsByField['display_name'])) {
            $rec = $this->userFieldsByField['display_name'];
            $rec->value = $value;
        } else {
            if ($value !== '') {
                $rec = new UserField();
                $rec->field = 'display_name';
                $rec->user_id = $this->id;
                $rec->value = $value;
                $rec->save();
            }
        }
    }


    public function query($search)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!$search) {
            return $dataProvider;
        }
        $query->orWhere(['like', 'username', $search]);
        $query->orWhere(['like', 'email', $search]);
        return $dataProvider;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['display_name'], 'string', 'max' => 64, 'min' => 2];
        $rules[] = [['permanent'], 'default', 'value' => 0];
        $rules[] = [['username', 'email', 'display_name'], 'trim'];
        $rules[] = [['username'], 'string', 'min' => 3];
        $rules[] = [['email'], 'required'];
        $rules[] = [['email'], 'email'];
        return $rules;

    }


    public function attributeLabels()
    {
        $attributs = parent::attributeLabels();
        $attributs['display_name'] = 'Display name';
        return $attributs;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFieldsByField()
    {
        return $this->getUserFields()->indexBy('field');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /* @var UserAuthenticateBase $UserAuthenticate */
        $UserAuthenticate = UserAuthenticateBase::findOne(['identifier' => $token, 'provider' => $type]);
        return $UserAuthenticate->user;
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        /** @var \yii\base\Security $security */
        $security = Yii::$app->getSecurity();
        return $security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        /** @var \yii\base\Security $security */
        $security = Yii::$app->getSecurity();
        $this->password_hash = $security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        /** @var \yii\base\Security $security */
        $security = Yii::$app->getSecurity();
        $this->auth_key = $security->generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        /** @var \yii\base\Security $security */
        $security = Yii::$app->getSecurity();
        $this->password_reset_token = $security->generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
