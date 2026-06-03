<?php
require __DIR__ . '/connect.php';

if (!function_exists('dbCheckError')) {
    function dbCheckError($q) {
        $e = $q->errorInfo();
        if ($e[0] !== PDO::ERR_NONE) { error_log($e[2]); return false; }
        return true;
    }
}
if (!function_exists('selectAll')) {
    function selectAll($t, $p = []) {
        global $pdo;
        $s = "SELECT * FROM $t";
        if ($p) { $i=0; foreach($p as $k=>$v) { if(!is_numeric($v)) $v="'$v'"; $s .= ($i++===0?" WHERE ":" AND ")."$k=$v"; } }
        $q=$pdo->prepare($s); $q->execute(); return $q->fetchAll();
    }
}
if (!function_exists('selectOne')) {
    function selectOne($t, $p = []) {
        global $pdo;
        $s = "SELECT * FROM $t";
        if ($p) { $i=0; foreach($p as $k=>$v) { if(!is_numeric($v)) $v="'$v'"; $s .= ($i++===0?" WHERE ":" AND ")."$k=$v"; } }
        $q=$pdo->prepare($s); $q->execute(); return $q->fetch();
    }
}
if (!function_exists('insert')) {
    function insert($t, $d) {
        global $pdo;
        $c=implode(',',array_keys($d)); $ph=':'.implode(',:',array_keys($d));
        $q=$pdo->prepare("INSERT INTO $t ($c) VALUES ($ph)");
        foreach($d as $k=>$v) $q->bindValue(":$k",$v);
        $q->execute(); return $pdo->lastInsertId();
    }
}
if (!function_exists('update')) {
    function update($t,$id,$d){
        global $pdo; $s=''; $i=0;
        foreach($d as $k=>$v){ $s.=($i++?', ':'')."$k='$v'"; }
        $q=$pdo->prepare("UPDATE $t SET $s WHERE id=$id"); $q->execute(); return true;
    }
}
if (!function_exists('delete')) {
    function delete($t,$id){ global $pdo; $q=$pdo->prepare("DELETE FROM $t WHERE id=$id"); $q->execute(); return true; }
}
if (!function_exists('countRow')) {
    function countRow($t){ global $pdo; $q=$pdo->prepare("SELECT COUNT(*) FROM $t WHERE status=1"); $q->execute(); return $q->fetchColumn(); }
}