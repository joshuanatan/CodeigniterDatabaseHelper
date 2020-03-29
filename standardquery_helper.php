<?php

#Codeigniter Standard Query Helper
#Created by: Joshua Natan Wijaya 

#This is Codeigniter Database Helper
#Main intention is to help developer to simplify database transaction (CRUD) 
#Put this script under /application/helpers/
#Add to autoload /application/config/autoload.php
#Call these functions from your controller/model [ex: insertRow("tbl_user",array("id_user"=>"001","user_name"=>"joshua"))]

if ( ! function_exists('insertRow')){
    function insertRow($table,$data){
        $CI =& get_instance();
        $CI->db->insert($table,$data);
        return $CI->db->insert_id();
    }
}
if ( ! function_exists('insertRowNoReturn')){
    function insertRowNoReturn($table,$data){
        $CI =& get_instance();
        $CI->db->insert($table,$data);
    }
}

if ( ! function_exists('updateRow')){
    function updateRow($table,$data,$where){
        $CI =& get_instance();
        $CI->db->update($table,$data,$where);
    }
}

if ( ! function_exists('deleteRow')){
    function deleteRow($table,$where){
        $CI =& get_instance();
        $CI->db->delete($table,$where);
    }
}

if ( ! function_exists('selectRow')){
    function selectRow($table,$where = "",$field = "",$limit = "",$offset = "",$order = "", $order_direction = "",$group_by = "",$like = "",$or_like = "", $or_where = ""){
        $CI =& get_instance();
        if($where != ""){
            $CI->db->where($where);
        }
        if($like != ""){
            $CI->db->like($like);
        }
        if($or_like != ""){
            $CI->db->or_like($or_like);
        }
        if($group_by != ""){
            $CI->db->group_by($group_by);
        }
        if($order != ""){
            if($order_direction != ""){
                $CI->db->order_by($order,$order_direction);
            }
            else{
                $CI->db->order_by($order,'ASC');
            }
        }
        if($limit != ""){
            if($offset != ""){
                $CI->db->limit($limit,$offset);
            }
            else{
                $CI->db->limit($limit);
            }
        }
        if($field != ""){
            $CI->db->select($field);
        }
        if($or_where != ""){
            $CI->db->or_where($or_where);
        }
        return $CI->db->get($table);
    }
}
if ( ! function_exists('isExistsInTable')){
    function isExistsInTable($table,$where){
        $CI =& get_instance();
        $result = $CI->db->get_where($table,$where);
        if($result->num_rows() > 0){
            return true; /*exists*/
        }
        else return false; /*not exists*/
    }   
}
if ( ! function_exists('getMaxId')){
    function getMaxId($table,$coloumn,$where){
        $CI =& get_instance();
        $CI->db->select("max(".$coloumn.") as maxId");
        $result = $CI->db->get_where($table,$where);
        foreach($result->result() as $a){
            if($a->maxId != ""){
                return $a->maxId+1;
            }
            else return 1;
        }
    }   
}
if ( ! function_exists('get1Value')){
    function get1Value($table,$coloumn,$where){
        $CI =& get_instance();
        $CI->db->select($coloumn);
        $result = $CI->db->get_where($table,$where);
        foreach($result->result() as $a){
            return $a->$coloumn;
            break;
        }
        return false;
    }
}
if ( ! function_exists('getAmount')){
    function getAmount($table,$coloumn,$where){
        $CI =& get_instance();
        $CI->db->select("count(".$coloumn.") as 'amount'");
        $CI->db->group_by($coloumn);
        $result = $CI->db->get_where($table,$where);
        return $result->num_rows(); /*karena yang penting bukan di count dari sqlnya melainkan jumlah row yang didapat dari query ini*/
    }
}
if ( ! function_exists('getTotal')){
    function getTotal($table,$coloumn,$where){
        $CI =& get_instance();
        $CI->db->select("sum(".$coloumn.") as 'total'");
        $result = $CI->db->get_where($table,$where);
        $adaTotal = 1;
        if($result->num_rows() == 0) return 0;
        foreach($result->result() as $a){
            $adaTotal = 0;
            return $a->total;
            break;
        }
        if($adaTotal == 1)
            return 0;
    }
}
if(! function_exists('selectRowBetweenDates')){
    function selectRowBetweenDates($table,$kolom_tgl,$constraint,$where,$field = "",$group_by = ""){
        $CI =& get_instance();
        if($field != ""){
            $CI->db->select($field);
        }
        if($group_by != ""){
            $CI->db->group_by($group_by);
        }
        $CI->db->where("$kolom_tgl between '".$constraint["awal"]."' and '".$constraint["akhir"]."' ");
        return $CI->db->get_where($table,$where);
    }
}
if(! function_exists('selectLike')){
    function selectLike($table,$like,$where,$limit = 0){
        $CI =& get_instance();
        $CI->db->like($like[0],$like[1],$like[2]);
        if($limit != 0){
            $CI->db->limit($limit);
        }
        return $CI->db->get_where($table,$where);
    }
}
if(! function_exists('executeQuery')){
    function executeQuery($query){
        
        $CI =& get_instance();
        return $CI->db->query($query);
    }
}

?>