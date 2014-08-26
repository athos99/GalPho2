<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;


class GalGroup extends GalGroupBase
{
    public function query($search) {
        $query = galGroup::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!$search){
            return $dataProvider;
        }
        $query->orWhere(['like', 'name', $search]);
        $query->orWhere(['like', 'description', $search]);
        return $dataProvider;
    }
    public function rules()
    {
        return [
            [['permanent'], 'integer'],
            [['permanent'], 'default','value'=>0],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 128, 'min'=>2],
            [['name'], 'required', 'except'=>'search'],
            [['name'], 'match', 'pattern' => '/^\*/i', 'not' => true, 'message' => Yii::t('app','ne doit pas commencer par un *')],
            [['name','description'],'trim'],
            [['description','permanent','name'], 'safe', 'on'=>'search'],
        ];
    }

    public function search($params)
    {
        $query = galGroup::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'id');
        $this->addCondition($query, 'permanent');
        $this->addCondition($query, 'name', true);
        $this->addCondition($query, 'description', true);
        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
    function saveUser( $users) {
        GalGroupUser::deleteAll(['group_id'=>$this->id]);
        foreach( $users as $userId) {
            $galGroupUser = new GalGroupUser();
            $galGroupUser->group_id = $this->id;
            $galGroupUser->user_id = $userId;
            $galGroupUser->save();
        }
    }
}
