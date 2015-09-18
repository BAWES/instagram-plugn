<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $subcategory_id
 * @property string $project_title
 * @property string $project_image
 * @property string $project_duration
 * @property string $project_description
 *
 * @property User $user
 * @property Subcategory $subcategory
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'subcategory_id', 'project_description'], 'required'],
            [['user_id', 'subcategory_id'], 'integer'],
            [['project_description'], 'string'],
            [['project_title', 'project_image', 'project_duration'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'user_id' => 'User ID',
            'subcategory_id' => 'Subcategory ID',
            'project_title' => 'Project Title',
            'project_image' => 'Project Image',
            'project_duration' => 'Project Duration',
            'project_description' => 'Project Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategory()
    {
        return $this->hasOne(Subcategory::className(), ['subcategory_id' => 'subcategory_id']);
    }
}
