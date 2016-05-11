<?php

namespace alice;
class product extends \Common\init {

    function __construct() {
        parent::__construct();

    }

    function index() {
        $this->isset_cookie();
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
            $where = " and a.cat_id='" . $id . "' ";
        }
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        if (!isset($_GET['perpagenum'])) {
            $perpagenum = 8; //定义每页显示几条	
        } else {
            $perpagenum = $_GET['perpagenum'];
        }
        if (!isset($_GET['order'])) {
            $type = 'a';
            $order = 'goods_id';
            $orders = 'a.goods_id'; //排序	
        } else {
            $type = $_GET['type'];
            $order = $_GET['order'];
            $orders = $_GET['type'] . '.' . $_GET['order'];
        }
        if (!isset($_GET['by'])) {
            $by = 'desc'; //排序	
        } else {
            $by = $_GET['by'];
        }
        if ($by == "asc") {
            $bys = "desc";
        } else if ($by == "desc") {
            $bys = "asc";
        }


        $sql = "select count(*) as c from " . $this->table_name('goods') . " as a left join " . $this->table_name('goods_i8n') . " as i on i.goods_id=a.goods_id where i.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "' ";
        $total = getFetchAll($sql, $this->conn);
        $Total = $total[0]['c'];
        $Totalpage = ceil($Total / $perpagenum);
        $startnum = ($page - 1) * $perpagenum;
        $sql = "select a.*,i.*,ci.cat_name from " . $this->table_name('goods') . " as a left join " . $this->table_name('goods_i8n') . " as i on i.goods_id=a.goods_id " .
                " left join " . $this->table_name('category') . " as c on c.cat_id=a.cat_id " .
                " left join " . $this->table_name('category_i8n') . " as ci on ci.cat_id=a.cat_id " .
                " where i.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "' and (ci.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "' or a.cat_id=0) order by " . $orders . " $by limit $startnum,$perpagenum";
        $products = getFetchAll($sql, $this->conn);
        $tmpPath = $this->sysVar['template'] . 'admin/show_product.php';
        include($tmpPath);
    }

    function change_show() {
        $this->isset_cookie();
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $data['id'] = $id;
            $sql = "select is_show from " . $this->table_name('goods') . " where goods_id='{$id}'";
            //pr($sql);
            $product = getFetchRow($sql, $this->conn);
            if ($product['is_show'] == '1') {
                $is_show = 0;
            } else {
                $is_show = 1;
            }
            $sql = "update " . $this->table_name('goods') . " set is_show='" . $is_show . "' where goods_id='{$id}'";
            $a = mysql_query($sql, $this->conn);

            if ($a) {
                $data['say'] = "ok";
                $data['is_show'] = $is_show;
            } else {
                $data['say'] = "error";
            }
            die(json_encode($data));
        } else {
            $data['say'] = "error";
            die(json_encode($data));
        }
    }

    function change_top() {
        $this->isset_cookie();
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $data['id'] = $id;
            $sql = "select top from " . $this->table_name('goods') . " where goods_id='{$id}'";
            //pr($sql);
            $product = getFetchRow($sql, $this->conn);
            if ($product['top'] == '1') {
                $top = 0;
            } else {
                $top = 1;
            }
            $sql = "update " . $this->table_name('goods') . " set top='" . $top . "' where goods_id='{$id}'";
            $a = mysql_query($sql, $this->conn);

            if ($a) {
                $data['say'] = "ok";
                $data['top'] = $top;
            } else {
                $data['say'] = "error";
            }
            die(json_encode($data));
        } else {
            $data['say'] = "error";
            die(json_encode($data));
        }
    }

    function change_order() {
        $this->isset_cookie();
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $valbase = $_POST['val'];
            $val = addslashes(str_replace(' ', '', $valbase));
            $data['id'] = $id;
            if (!empty($val)) {
                $sql = "update " . $this->table_name('goods') . " set order_by='" . $val . "' where goods_id='{$id}'";
                $a = mysql_query($sql, $this->conn);
            } else {
                $sql = "select order_by from " . $this->table_name('goods') . " where goods_id='{$id}'";
                $product = getFetchRow($sql, $this->conn);
                $val = $product['order_by'];
            }

            if ($a) {
                $data['say'] = "ok";
                $data['val'] = $val;
            } else {
                $data['say'] = "error";
            }
            die(json_encode($data));
        } else {
            $data['say'] = "error";
            die(json_encode($data));
        }
    }

    function show_product_detail() {
        $this->isset_cookie();
        $category = $this->c_tree();
        if (isset($_GET['id'])) {
            $act = 'edit';

            $id = $_GET['id'];
            $sql = "select * from " . $this->table_name('goods') . " where goods_id='{$id}'";
            $product = getFetchAll($sql, $this->conn);

            $sql = "select * from " . $this->table_name('img') . " where type_id='{$id}' and type='P' order by i8n asc";
            $img = getFetchAll($sql, $this->conn);
            if (!empty($img)) {
                foreach ($img as $k => $v) {
                    $imgs[$v['img_id'] . '-' . $v['i8n']] = $v;
                }
            }
            //pr($img);
            $sql = "select * from " . $this->table_name('goods_i8n') . " where goods_id='{$id}' order by i8n asc";
            $products = getFetchAll($sql, $this->conn);
            if (!empty($products)) {
                foreach ($products as $k => $v) {
                    $v['detail_arr'] = explode('":;"', $v['goods_detail']);
                    $pro[$v['i8n']] = $v;
                }
            }
            //pr($pro);die;
        } else {
            $act = 'add';
            //js_alert_redir('登录错误请重新再试','index.php?a=main&m=login');
        }
        $tmpPath = $this->sysVar['template'] . 'admin/show_product_detailed.php';
        include($tmpPath);
    }

    function edit_product() {
        $this->isset_cookie();
        $d = date("Y-m-d H:i:s");

        require_once(MANAGE_MOD . 'uploaded_file.php');
        $path = "/data/product_doc/";
        $doc_src = uploaded_m_file($this->table_name('img'), 'original_src', 'file_url', $path);

        if ($_GET['id'] != '' && $_POST['act'] == 'edit') {
            $id = $_GET['id'];
            $sql = "update " . $this->table_name('goods') .
                    " set 
								cat_id='" . $_POST['cat_id'] . "'
								,top='" . $_POST['top'] . "'
								,order_by='50'
								,is_sale=" . $_POST['is_sale'] . "
								,is_sale_i=" . $_POST['is_sale_i'] . "
								,sale_price=" . $_POST['sale_price'] . "
								,price='" . $_POST['price'] . "'
								,color='" . $_POST['color'] . "'
								,edit_by='" . $_SESSION[$this->shop_name]['h_id'] . "'
							 where goods_id='{$id}'";
            //pr($sql);die;
            $a = mysql_query($sql, $this->conn);
            if (!empty($_POST['edit_doc'])) {
                foreach ($_POST['edit_doc'] as $k => $v) {
                    $sql = "select * from " . $this->table_name('img') . " where img_id='" . $_POST['img_id'][$k] . "' ";
                    $img_b = getFetchAll($sql, $this->conn);
                    if (!empty($img_b) && $_POST['acti'][$k] == 'del') {
                        @unlink('.' . $img_b[0]['original_src']);
                        $sql = "delete from " . $this->table_name('img') . " where img_id='" . $_POST['img_id'][$k] . "' ";
                        $c = mysql_query($sql, $this->conn);
                    } else if (!empty($img_b)) {
                        if ($v == 1) {
                            $src = ",original_src='" . $doc_src[$k] . "'";
                            @unlink('.' . $img_b[0]['original_src']);
                        } else {
                            $src = "";
                        }
                        if (isset($_POST['is_showi'][$k])) {
                            $where = " ,is_show='" . $_POST['is_showi'][$k] . "'";
                        } else {
                            $where = "";
                        }
                        $sql = "update " . $this->table_name('img') . " set order_by='50',edit_by='" . $_SESSION[$this->shop_name]['h_id'] . "'" . $where . $src . " where img_id='" . $_POST['img_id'][$k] . "' ";
                        $c = mysql_query($sql, $this->conn);
                    } else {
                        if ($v == 1) {
                            $p = explode('-', $k);
                            if (!empty($_POST['is_show'][$k])) {
                                $where = $_POST['is_show'][$k];
                            } else {
                                $where = "1";
                            }
                            $sql = "insert into " . $this->table_name('img') . "(type_id,order_by,is_show,type,original_src,add_by ,add_time,edit_by,point,i8n) values('" . $id . "','50','" . $where . "','P','" . $doc_src[$k] . "','" . $_SESSION[$this->shop_name]['h_id'] . "','" . $d . "','" . $_SESSION[$this->shop_name]['h_id'] . "','" . $p[2] . "','" . $p[1] . "')";
                            //pr($sql);
                            $c = mysql_query($sql, $this->conn);
                        }
                    }
                }
            }
            //die;
            if ($a) {
                if (!empty($_POST['detail'])) {
                    foreach ($_POST['detail'] as $k => $v) {
                        $title = addslashes($_POST['title'][$k]);
                        $overview = addslashes($_POST['overview'][$k]);
                        $art_detail = addslashes(implode('":;"', $v));
                        $sql = "update " . $this->table_name('goods_i8n') . " set goods_name='" . $title . "',goods_overview='" . $overview . "',goods_detail='" . $art_detail . "'  where goods_i8n_id='" . $_POST['iid'][$k] . "'";

                        $b = mysql_query($sql, $this->conn);
                    }
                }
                //die;
                js_redir('index.php?a=product&m=index');
            } else {
                js_alert('修改失败，请联系系统管理员');
            }
        } else if ($_POST['act'] == 'add') {
            $sql = "insert into " . $this->table_name('goods') . "
						(
							cat_id,
							top,
							is_show,
							order_by,
							add_by,
							add_time,
							edit_by,
							is_sale,
							is_sale_i,
							sale_price,
							price,
							color
						) values (
							'" . $cat_id . "',
							'" . $_POST['top'] . "',
							'" . $_POST['is_show'] . "',
							'50',
							'" . $_SESSION[$this->shop_name]['h_id'] . "',
							'" . $d . "',
							'" . $_SESSION[$this->shop_name]['h_id'] . "',
							'" . $_POST['is_sale'] . "',
							'" . $_POST['is_sale_i'] . "',
							'" . $_POST['sale_price'] . "',
							'" . $_POST['price'] . "',
							'" . $_POST['color'] . "'
						) ";
            $a = mysql_query($sql, $this->conn);
            $id = mysql_insert_id();

            if (!empty($_POST['edit_doc'])) {
                foreach ($_POST['edit_doc'] as $k => $v) {
                    $sql = "select * from " . $this->table_name('img') . " where img_id='" . $_POST['img_id'][$k] . "' ";
                    $img_b = getFetchAll($sql, $this->conn);
                    if ($v == 1) {
                        $p = explode('-', $k);
                        if (!empty($_POST['is_show'][$k])) {
                            $where = $_POST['is_show'][$k];
                        } else {
                            $where = "1";
                        }
                        $sql = "insert into " . $this->table_name('img') . "(type_id,order_by,is_show,type,original_src,add_by ,add_time,edit_by,point,i8n) values('" . $id . "','" . $_POST['order_by'][$k] . "','" . $where . "','P','" . $doc_src[$k] . "','" . $_SESSION[$this->shop_name]['h_id'] . "','" . $d . "','" . $_SESSION[$this->shop_name]['h_id'] . "','" . $p[2] . "','" . $p[1] . "')";
                        $c = mysql_query($sql, $this->conn);
                    }
                }
            }
            if ($a) {
                if (!empty($_POST['detail'])) {
                    foreach ($_POST['detail'] as $k => $v) {
                        $title = addslashes($_POST['title'][$k]);
                        $overview = addslashes($_POST['overview'][$k]);
                        $art_detail = addslashes(implode('":;"', $v));
                        $sql = "insert into " . $this->table_name('goods_i8n') . "(goods_id,goods_name,goods_overview,goods_detail,i8n) values('" . $id . "','" . $title . "','" . $overview . "','" . $art_detail . "','" . $k . "')";
                        $b = mysql_query($sql, $this->conn);
                    }
                }
                //die;
                js_redir('index.php?a=product&m=index');
            } else {
                js_alert('添加失败，请联系系统管理员');
            }
        } else {
            js_alert_redir('登录错误请重新再试', 'index.php?a=main&m=login');
        }
    }

    function del_product() {
        $this->isset_cookie();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "delete from " . $this->table_name('goods') . " where goods_id='{$id}'";
            $a = mysql_query($sql, $this->conn);
            $sql = "delete from " . $this->table_name('goods_i8n') . " where goods_id='{$id}'";
            $a = mysql_query($sql, $this->conn);

            $sql = "select * from " . $this->table_name('img') . " where type_id='{$id}' and type='P'";
            $product = getFetchAll($sql, $this->conn);
            if (!empty($product)) {
                foreach ($product as $k => $v) {
                    @unlink('.' . $v['original_src']);
                }
            }
            $sql = "delete from " . $this->table_name('img') . " where type_id='{$id}' and type='P'";
            $a = mysql_query($sql, $this->conn);

            if ($a)
                $this->index();
            else
                js_alert('删除失败，请联系系统管理员');
        }else {
            js_alert_redir('登录错误请重新再试', 'index.php?a=main&m=login');
        }
    }

    /* 调货单 */

    function tune_manifest() {
        $this->isset_cookie();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        if (!isset($_GET['perpagenum'])) {
            $perpagenum = 8; //定义每页显示几条	
        } else {
            $perpagenum = $_GET['perpagenum'];
        }
        if (!isset($_GET['order'])) {
            $type = 'l';
            $order = 'goods_move_id';
            $orders = 'l.goods_move_id'; //排序	
        } else {
            $type = $_GET['type'];
            $order = $_GET['order'];
            $orders = $_GET['type'] . '.' . $_GET['order'];
        }
        if (!isset($_GET['by'])) {
            $by = 'desc'; //排序	
        } else {
            $by = $_GET['by'];
        }
        if ($by == "asc") {
            $bys = "desc";
        } else if ($by == "desc") {
            $bys = "asc";
        }


        $sql = "select count(*) as c from " . $this->table_name('goods_move_log') . " as l 
					left join " . $this->table_name('goods') . " as a on a.goods_id=l.goods_id 
					left join " . $this->table_name('goods_i8n') . " as i on i.goods_id=a.goods_id 
					where l.type='1' and i.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "' ";
        $total = getFetchAll($sql, $this->conn);
        $Total = $total[0]['c'];
        $Totalpage = ceil($Total / $perpagenum);
        $startnum = ($page - 1) * $perpagenum;
        $sql = "select l.*,a.goods_sn,i.goods_name,i.goods_overview from " . $this->table_name('goods_move_log') . " as l 
						 left join " . $this->table_name('goods') . " as a on a.goods_id=l.goods_id 
						 left join " . $this->table_name('goods_i8n') . " as i on i.goods_id=a.goods_id 
						 where l.type='1' and i.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "' order by " . $orders . " $by limit $startnum,$perpagenum";
        $products = getFetchAll($sql, $this->conn);
        //	pr($products);die;
        $sql = "select g.goods_id,i.goods_name,i.goods_overview from " . $this->table_name('goods') . " as g 
						left join " . $this->table_name('goods_i8n') . " as i 
						on g.goods_id=i.goods_id 
						where i.i8n='" . $_SESSION[$this->shop_name]['b_lang'] . "'
						";
        $wine = getFetchAll($sql, $this->conn);
        //	pr($wine);die;
        $tmpPath = $this->sysVar['template'] . 'admin/show_product_tune_manifest.php';
        include($tmpPath);
    }

    function change_tune_manifest() {
        $this->isset_cookie();
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $data['id'] = $id;
            $sql = "select type,qty,goods_id from " . $this->table_name('goods_move_log') . " where goods_move_id='{$id}'";
            //pr($sql);
            $product = getFetchRow($sql, $this->conn);
            if ($product['type'] == '1') {
                $is_show = 0;
                $sql = "update " . $this->table_name('goods') . " set t_inventory=t_inventory-" . $product['qty'] . ",b_inventory=b_inventory+" . $product['qty'] . " where goods_id='" . $product['goods_id'] . "' ";
                $a = mysql_query($sql, $this->conn);
            } else {
                $is_show = 1;
                $sql = "update " . $this->table_name('goods') . " set t_inventory=t_inventory+" . $product['qty'] . ",b_inventory=b_inventory-" . $product['qty'] . " where goods_id='" . $product['goods_id'] . "' ";
                $a = mysql_query($sql, $this->conn);
            }
            //	pr($sql);
            $sql = "update " . $this->table_name('goods_move_log') . " set type='" . $is_show . "' where goods_move_id='{$id}'";
            $a = mysql_query($sql, $this->conn);

            if ($a) {
                $data['say'] = "ok";
                $data['is_show'] = $is_show;
            } else {
                $data['say'] = "error";
            }
            die(json_encode($data));
        } else {
            $data['say'] = "error";
            die(json_encode($data));
        }
    }

    function tune_manifest_post() {
        $this->isset_cookie();
        $d = date("Y-m-d H:i:s");
        if (!empty($_POST)) {
            $id = $_POST['wine_id'];
            $sql = "select t_inventory,b_inventory from " . $this->table_name('goods') . " where goods_id='{$id}'";
            $product = getFetchRow($sql, $this->conn);

            $iqty = 0;
            /*
              $sql="select sum(qty) as sqty from ".$this->table_name('goods_move_log')." where goods_id='{$id}' and type=1 ";
              $qty=getFetchRow($sql,$this->conn);
             */
            if (!empty($qty['sqty'])) {
                $iqty = $qty['sqty'];
            }
            $sqty = $product['b_inventory'] - $iqty - $_POST['qty'];
            //		pr($sqty);die;
            if ($_POST['qty'] > 0) {
                if ($sqty >= 0) {
                    $sql = "insert into " . $this->table_name('goods_move_log') . " (goods_id,type,qty,add_by,add_time,edit_by)values('" . $id . "','1','" . $_POST['qty'] . "','" . $_SESSION[$this->shop_name]['h_id'] . "','" . $d . "','" . $_SESSION[$this->shop_name]['h_id'] . "')";
                    mysql_query($sql, $this->conn);
                    $sql = "update " . $this->table_name('goods') . " set t_inventory=t_inventory+" . $_POST['qty'] . ",b_inventory=b_inventory-" . $_POST['qty'] . " where goods_id='" . $id . "' ";
                    $a = mysql_query($sql, $this->conn);

                    js_redir('index.php?a=product&m=tune_manifest');
                } else {
                    js_alert_redir('Inventory is not enough.', 'index.php?a=product&m=tune_manifest');
                }
            } else {
                js_alert_redir('Qty is error', 'index.php?a=product&m=tune_manifest');
            }
        } else {
            js_redir('index.php?a=product&m=tune_manifest');
        }
    }

    function get_goods_info() {
        $this->isset_cookie();
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $data['id'] = $id;
            $sql = "select t_inventory,b_inventory from " . $this->table_name('goods') . " where goods_id='{$id}'";
            //pr($sql);
            $product = getFetchRow($sql, $this->conn);
            $iqty = 0;
            /* 	
              $sql="select sum(qty) as sqty from ".$this->table_name('goods_move_log')." where goods_id='{$id}' and type=1 ";
              $qty=getFetchRow($sql,$this->conn);
             */
            if (!empty($qty['sqty'])) {
                $iqty = $qty['sqty'];
            }
            //	pr($product);pr($sql);die;
            $data['qty'] = $product['b_inventory'] - $iqty;

            $data['say'] = "product";
            //	$data['data']=$product;
            die(json_encode($data));
        } else {
            $data['say'] = "error";
            die(json_encode($data));
        }
    }

    function del_product_move() {
        $this->isset_cookie();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "delete from " . $this->table_name('goods_move_log') . " where goods_move_id='{$id}'";
            $a = mysql_query($sql, $this->conn);
            if ($a)
                js_redir('index.php?a=product&m=tune_manifest');
            else
                js_alert('删除失败，请联系系统管理员');
        }else {
            js_alert_redir('登录错误请重新再试', 'index.php?a=main&m=login');
        }
    }

    /* 调货单 end */
}
