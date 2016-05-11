<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AliceDAL;

/**
 * Description of NewsDAL
 *
 * @author space
 */
class News extends \CommonDAL\BaseDAL {

    function __construct() {
        parent::__construct();
        include_once './model/ImgDAL.php';
    }

//get list
    function getList($i8n, $currentpage, $pagesize) {

        $sql = "select count(1) as count from " . $this->table_name('article') . " as c left join " . $this->table_name('article_i8n') . " as i on c.art_id=i.art_id "
                . " where i.i8n='" . $i8n . "' and c.status=1;";
        $count = $this->getFetchRow($sql);
        $orderby = " order by c.order_by asc,c.art_id desc ";
        $limit = " limit " . (($currentpage - 1) * $pagesize) . "," . $pagesize . " ";
        $sql = "select * from " . $this->table_name('article') . " as c left join " . $this->table_name('article_i8n') . " as i on c.art_id=i.art_id "
                . " where i.i8n='" . $i8n . "' and c.status=1 "
                . " " . $orderby . " " . $limit . ";";
        $product = $this->getFetchAll($sql);
        return array("list" => $product, "count" => $count['count']);
    }

//detail 
    function getDetail($id) {
        $sql = "select * from " . $this->table_name('article') . " where art_id='{$id}'";
        $product = $this->getFetchRow($sql);
        return $product;
    }

//i8n
    function getDetailI8n($id) {
        $sql = "select * from " . $this->table_name('article_i8n') . " where art_id='{$id}' order by i8n asc";
        $products = $this->getFetchAll($sql);
        $pro = array();
        if (!empty($products)) {
            foreach ($products as $v) {
                $v['detail'] = html_entity_decode($v['art_detail'], ENT_QUOTES, 'UTF-8');
                $pro[$v['i8n']] = $v;
            }
        }
        return $pro;
    }

//img
    function getDetailImg($id) {
        $sql = "select * from " . $this->table_name('img') . " where type_id='{$id}' and type='A' order by i8n asc";
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
    function add($model) {
        $sql = "insert into " . $this->table_name('article') . "(cat_id,type,order_by,add_by ,add_time,edit_by) "
                . "values('" . $model['cat_id'] . "','" . $model['type'] . "','" . $model['order_by'] . "','" . $model['h_id'] . "',NOW(),'" . $model['h_id'] . "')";
        $this->query($sql);
        $id = $this->get_last_id();

        foreach ($model['i8n'] as $k => $v) {
            $name = isset($v['name']) ? $v['name'] : '';
            $overview = isset($v['overview']) ? $v['overview'] : '';
            $detail = isset($v['detail']) ? $v['detail'] : '';
            $i8n = isset($v['i8n']) ? $v['i8n'] : '';
            $sql = "insert into " . $this->table_name('article_i8n') . "(art_id,art_name,art_overview,art_detail,i8n) "
                    . "values('" . $id . "','" . $name . "','" . $overview . "','" . $detail . "','" . $i8n . "')";
            $this->query($sql);
        }

        if (!empty($model['img'])) {
            $Img = new \AliceDAL\Img('A');
            $Img->addImg($model['add'], $id, $model['h_id']);
        }
    }

//update category
    function edit($model) {
        $id = $model['id'];
        $_sql = "";
        if (isset($model['order_by'])) {
            $_sql.=" ,order_by='" . $model['order_by'] . "' ";
        }
        if (!empty($model['cat_id'])) {
            $_sql.=" ,cat_id='" . $model['cat_id'] . "' ";
        }
        if (isset($model['status'])) {
            $_sql.=" ,status=" . $model['status'] . " ";
        }
        $sql = "update " . $this->table_name('article') . " set edit_by='" . $model['h_id'] . "' " . $_sql . "  where art_id='{$id}'";
        $this->query($sql);
        if (!empty($model['i8n'])) {
            foreach ($model['i8n'] as $v) {
                if (count($v) > 0) {
                    $set = "";
                    if (isset($v['name'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "art_name='" . $v['name'] . "'  ";
                    }
                    if (isset($v['overview'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "art_overview='" . $v['overview'] . "'  ";
                    }
                    if (isset($v['detail'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "art_detail='" . $v['detail'] . "'  ";
                    }
                    if (isset($v['i8n'])) {
                        if ($set != "") {
                            $set.=",";
                        }
                        $set.= "i8n='" . $v['i8n'] . "'  ";
                    }
                    if ($set != "") {
                        $sql = "update " . $this->table_name('article_i8n') . " set " . $set . " where art_i8n_id='" . $v['sid'] . "' ";
                        $this->query($sql);
                    }
                }
            }
        }
        $Img = new \AliceDAL\Img('A');
        //pr($model);die;
        if (!empty($model['img'])) {
            $Img->editImg($model['img'], $model['h_id']);
        }
        if (!empty($model['add'])) {
            $Img->addImg($model['add'], $id,$model['h_id']);
        }
        if (!empty($model['del'])) {
            $Img->deleteImg($model['del'], $model['h_id']);
        }
    }

}
