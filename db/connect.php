<?php
date_default_timezone_set('Asia/Manila');

$PROJECT_NAME           = 'Reservation System';

$GLOBALS['host']        = 'localhost';
$GLOBALS['username']    = 'root';
$GLOBALS['password']    = '';
$GLOBALS['port']        = '3307';
$GLOBALS['db']          = 'simple-reservation-system1';

$GLOBALS['pdo']         = new PDO("mysql:host={$GLOBALS['host']};port={$GLOBALS['port']};dbname=mysql", $GLOBALS['username'], $GLOBALS['password']);
$GLOBALS['pdo']         ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

initializeDatabase();

function initializeDatabase() {
    $GLOBALS['pdo']->exec("
        CREATE DATABASE IF NOT EXISTS `{$GLOBALS['db']}`;
        USE `{$GLOBALS['db']}`;
        create table if not exists category (
          id          int auto_increment primary key,
          name        varchar(255) null,
          description longtext     null
        );
        create table if not exists unit (
          id          int auto_increment primary key,
          category_id int          null,
          name        varchar(255) null,
          description longtext     null,
          img         varchar(255) null,
          constraint unit_category_id_fk foreign key (category_id) references category (id)
        );
        create table if not exists reservation (
          id         int auto_increment primary key,
          first_name varchar(100) null,
          last_name  varchar(100) null,
          email      varchar(100) null,
          unit_id    int          null,
          date_from  datetime     null,
          date_to    datetime     null,
          status     varchar(100) null,
          mobile     varchar(20)  null,
          constraint reservation_unit_id_fk foreign key (unit_id) references unit (id)
        );"
    );
}

function seed(){
    $units = get_unit();
    $categories = get_category();

    if(count($categories) <= 0) {
        $string = file_get_contents(__DIR__. "/simple_reservation_system_category.json");
        $json_a = json_decode($string, true);
        foreach ($json_a as $item) {
            stmtInsert('category', $item);
        }
    }
    if(count($units) <= 0) {
        $string = file_get_contents(__DIR__. "/simple_reservation_system_unit.json");
        $json_a = json_decode($string, true);
        foreach ($json_a as $item) {
            stmtInsert('unit', $item);
        }
    }
}

function stmtPrepare($sql) {
    try {
        $stmt = $GLOBALS['pdo']->prepare($sql);
    } catch(PDOExeption $e) {
        die($e->getMessage());
    }
    return $stmt;
}

function stmtExecute(&$stmt, $params=array()) {
    try {
        $stmt->execute($params);
    } catch(PDOExeption $e) {
        die($e->getMessage());
    }
}

function stmtInsert($tbl, $data) {

    $f = '';
    $v = '';
    $p = array();

    foreach($data as $colName => $value) {
        if(is_numeric($colName)) {
            continue;
        }
        if(empty($f)) {
            $f = '`' . $colName . '`';
            $v = '?';
        } else {
            $f .=  ',`' . $colName . '`';
            $v .= ',?';
        }
        $p[] = $value;
    }

    $qi = $GLOBALS['pdo']->prepare("INSERT INTO `$tbl` ($f) VALUES ($v)");
    $qi->execute($p);
    return  $GLOBALS['pdo']->lastInsertId();
}

function stmtUpdate($tbl, $data, $where) {

    $s = '';
    $w = '';
    $p = array();
    foreach($data as $colName => $value){
        if(is_numeric($colName)) {
            continue;
        }
        if(empty($s)) {
            $s = '`' . $colName . '` = ?';
            $p[] = $value;
        } else {
            $s .=  ' ,`' . $colName . '` = ?';
            $p[] = $value;
        }
    }

    foreach($where as $colName2 => $value2){
        if(is_numeric($colName2)) {
            continue;
        }
        if(empty($w)) {
            $w = '`' . $colName2 . '` = ?';
            $p[] = $value2;
        } else {
            $w .=  ' AND `' . $colName2 . '` = ?';
            $p[] = $value2;
        }
    }

    $qi = $GLOBALS['pdo']->prepare("UPDATE `$tbl` SET $s WHERE $w");
    $qi->execute($p);
}

function get_unit($id = null){
    $where = "";
    $params = [];
    if(!is_null($id)) {
        $where .= (empty($where) ? ' WHERE ' : ' AND ') . " u.id = ?";
        $params[] = $id;
    }
    $units = stmtPrepare("
        SELECT
            u.*
        FROM `unit` u
        $where
    ");
    stmtExecute($units, $params);
    return $units->fetchAll();
}

function get_category($id = null){
    $where = "";
    $params = [];
    if(!is_null($id)) {
        $where .= (empty($where) ? ' WHERE ' : ' AND ') . " u.id = ?";
        $params[] = $id;
    }
    $units = stmtPrepare("
        SELECT
            u.*
        FROM `category` u
        $where
    ");
    stmtExecute($units, $params);
    return $units->fetchAll();
}

function get_reservation($id = null){
    $where = "";
    $params = [];
    if(!is_null($id)) {
        $where .= (empty($where) ? ' WHERE ' : ' AND ') . " r.id = ?";
        $params[] = $id;
    }
    $units = stmtPrepare("
        SELECT
            r.*,
               u.id AS unit_id,
               u.name AS unit_name,
               u.description AS unit_description,
               u.img AS unit_img
        FROM `reservation` r
        INNER JOIN unit u ON r.unit_id = u.id
        $where
    ");
    stmtExecute($units, $params);
    return is_null($id) ? $units->fetchAll() : $units->fetch();
}

function get_reservation_overlap($unit_id, $date_from, $date_to){
    $q = stmtPrepare("
        SELECT * 
        FROM reservation
        WHERE unit_id = :unit_id 
        AND (:date_from BETWEEN date_from AND date_to) OR (:date_to BETWEEN date_from AND date_to)
    ");
    stmtExecute($q, [
        'unit_id' => $unit_id,
        'date_from' => $date_from,
        'date_to' => $date_to
    ]);
    return $q->fetchAll();
}