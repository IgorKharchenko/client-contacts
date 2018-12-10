<?php

namespace app\components\migration;

use yii\helpers\ArrayHelper;

/**
 * Class Migration
 * Класс, расширяющий функционал миграций.
 *
 * @package app\components\migrations
 */
class Migration extends \yii\db\Migration
{
    /**
     * Создает таблицу которые содержат метки времения created_at и updated_at.
     *
     * @param string $table   имя создаваемой таблицы.
     * @param array  $columns массив, содержащий имя и тип колонок.
     * @param null   $options дополнительные SQL параметры, которые будут добавлены к
     *                        сгенерированному запросу.
     *
     * @return void
     */
    public function createTableWithTimestamps ($table, array $columns, $options = null)
    {
        $columns = ArrayHelper::merge($columns, [
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
        $this->createTable($table, $columns, $options);
    }

    /**
     * Создаёт SQL код для добавления индекса. Автоматически генерирует имя индекса.
     *
     * @param string       $table   the table that the new index will be created for. The table
     *                              name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. If there
     *                              are multiple columns, please separate them by commas or use an
     *                              array. Each column name will be properly quoted by the method.
     *                              Quoting will be skipped for column names that include a left
     *                              parenthesis "(".
     * @param bool         $unique  whether to add UNIQUE constraint on the created index.
     */
    public function createIndexAuto ($table, $columns, $unique = false)
    {
        $indexName = $this->generateIndexName($table, $columns);
        $this->createIndex($indexName, $table, $columns, $unique);
    }

    /**
     * Генерирует имя индекса.
     *
     * @param string       $table      the table that the foreign key constraint will be added to.
     * @param string|array $columns    the name of the column to that the constraint will be added
     *                                 on. If there are multiple columns, separate them with commas
     *                                 or use an array.
     *
     * @return string
     */
    public function generateIndexName ($table, $columns)
    {
        $fk = 'idx_';
        $fk .= $table;
        $fk .= (is_array($columns)) ? '-' . $columns[0] : '-' . $columns;
        return $fk;
    }

    /**
     * Генерирует SQL код для добавления foreign key constraint. Автоматически генерирует имя
     * констрейнта.
     *
     * @param string       $table      the table that the foreign key constraint will be added to.
     * @param string|array $columns    the name of the column to that the constraint will be added
     *                                 on. If there are multiple columns, separate them with commas
     *                                 or use an array.
     * @param string       $refTable   the table that the foreign key references to.
     * @param string|array $refColumns the name of the column that the foreign key references to.
     *                                 If there are multiple columns, separate them with commas or
     *                                 use an array.
     * @param string       $delete     the ON DELETE option. Most DBMS support these options:
     *                                 RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     * @param string       $update     the ON UPDATE option. Most DBMS support these options:
     *                                 RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     *
     * @return void
     */
    public function addForeignKeyAuto ($table, $columns, $refTable, $refColumns, $delete = 'CASCADE',
                                       $update = 'CASCADE')
    {
        $name = $this->generateFkName($table, $columns);
        $this->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
    }

    /**
     * Генерирует имя внешнего ключа.
     *
     * @param string       $table      the table that the foreign key constraint will be added to.
     * @param string|array $columns    the name of the column to that the constraint will be added
     *                                 on. If there are multiple columns, separate them with commas
     *                                 or use an array.
     *
     * @return string
     */
    public function generateFkName ($table, $columns)
    {
        $fk = 'FK_';
        $fk .= $table;
        $fk .= (is_array($columns)) ? '-' . $columns[0] : '-' . $columns;
        return $fk;
    }

    /**
     * Добавляет несколько колонок к таблице.
     *
     * @param string $table   имя таблицы.
     * @param array  $columns массив с колонками, где ключ - имя колонки, значение -тип колонки.
     *
     * @return $this
     */
    public function addColumns ($table, array $columns)
    {
        foreach ($columns as $columnName => $columnType) {
            $this->addColumn($table, $columnName, $columnType);
        }

        return $this;
    }

    /**
     * Удаляет несколько колонок из указанной таблицы.
     *
     * @param string $table   имя таблицы из которой необходимо удалить колонки.
     * @param array  $columns массив с именами колонок.
     *
     * @return $this
     */
    public function dropColumns ($table, array $columns)
    {
        foreach ($columns as $columnName) {
            $this->dropColumns($table, $columnName);
        }

        return $this;
    }
}
