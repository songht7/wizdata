<?php

namespace AliceDAL;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Img extends \CommonDAL\BaseDAL {

    protected $type = "C";

    function __construct($type) {
        parent::__construct();
        $this->type = $type;
    }

    //add img
    function addImg($model, $type_id, $h_id) {
        foreach ($model as $v) {

            $type_id = $type_id;
            $type = $this->type;
            $point = isset($v['point']) ? $v['point'] : 0;
            $is_show = isset($v['is_show']) ? $v['is_show'] : 0;
            $original_src = isset($v['original_src']) ? $v['original_src'] : null;
            $original_link = isset($v['original_link']) ? $v['original_link'] : null;
            $order_by = isset($v['order_by']) ? $v['order_by'] : 0;
            $add_by = isset($h_id) ? $h_id : 1;
            $edit_by = isset($h_id) ? $h_id : 1;
            $i8n = isset($v['i8n']) ? $v['i8n'] : 'en_us';

            $sql = "insert into " . $this->table_name('img') . "(type_id,type,point,is_show,original_src,original_link"
                    . ",order_by,add_by,add_time,edit_by,i8n) "
                    . "values('" . $type_id . "','" . $type . "','" . $point . "','" . $is_show . "','" . $original_src . "','" . $original_link
                    . "','" . $order_by . "','" . $add_by . "',NOW(),'" . $edit_by . "','" . $i8n . "')";

            $this->query($sql);
        }
    }

    //edit img
    function editImg($model, $h_id) {
        foreach ($model as $iv) {
            foreach ($iv as $v) {
                $edit_by = isset($h_id) ? $h_id : 1;
                $sql = "update " . $this->table_name('img') . " set  edit_by='" . $edit_by . "'  ";
                if (isset($v['point'])) {
                    $sql.= ",point='" . $v['point'] . "'  ";
                }
                if (isset($v['is_show'])) {
                    $sql.= ",is_show='" . $v['is_show'] . "'  ";
                }
                if (isset($v['original_src'])) {
                    $sql.= ",original_src='" . $v['original_src'] . "'  ";
                }
                if (isset($v['original_link'])) {
                    $sql.= ",original_link='" . $v['original_link'] . "'  ";
                }
                if (isset($v['order_by'])) {
                    $sql.= ",order_by='" . $v['order_by'] . "'  ";
                }
                if (isset($v['i8n'])) {
                    $sql.= ",i8n='" . $v['i8n'] . "'  ";
                }
                $sql.="where img_id='" . $v['img_id'] . "' ";
                $this->query($sql);
            }
        }
    }
    //delete
    function deleteImg($model, $h_id){
        foreach ($model as $v) {
            $img_id = $v['img_id'] ;
            $sql = "delete from " . $this->table_name('img') . " where img_id=".$img_id."; ";
            $this->query($sql);
        }
    }

}
