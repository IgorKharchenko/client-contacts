<?php

namespace app\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Class UploadFileForm
 * Форма для загрузки изображения с ПК.
 *
 * @package app\models
 */
class UploadPhotoForm extends Model
{
    /**
     * Файл изображения.
     *
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * Путь к загруженному изображению на сервере.
     *
     * @var string
     */
    public $imageUrl;

    /**
     * Правила валидации для модели.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            [
                'imageFile',
                'file',
                'skipOnEmpty' => false,
                'extensions'  => $this->getPossibleExtensions(),
                'maxSize'     => 1024 * 1024 * 15,
                'tooBig'      => 'Максимальный размер изображения 15 МБ.',
                'mimeTypes'   => 'image/*',
            ],
        ];
    }

    /**
     * Загрузка изображения с ПК и схоронение в директорию.
     *
     * @return bool удалось ли схоронить файл на сервере.
     *
     * @throws \yii\base\InvalidParamException если не заданы параметры валидации.
     */
    public function upload ()
    {
        if (!$this->validate()) {
            return false;
        }

        $baseName = $this->prepareBaseName($this->imageFile->baseName);

        $fileName = $baseName . '.' . $this->imageFile->extension;
        $serverImagePath = Url::to('@app/web/uploads/' . $fileName);

        $loadResult = $this->imageFile->saveAs($serverImagePath);
        $this->imageUrl = Url::to('@web/uploads/' . $fileName);

        return $loadResult;
    }

    /**
     * Удаляет файл, находящийся в папке uploads, с сервера.
     *
     * @return bool
     *
     * @throws InvalidArgumentException в случае, если файл на сервере не существует
     *                                  или он нечитаем.
     */
    public function delete ()
    {
        $fileName = $this->getFileNameFromUrl();
        $serverImagePath = Url::to('@frontend/web/uploads/' . $fileName);

        if (!is_file($serverImagePath) || !is_writable($serverImagePath)) {
            throw new InvalidArgumentException('The file does not exist or it\'s not writeable.');
        }

        return unlink($serverImagePath);
    }

    /**
     * Возвращает название файла из url-адреса.
     * Использовать $this->imageFile->name нет возможности,
     * потому что к названию в папке добавляется uniqid.
     *
     * @return string|null
     */
    public function getFileNameFromUrl ()
    {
        if (!$this->imageUrl) {
            return null;
        }

        $urlParts = explode('/', $this->imageUrl);
        return $urlParts[count($urlParts) - 1];
    }

    /**
     * Возможные расширения файлов.
     *
     * @return array
     */
    public function getPossibleExtensions ()
    {
        return ['png', 'jpg', 'jpeg'];
    }

    /**
     * Возвращает название файла из его пути.
     *
     * @param string $filePath путь до файла на сервере.
     *
     * @deprecated
     */
    protected function getFileNameFromPath ($filePath)
    {
        $fileNameParts = explode('\\', $filePath);

        return $fileNameParts[count($fileNameParts) - 1];
    }

    /**
     * Заменяет все пробелы и табуляции на нижние подчеркивания.
     *
     * @param $baseName
     *
     * @return string
     */
    protected function prepareBaseName ($baseName)
    {
        $out = $baseName;

        // заменяем пробелы, табуляции и прочую ересь на нижние подчеркивания
        $out = str_replace([
            ' ',
            "\s",
            "\t",
            '-',
        ], '_', $out);

        // добавляем случайный идентификатор через знак подчеркивания с большой энтропией,
        // внутри которого удаляем точку
        $out .= str_replace('.', '', uniqid('_', true));

        return $out;
    }
}
