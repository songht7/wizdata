<?php

namespace CommonDAL;

/*
 * 基本数据类包
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class BaseDAL {

    //表名
    public $tab_name;
    //创建连接
    public $conn;

    //默认方法
    function __construct() {
        include("iso_databaseSitting.php");
        $this->tab_name = $table_pre;
        $this->conn = mysqli_connect($host, $user, $password, $dbName, $port);
        if (mysqli_connect_errno($this->conn)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        mysqli_query($this->conn, 'set names utf8');
    }

    /*
     * 获取列表
     */

    public function getFetchAll($sql) {
        $result = $this->query($sql);
        if (!empty($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        if (!isset($data)) {
            return false;
        } else {
            return $data;
        }
    }

    /*
     * 获取单个
     */

    public function getFetchRow($sql) {
        $result = $this->query($sql);
        if (!empty($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data = $row;
            }
        }
        if (!isset($data)) {
            return false;
        } else {
            return $data;
        }
    }

    /*
     * 执行sql
     */

    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    
    /*
     * get last id
     */
    public function get_last_id() {
        $result = mysqli_insert_id($this->conn);
        return $result;
    }

    //表名处理
    function table_name($name) {
        $ls = $this->tab_name . $name;
        return $ls;
    }
/* 排序分类树 */
    function madeTree($spec_cat_id, $arr, $id_name, $name_name) {
        static $categorys_options = array();

        if (isset($categorys_options[$spec_cat_id])) {
            return $categorys_options[$spec_cat_id];
        }
        if (!isset($categorys_options[0])) {
            $level = $last_cat_id = 0;
            $options = $cat_id_array = $level_array = array();

            while (!empty($arr)) {

                foreach ($arr AS $key => $value) {
                    $cat_id = $value[$id_name];
                    if ($level === 0 && $last_cat_id === 0) {
                        if ($value['parent_id'] > 0) {
                            break;
                        }

                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value[$name_name];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0) {
                            continue;
                        }
                        $last_cat_id = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] === $last_cat_id) {
                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value[$name_name];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0) {
                            if (end($cat_id_array) != $last_cat_id) {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    } else if ($value['parent_id'] > $last_cat_id) {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1) {
                    $last_cat_id = array_pop($cat_id_array);
                } else if ($count == 1) {
                    if ($last_cat_id != end($cat_id_array)) {
                        $last_cat_id = end($cat_id_array);
                    } else {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id])) {
                    $level = $level_array[$last_cat_id];
                } else {
                    $level = 0;
                }
            }

            $categorys_options[0] = $options;
        } else {
            $options = $categorys_options[0];
        }

        if (!$spec_cat_id) {
            return $options;
        } else {
            if (empty($options[$spec_cat_id])) {
                return array();
            }

            $spec_cat_id_level = $options[$spec_cat_id]['level'];

            foreach ($options AS $key => $value) {
                if ($key != $spec_cat_id) {
                    unset($options[$key]);
                } else {
                    break;
                }
            }

            $spec_cat_id_array = array();
            foreach ($options AS $key => $value) {
                if (($spec_cat_id_level == $value['level'] && $value[$id_name] != $spec_cat_id) ||
                        ($spec_cat_id_level > $value['level'])) {
                    break;
                } else {
                    $spec_cat_id_array[$key] = $value;
                }
            }
            $categorys_options[$spec_cat_id] = $spec_cat_id_array;

            return $spec_cat_id_array;
        }
    }
}
