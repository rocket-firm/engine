<?php
namespace app\components\traits;

use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 *
 */
trait UploadableAsync
{
    use Uploadable;

    public static $asyncTempDir = 'temp';

    protected $uploaded1sAsync = [];

    public function getTempMediaDirectory($abs = false)
    {
        $path = $this->getStorageDir($abs);
        return $path . '/' . self::$asyncTempDir;
    }

    public function getAsyncFileFromSession($name)
    {
        $data = \Yii::$app->session->get($this->tableName());
        if (!$name)
            return $data ? $data['files'] : null;
        if (!$data || !isset($data['files']) || !isset($data['files'][$name]))
            return null;
        return $data['files'][$name];
    }

    public function addAsyncFileToSession($name, $file, $index = false)
    {
        $data = \Yii::$app->session->get($this->tableName());
        if (!$data)
            $data = ['files' => [$name => []]];
        else if (!isset($data['files']))
            $data['files'] = [$name => []];
        else if (!isset($data['files'][$name]) || !is_array($data['files'][$name]))
            $data['files'][$name] = [];
        if ($index) {
            $data['files'][$name][$index] = $file;
        } else {
            $data['files'][$name][] = $file;
        }
        \Yii::$app->session->set($this->tableName(), $data);
    }

    public function removeAsyncFileFromSession($name, $index = null)
    {
        $data = \Yii::$app->session->get($this->tableName());

        if (!$data || !isset($data['files']) || !isset($data['files'][$name]))
            return;
        if ($index) {
            unset($data['files'][$name][$index]);
        } else {
            unset($data['files'][$name]);
        }
        \Yii::$app->session->set($this->tableName(), $data);
    }

    public function getAsyncTempFiles($attributeName, $abs = true, $ensure = false)
    {
        $file = $this->getAsyncFileFromSession($attributeName);

        if (!$file)
            return false;
        $result = [];
        foreach ($file as $i => $value) {
            $file = $this->getAsyncTempFile($attributeName, $abs, $ensure, $i);
            if ($file)
                $result[$i] = $file;
        }
        return $result;
    }


    public function getAsyncTempFile($attributeName, $abs = false, $ensure = false, $index = null, $remove = true)
    {
        $data = $this->getAsyncFileFromSession($attributeName);
        if (!$data)
            return false;
        if (!$index) {
            $file = reset($data);
        } else {
            $file = $data[$index];
        }
        $path = $this->getTempMediaDirectory($abs) . '/' . $file;
        $fullPath = $this->getTempMediaDirectory(true) . '/' . $file;
        if (!$ensure || file_exists($fullPath))
            return $path;
        if ($remove)
            $this->removeAsyncTempFile($attributeName, true, $index);

        return false;
    }

    public function hasAsyncTempFile($attributeName)
    {
        return (bool)$this->getAsyncTempFiles($attributeName, false, true);
    }

    public function validateAndSaveAsyncFile($attribute, $saveToSession = true, $removeOld = true)
    {
        if (!($this->{$attribute} instanceof UploadedFile)) {
            $this->$attribute = UploadedFile::getInstance($this, $attribute);
        }
        if (!$this->$attribute || $this->$attribute->hasError)
            return false;
        $valid = $this->validate([$attribute]);
        if (!$valid)
            return false;
        $path = $this->saveAsyncTempFile($attribute, $saveToSession, $removeOld);
        return $path;
    }

    public function saveAsyncTempFile($attributeName, $saveToSession = true, $removeOld = true)
    {
        /**
         * @var $file UploadedFile
         */
        $file = $this->$attributeName;
        $path = $this->tableName() . '/' . self::$asyncTempDir;
        $name = FileHelper::saveUploaded($file, $path, false);
        if ($saveToSession) {
            if ($removeOld)
                $this->removeAsyncTempFile($attributeName);
            $this->addAsyncFileToSession($attributeName, $name);
        }
        return $name;
    }

    public function saveAsyncFile($fileAttribute, $attribute, $removeFromSession = true, $removeOld = true)
    {
        $files = $this->getAsyncFileFromSession($fileAttribute);
        if (!$files)
            throw new \Exception('No such file: ' . $fileAttribute);
        $name = reset($files);
        $result = $this->saveAsyncFileByName($name);
        if ($removeFromSession)
            $this->removeAsyncFileFromSession($fileAttribute);
        if ($this->$attribute && $removeOld)
            $this->deleteFile($attribute);
        $this->$attribute = $result;
        return $result;
    }

    public function saveAsyncFiles($attributeName, $removeFromSession = true)
    {
        $files = $this->getAsyncFileFromSession($attributeName);
        if (!$files)
            throw new \Exception('No such files: ' . $attributeName);
        $result = [];
        foreach ($files as $i => $name) {
            $path = $this->saveAsyncFileByName($name);
            $result[$path] = $name;
        }
        if ($removeFromSession)
            $this->removeAsyncFileFromSession($attributeName);
        return $result;
    }

    public function saveAsyncFileByName($name)
    {
        $path = $this->getStorageDir(true);
        $subDir = FileHelper::generateSubdir($path);
        copy($this->getTempMediaDirectory(true) . '/' . $name, $path . '/' . $subDir . "/" . $name);
        unlink($this->getTempMediaDirectory(true) . '/' . $name);
        return $subDir . "/" . $name;
    }

    public function removeAsyncTempFile($name, $removeFromSession = true, $index = null)
    {
        $path = $this->getAsyncTempFile(true, $name, false, $index);
        if (file_exists($path))
            unlink($path);
        if ($removeFromSession) {
            $this->removeAsyncFileFromSession($name, $index);
        }
    }

    public function removeAsyncTempFiles($removeFromSession = true)
    {
        $files = $this->getAsyncFileFromSession(null);
        if (!$files)
            return;
        foreach ($files as $attr => $file) {
            $this->removeAsyncTempFile($attr, $removeFromSession);
        }
    }

    public function validateRequiredAsyncFile($attribute)
    {
        if (!$this->isNewRecord)
            return;
        if ($this->$attribute)
            return;
        $file = $this->getAsyncTempFile($attribute);
        if (!$file) {
            $this->addError($attribute, 'Необходимо загрузить файл');
        }
    }
}
