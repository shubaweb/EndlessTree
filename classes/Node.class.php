<?php
class Node
{

    /**
     * Node constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function getLevel($id)
    {
        $sql = 'SELECT level FROM categories WHERE id = :id';
        $args = array(':id' => $id);
        $stmt = DB::execQuery($sql, $args);
        return $stmt->fetch()[level];
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function getParentId($id)
    {
        $sql = 'SELECT parent_id FROM categories WHERE id = :id';
        $args = array(':id' => $id);
        $stmt = DB::execQuery($sql, $args);
        return $stmt->fetch()[parent_id];
    }

    /**
     * @param $node_tree
     * @param int $parent_id
     * @return string
     */
    private static function buildTree($node_tree, $parent_id=0){
        $tree = '<ul>';
        foreach($node_tree[$parent_id] as $cat){
            $tree .= '<li id="'.$cat['id'].'">'.$cat['name'];
            $tree .=  self::buildTree($node_tree, $cat['id']);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
        return $tree;
    }

    /**
     * @return mixed
     */
    public static function showTree()
    {
        $stmt = DB::execQuery('SELECT * FROM categories');
        while ($row = $stmt->fetch()){
            $node_tree[$row[parent_id]][$row[id]] = $row;
        }
        return self::buildTree($node_tree);
    }

    /**
     * @param $name
     * @param $parent_id
     * @param level $
     */
    public static function addNode($name, $parent_id, $level)
    {
        $level = self::getLevel($parent_id) + 1;
        $sql = 'INSERT INTO categories (name, parent_id, level) VALUES (:name, :parent_id, :level)';
        $args = array(':name' => $name, ':parent_id' => $parent_id, ':level' => $level);
        DB::execQuery($sql, $args);
        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getChildNodes($id=0)
    {
        $sql = 'SELECT id, name FROM categories WHERE parent_id = :id';
        $args = array(':id' => $id);
        $stmt = DB::execQuery($sql, $args);
        while ($row = $stmt->fetch()) {
            $option[$row[id]] = $row[name];
        }
        return $option;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getBranch($id)
    {
        $parent_id = self::getParentId($id);
        $level = self::getLevel($id);
        for ($i=$level; $i>=0; $i--) {
            $branch[$i] = self::getChildNodes($parent_id);
            $parent_id = self::getParentId($parent_id);
        }
        unset ($branch[$level][$id]);
        $branch = array_reverse ($branch, true);
        return $branch;
    }
}