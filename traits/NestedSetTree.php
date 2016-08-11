<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/23/14
 * Time: 17:27
 */

namespace rocketfirm\engine\traits;


// TODO: get rid of straight Language module dependency and use DI
use rocketfirm\engine\modules\languages\models\Languages;
use yii\helpers\ArrayHelper;

trait NestedSetTree
{
    public static function getTreeDropdownList($withRoot = true)
    {

        $treeData = self::find()->addOrderBy('lft')->andWhere(['lang_id' => Languages::getAdminCurrent()->id]);

        $treeData = $treeData->all();

        $treeForest = self::dbResultToForest($treeData, 'id', 'parent_id', 'title');
        $categoryDataSelect = self::converTreeArrayToSelect($treeForest, 0, $withRoot);

        return ArrayHelper::map($categoryDataSelect, 'id', 'name');
    }

    /**
     * Build heriarhal result from DB Query result.
     * db result must conist id, parent_id, value
     *
     * @param Object $rows
     * @param string $idName name of id key in result query
     * @param string $pidName name of parent id for query result
     * @param string $labelName name of value field in query result
     * @return array heriarhical tree
     */
    public static function dbResultToForest($rows, $idName, $pidName, $labelName = 'label')
    {
        $totalArray = [];
        $children = []; // children of each ID
        $ids = [];
        $k = 0;
        // Collect who are children of whom.
        foreach ($rows as $i => $r) {
            $element = [
                'id' => $rows[$i][$idName],
                'parent_id' => $rows[$i][$pidName],
                'value' => $rows[$i][$labelName]
            ];
            $totalArray[$k++] = $element;
            $row = &$totalArray[$k - 1];
            $id = $row['id'];
            if ($id === null) {
                // Rows without an ID are totally invalid and makes the result tree to
                // be empty (because PARENT_ID = null means "a root of the tree"). So
                // skip them totally.
                continue;
            }
            $pid = $row['parent_id'];
            if ($id == $pid) {
                $pid = null;
            }
            $children[$pid][$id] =& $row;
            if (!isset($children[$id])) {
                $children[$id] = [];
            }
            $row['childNodes'] = &$children[$id];
            $ids[$id] = true;
        }

        // Root elements are elements with non-found PIDs.
        $forest = [];
        foreach ($totalArray as $i => $r) {
            $row = &$totalArray[$i];
            $id = $row['id'];
            $pid = $row['parent_id'];
            if ($pid == $id) {
                $pid = null;
            }
            if (!isset($ids[$pid])) {
                $forest[$row[$idName]] =& $row;
            }
        }

        return $forest;
    }

    /**
     * Recursive function converting tree like array to single array with
     * delimiter. Such type of array used for generate drop down box
     *
     * @param array $data data of tree like
     * @param int $level current level of recursive function
     * @return array converted array
     */
    public static function converTreeArrayToSelect($data, $level = 0, $withRoot = false)
    {
        if ($withRoot) {
            $returnArray[0] = array('name' => 'Корневой элемент', 'id' => '00');
        }

        foreach ($data as $item) {
            $subitems = [];
            $elementName = "|" . str_repeat("--", $level * 2) . " " . $item['value'];
            $returnItem = array('name' => $elementName, 'id' => $item['id']);
            if ($item['childNodes']) {
                $subitems = self::converTreeArrayToSelect($item['childNodes'], $level + 1);
            }

            $returnArray[] = $returnItem;

            if ($subitems != []) {
                $returnArray = array_merge($returnArray, $subitems);
            }

        }

        return empty($returnArray) ? [] : $returnArray;
    }
}
