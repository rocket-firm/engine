<?php
namespace rocketfirm\engine\rocket;

class RFEnvironment extends \janisto\environment\Environment
{
    /**
     * @var array Сшитая конфигурация приложения
     */
    private $configMerged = [];

    /**
     * @var string
     */
    public $defaultEndpoint = 'front';

    /**
     * Load and merge configuration files into one array.
     *
     * @return array $config array to be processed by setEnvironment
     * @throws \Exception
     */
    protected function getConfig()
    {
        foreach ($this->configDir as $configDir) {
            // Merge main config.
            $this->pushConfig($this->getConfigFile($configDir . 'main.php'));

            // Merge mode specific config.
            $this->pushConfig($this->getConfigFile($configDir . 'mode_' . $this->mode . '.php'));

            // Получаем конфигурацию для конкретного входного файла, который задает переменную окружения YII_END
            $yiiEnd = $this->defaultEndpoint;
            if (!empty(getenv('YII_END'))) {
                $yiiEnd = getenv('YII_END');
            }

            $this->pushConfig($this->getConfigFile($configDir . $yiiEnd . '.php'));

            // If one exists, merge local config.
            $this->pushConfig($this->getConfigFile($configDir . 'local.php', false));
        }
        return $this->configMerged;
    }

    /**
     * Возвращает массив настроек
     *
     * @param string $filePath Путь до конфигурационного файла
     * @param bool $strict Выдавать исключения для заданного файла при ошибке чтения или парсинга
     * @return array
     * @throws \Exception
     */
    private function getConfigFile($filePath, $strict = true)
    {
        if (file_exists($filePath)) {
            $configArray = require($filePath);

            if (is_array($configArray)) {
                return $configArray;
            } elseif (!$strict) {
                return [];
            }

            throw new \Exception('Конфигурационный файл ' . $filePath . ' не возвращает массив');
        } elseif(!$strict) {
            return [];
        }

        throw new \Exception('Конфигурационный файл ' . $filePath . ' не найден');
    }

    /**
     * @param array $configArray
     */
    private function pushConfig($configArray)
    {
        $this->configMerged = self::merge($this->configMerged, $configArray);
    }

    /**
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will
     * be appended to the former array.
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional
     * arrays via third argument, fourth argument etc.
     * @return array the merged array (the original arrays are not changed.)
     */
    protected static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_integer($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    /**
     * Defines Yii constants, includes base Yii class, sets aliases and merges class map.
     */
    public function setup()
    {
        /**
         * This constant defines whether the application should be in debug mode or not.
         */
        defined('YII_DEBUG') or define('YII_DEBUG', $this->yiiDebug);
        /**
         * This constant defines in which environment the application is running.
         * The value could be 'prod' (production), 'stage' (staging), 'test' (testing) or 'dev' (development).
         */
        defined('YII_ENV') or define('YII_ENV', $this->yiiEnv);
        /**
         * Whether the the application is running in staging environment.
         */
        defined('YII_ENV_STAGE') or define('YII_ENV_STAGE', YII_ENV === 'stage');

        // Include Yii.
        require_once($this->yiiPath);

        // Set aliases.
        foreach ($this->aliases as $alias => $path) {
            \Yii::setAlias($alias, $path);
        }

        // Merge class map.
        if (!empty($this->classMap)) {
            \Yii::$classMap = static::merge(\Yii::$classMap, $this->classMap);
        }
    }
}
