<?php
namespace app\components\traits;

use app\components\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait Uploadable
{

    /**
     * @param UploadedFile $file
     * @param $attribute
     * @param bool $removeOld
     * @return string
     */
    public function saveFile(UploadedFile $file, $attribute, $removeOld = true)
    {
        if ($removeOld) {
            $this->deleteFile($attribute);
        }
        $newName = FileHelper::saveUploaded($file, $this->tableName());
        $this->$attribute = $newName;
        return $newName;
    }

    /**
     * @param string $attribute
     * @param bool $abs
     * @param string $mode
     * @return string filename or false if there's no image
     */
    public function getFilePath($attribute, $abs = false, $mode = null)
    {
        if (!$this->$attribute) {
            return false;
        }
        $path = $this->getStorageDir($abs) . '/';

        $pathinfo = pathinfo($this->$attribute);
        return $path . $pathinfo['dirname'] . "/" . $pathinfo['filename'] . $mode . "." . $pathinfo['extension'];
    }

    public function deleteFile($attribute, $mode = null)
    {
        $path = $this->getFilePath($attribute, true, $mode);
        if ($path && file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param bool $abs
     * @return string path
     */
    public function getStorageDir($abs = false)
    {
        return FileHelper::getStoragePath($abs, $this->tableName());
    }
}
