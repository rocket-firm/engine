<?php
namespace yii\helpers;

use yii\base\Exception;
use yii\web\UploadedFile;

class FileHelper extends BaseFileHelper
{
    public static $storageDir = 'media';

    public static function download($url, $path, $tryCount = 1)
    {
        $content = @file_get_contents($url);
        if ($content === false) {
            $tryCount--;
            if ($tryCount) {
                return self::download($url, $path, $tryCount);
            }
            return false;
        }
        file_put_contents($path, $content);
        return true;
    }

    public static function generateName($ext, $suffix = null)
    {
        if (!$suffix) {
            return time() . StringHelper::random(5, 5) . '.' . $ext;
        }
        return time() . StringHelper::random(5, 5) . '-' . $suffix . '.' . $ext;
    }

    public static function generateSubdir($path, $check = true)
    {
        if ($check && !is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $subdir = StringHelper::random(2, 2);
        //images with "ad" in path get blocked by adblocker
        if ($subdir == "ad") {
            $subdir = "/da";
        }
        if ($path && $check && !is_dir($path . '/' . $subdir)) {
            mkdir($path . '/' . $subdir);
        }
        return $subdir;
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param bool $subdir
     * @return string new name
     */
    public static function saveUploaded($file, $path, $subdir = true)
    {
        $path = static::getStoragePath(true, $path);
        if ($subdir) {
            $subdir = static::generateSubdir($path);
        } else {
            $subdir = '';
            if (!is_dir($path)) {
                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path));
                }
                mkdir($path);
            }
        }
        $newName = static::generateName($file->extension);
        $result = $subdir ? $subdir . '/' . $newName : $newName;
        $saved = $file->saveAs($path . '/' . $result);
        if (!$saved) {
            throw new \Exception('Could not save uploaded file: ' . $file->error . ' to path ' . $path . '/' . $result);
        }
        return $result;
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param bool $subdir
     * @return string new name
     */
    public static function saveFromUrl($url, $path, $subdir = true)
    {
        $path = static::getStoragePath(true, $path);
        if ($subdir) {
            $subdir = static::generateSubdir($path);
        } else {
            $subdir = '';
            if (!is_dir($path)) {
                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path));
                }
                mkdir($path);
            }
        }
        $rawExt = pathinfo($url, PATHINFO_EXTENSION);
        $extension = '';
        if ($rawExt) {
            $extension = $rawExt;
        }

        $newName = static::generateName($extension);

        $result = $subdir ? $subdir . '/' . $newName : $newName;

        $content = @file_get_contents($url);
        if ($content === false) {
            throw new Exception('Could not load file from url: ' . $url);
        }

        $saved = file_put_contents($path . '/' . $result, $content);

        if (!$saved) {
            throw new Exception('Could not save file from url:' . $path . '/' . $result);
        }

        return $result;
    }

    public static function getStoragePath($abs = false, $name = null)
    {
        if ($abs) {
            $path = \Yii::getAlias('@' . static::$storageDir);
        } else {
            $path = '/' . self::$storageDir;
        }
        if (!$name) {
            return $path;
        } else {
            return $path . '/' . trim($name, '/');
        }
    }

    public static function getImageSize($path, $makeAbs = false)
    {
        $fullPath = $makeAbs ? (\Yii::getAlias('@webroot') . $path) : $path;
        if (!file_exists($fullPath)) {
            return null;
        } else {
            $size = getimagesize($fullPath);
            return [$size[0], $size[1]];
        }
    }

    public static function rmdirContent($dir)
    {
        if (!$dh = opendir($dir)) {
            return;
        }
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }
            if (is_dir($dir . '/' . $obj)) {
                self::rmdirContent($dir . '/' . $obj, true);
            } else {
                unlink($dir . '/' . $obj);
            }
        }
        closedir($dh);
    }
}
