<?php

namespace AliceDAL;

class Category extends \CommonDAL\BaseDAL {

    function __construct() {
        parent::__construct();
        include_once './model/ImgDAL.php';
    }

//get list
    function getList($i8n, $currentpage, $pagesize) {

        $sql = "select count(1) as count from " . $this->table_name('category') . " as c left join " . $this->table_name('category_i8n') . " as i on c.cat_id=i.cat_id "
                . " where i.i8n='" . $i8n . "';";
        $count = $this->getFetchRow($sql);
        $orderby = " order by c.cat_id desc ";
        $limit = " limit " . (($currentpage - 1) * $pagesize) . "," . $pagesize . " ";
        $sql = "select * from " . $this->table_name('category') . " as c left join " . $this->table_name('category_i8n') . " as i on c.cat_id=i.cat_id "
                . " where i.i8n='" . $i8n . "' "
                . " " . $orderby . " " . $limit . ";";
        $product = $this->getFetchAll($sql);
        return array("list" => $product, "count" => $count['count']);
    }

//detail 
    function getCategoryDetail($id) {
        $sql = "select * from " . $this->table_name('category') . " where cat_id='{$id}'";
        $product = $this->getFetchRow($sql);
        return $product;
    }

//i8n
    function getCategoryDetailI8n($id) {
        $sql = "select * from " . $this->table_name('category_i8n') . " where cat_id='{$id}' order by i8n asc";
        $products = $this->getFetchAll($sql);
        $pro = array();
        if (!empty($products)) {
            foreach ($products as $v) {
                //$v['detail_arr'] = explode('":;"', $v['cat_detail']);
                $pro[$v['i8n']] = $v;
            }
        }
        return $pro;
    }

//img
    function getCategoryDetailImg($id) {
        $sql = "select * from " . $this->table_name('img') . " where type_id='{$id}' and type='C' order by i8n asc";
        $img = $this->getFetchAll($sql);
        $imgs = array();
        if (!empty($img)) {
            foreach ($img as $v) {
                $imgs[$v['img_id'] . '-' . $v['i8n']] = $v;
            }
        }
        return $imgs;
    }

    //insert category
    function addCategory($model) {
        $sql = "insert into " . $this->table_name('category') . "(parent_id,type,is_show,order_by,add_by ,add_time,edit_by) "
                . "values('" . $model['parent_id'] . "','" . $model['type'] . "','" . $model['is_show'] . "','" . $model['order_by'] . "','" . $model['h_id'] . "',NOW(),'" . $model['h_id'] . "')";
        $this->query($sql);
        $categoryid = mysql_insert_id();

        foreach ($model['i8n'] as $k => $v) {
            $sql = "insert into " . $this->table_name('category_i8n') . "(cat_id,cat_name,cat_overview,cat_detail,i8n) "
                    . "values('" . $categoryid . "','" . $v['cat_name'] . "','" . $v['overview'] . "','" . $v['detail'] . "','" . $v['i8n'] . "')";
            $this->query($sql);
        }

        if (!empty($model['img'])) {
            $Img = new \AliceDAL\Img('C');
            $Img->addImg($model['img'], $categoryid, $model['h_id']);
        }
    }

//update category
    function editCategory($model) {
        $id = $model['id'];
        $sql = "update " . $this->table_name('category') . " set edit_by='" . $model['h_id'] . "'  where cat_id='{$id}'";
        $this->query($sql);
        if (!empty($model['i8n'])) {
            foreach ($model['i8n'] as $v) {
                if (count($v) > 0) {
                    $set = "";
                    if (isset($v['name'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "cat_name='" . $v['name'] . "'  ";
                    }
                    if (isset($v['overview'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "cat_overview='" . $v['overview'] . "'  ";
                    }
                    if (isset($v['detail'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "cat_detail='" . $v['detail'] . "'  ";
                    }
                    if (isset($v['i8n'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "i8n='" . $v['i8n'] . "'  ";
                    }
                    if ($set != "") {
                        $sql = "update " . $this->table_name('category_i8n') . " set " . $set . " where cat_i8n_id='" . $v['sid'] . "' ";
                        $this->query($sql);
                    }
                }
            }
        }
        if (!empty($model['img'])) {
            $Img = new \AliceDAL\Img('C');
            $Img->editImg($model['img'], $model['h_id']);
        }
    }

//delete category
    function delCategory($model) {
        $id = $model['id'];
        $sql = "delete from  " . $this->table_name('category') . "  where cat_id='{$id}'";
        $this->query($sql);
    }

    /* 通用树状结构 */

    function tree($id = 0, $lang = 'en_us', $level = 0, $is_show_all = true) {
        $sql = "select c.*,ci.*, COUNT(s.cat_id) AS has_children " .
                " from " . $this->table_name('category') . " as c " .
                "left join " . $this->table_name('category_i8n') . " as ci on ci.cat_id=c.cat_id " .
                "left join " . $this->table_name('category') . " as s on s.parent_id=c.cat_id " .
                " where ci.i8n='".$lang."'".
                " GROUP BY c.cat_id " .
                " order by c.parent_id asc,c.cat_id asc";
        //	pr($sql);
        $cate = $this->getFetchAll($sql);

        $options = $this->madeTree($id, $cate, 'cat_id', 'cat_name');
        $children_level = 99999; //大于这个分类的将被删除
        if ($is_show_all == false) {
            foreach ($options as $key => $val) {
                if ($val['level'] > $children_level) {
                    unset($options[$key]);
                } else {
                    if ($val['is_show'] == 0 ) {
                        unset($options[$key]);
                        if ($children_level > $val['level']) {
                            $children_level = $val['level']; //标记一下，这样子分类也能删除
                        }
                    } else {
                        $children_level = 99999; //恢复初始值
                    }
                }
            }
        }

        /* 截取到指定的缩减级别 */
        if ($level > 0) {
            if ($id == 0) {
                $end_level = $level;
            } else {
                $first_item = reset($options); // 获取第一个元素
                $end_level = $first_item['level'] + $level;
            }

            /* 保留level小于end_level的部分 */
            foreach ($options AS $key => $val) {
                if ($val['level'] >= $end_level) {
                    unset($options[$key]);
                }
            }
        }
        return $options;
    }


}
