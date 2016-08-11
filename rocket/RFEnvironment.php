<?php
namespace rocketfirm\engine\rocket;

class RFEnvironment extends \janisto\environment\Environment
{
    public $mainConfigName;

    /**
     * Load and merge configuration files into one array.
     *
     * @return array $config array to be processed by setEnvironment
     * @throws \Exception
     */
    protected function getConfig()
    {
        $configMerged = [];
        foreach ($this->configDir as $configDir) {
            // Merge main config.
            $fileMainConfig = $configDir . 'main.php';
            if (!file_exists($fileMainConfig)) {
                throw new \Exception('Cannot find main config file "' . $fileMainConfig . '".');
            }
            $configMain = require($fileMainConfig);
            if (is_array($configMain)) {
                $configMerged = self::merge($configMerged, $configMain);
            }

            // Merge mode specific config.
            $fileSpecificConfig = $configDir . 'mode_' . $this->mode . '.php';
            if (!file_exists($fileSpecificConfig)) {
                throw new \Exception('Cannot find mode specific config file "' . $fileSpecificConfig . '".');
            }
            $configSpecific = require($fileSpecificConfig);
            if (is_array($configSpecific)) {
                $configMerged = self::merge($configMerged, $configSpecific);
            }

            if (getenv('YII_END') == 'admin') {
                // If one exists, merge local config.
                $fileLocalConfig = $configDir . 'admin.php';
                if (file_exists($fileLocalConfig)) {
                    $configLocal = require($fileLocalConfig);
                    if (is_array($configLocal)) {
                        $configMerged = self::merge($configMerged, $configLocal);
                    }
                }
            } else {
                // If one exists, merge local config.
                $fileLocalConfig = $configDir . 'front.php';
                if (file_exists($fileLocalConfig)) {
                    $configLocal = require($fileLocalConfig);
                    if (is_array($configLocal)) {
                        $configMerged = self::merge($configMerged, $configLocal);
                    }
                }
            }

            // If one exists, merge local config.
            $fileLocalConfig = $configDir . 'local.php';
            if (file_exists($fileLocalConfig)) {
                $configLocal = require($fileLocalConfig);
                if (is_array($configLocal)) {
                    $configMerged = self::merge($configMerged, $configLocal);
                }
            }
        }
        return $configMerged;
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
}
