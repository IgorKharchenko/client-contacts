<?php

namespace app\components\test;

use yii\base\Model;

trait UnitTestHelper
{
    /**
     * Валидирует созданную модель.
     *
     * @param Model $model
     * @param bool  $mustBeValid       должен ли валидатор возвращать true или false.
     * @param array $invalidAttributes если есть, то проверяет, что ошибка для каждого атрибута
     *                                 установлена.
     *
     * @return void
     */
    public function validateModel (Model $model, bool $mustBeValid, array $invalidAttributes = [])
    {
        if ($mustBeValid) {
            $this->assertTrue($model->validate(), 'validation must pass');
            $this->assertEmpty($model->errors, 'error array MUST be empty');
        } else {
            $this->assertFalse($model->validate(), 'validation must fail');
            $this->assertNotEmpty($model->errors, 'error array MUST NOT be empty');
        }

        if (!empty($invalidAttributes)) {
            foreach ($invalidAttributes as $attribute) {
                $this->assertNotEmpty($model->getErrors($attribute));
            }
        }
    }
}