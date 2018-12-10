<?php

namespace app\components\view\dropDownList;

class ArrayFormatter
{
    /**
     * Делает вот это:
     * ['Доставка', 'Ремонт', 'Чистка'...]
     * =>
     * [
     *     'Доставка' => 'Доставка',
     *     'Ремонт'   => 'Ремонт',
     *     'Чистка'   => 'Чистка',
     *     ...
     * ]
     *
     * @param array $items итемы в виде ['Итем', 'Итем'...]
     *
     * @return array
     */
    public static function formatArray (array $items) : array
    {
        $out = [];
        foreach ($items as $item) {
            $out[$item] = $item;
        }

        return $out;
    }
}