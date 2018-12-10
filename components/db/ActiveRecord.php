<?php

namespace app\components\db;

use app\components\db\exceptions\ModelNotFoundException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;
use yii\db\ActiveRecordInterface;

/**
 * Class ActiveRecord
 *
 * @package common\components\db
 */
class ActiveRecord extends BaseActiveRecord
{
    const DB_DATE_FORMAT = 'Y-m-d';
    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Подключаем поведения к базовой модели.
     *
     * @return array
     */
    public function behaviors ()
    {
        $behaviors = parent::behaviors();
        $behaviors['TimestampBehavior'] = [
            'class'              => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value'              => function() { return date(static::DB_DATETIME_FORMAT); },
        ];
        return $behaviors;
    }

    /**
     * Производит поиск модели по заданному условию. В случае, если модель не найдена выбрасывается
     * исключение.
     *
     * @param mixed $condition первичный ключ или массив сотвествий поле => значение.
     *
     * @see ActiveRecordInterface::findOne()
     * @throws ModelNotFoundException в случае, если модель не найдена.
     *
     * @return static
     */
    public static function findOneOrFail ($condition)
    {
        $activeRecord = static::findOne($condition);
        if (null === $activeRecord) {
            throw  new ModelNotFoundException('Model not found');
        }

        return $activeRecord;
    }
}
