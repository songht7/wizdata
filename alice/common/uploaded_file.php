<?php

function uploaded_file($table, $table_name, $filename, $path) {//存数据库表名，上传表单名
    $name = $_FILES[$filename]['name'];
    $pos = strrpos($name, "."); //取得文件名中后缀名的开始位置
    $ext = substr($name, $pos); //取得后缀名，包括点号
    $name = date("Ynd") . rand(1000, 9999) . $ext;
    $full_path2 = $path . $name;
    $full_path = "." . $full_path2;
    while (true) {
        $sql = "select count(*) from {$table} where {$table_name}='$full_path'";
        $a = mysql_query($sql);
        $row = mysql_fetch_array($a);
        if ($row[0] > 0) {
            $full_path2 = $path . date("Ynd") . rand(0, 999) . $name;
            $full_path = "." . $full_path2;
        } else
            break;
    }
    move_uploaded_file($_FILES[$filename]['tmp_name'], $full_path);
    return $full_path2;
}

function uploaded_m_file($table, $table_name, $filename, $path) {//存数据库表名，上传表单名
    $names = $_FILES[$filename]['name'];
    $full_path2 = '';
    if (!empty($names)) {
        foreach ($names as $k => $v) {
            if ($v != "") {
                $pos = strrpos($v, "."); //取得文件名中后缀名的开始位置
                $ext = substr($v, $pos); //取得后缀名，包括点号
                $names[$k] = date("Ynd") . rand(1000, 9999) . $ext;
                $full_path2[$k] = $path . $names[$k];
                $full_path = "." . $full_path2[$k];
                while (true) {
                    $sql = "select count(*) from {$table} where {$table_name}='$full_path'";
                    $a = mysql_query($sql);
                    $row = mysql_fetch_array($a);
                    if ($row[0] > 0) {
                        $names[$k] = date("Ynd") . rand(0, 999) . $ext;
                        $full_path2[$k] = $path . $names[$k];
                        $full_path = "." . $full_path2[$k];
                    } else
                        break;
                }
                move_uploaded_file($_FILES[$filename]['tmp_name'][$k], $full_path);
            }
        }
    }
    return $full_path2;
}

?>