<?php

namespace app\models;

use app\components\CustomBehaviors;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string|null $date
 * @property string|null $author
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class News extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['status', 'created_at', 'author_id', 'updated_at', 'updated_by'], 'integer'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['title'], 'string', 'max' => 50],
        ];
    }

    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $res = [
                'status'  => 'error',
                'table'   => self::tableName() ?? '',
                'url'     => Url::current([], true),
                'message' => $this->getErrors(),
            ];
            Yii::error($res, 'save');
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->date = $this->date ?? date('Y-m-d');
                $this->status = $this->status ?? 1;
            }
            return true;
        }

        return false;
    }

    public function behaviors()
    {
        return [
            [
                'class' => CustomBehaviors::class,
            ],
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'title'      => Yii::t('app', 'Title'),
            'content'    => Yii::t('app', 'Content'),
            'date'       => Yii::t('app', 'Date'),
            'author'     => Yii::t('app', 'Author'),
            'status'     => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
}
