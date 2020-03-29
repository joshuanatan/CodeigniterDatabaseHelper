<?php

#Codeigniter Standard Query Helper
#Created by: Joshua Natan Wijaya 

#This is Codeigniter Database Helper
#Main intention is to help developer to simplify database transaction (CRUD) 
#Put this script under /application/helpers/
#Add to autoload /application/config/autoload.php
#Call these functions from your controller/model [ex: insertRow("tbl_user",array("id_user"=>"001","user_name"=>"joshua"))]


#insertRow = Script to help you insert data to database

#Arguments
#table = String, Table Name (ex: "tbl_user")
#data = Key-Value Array, Data will be inserted. [ex: array("username" => "Joshua Natan")] 

#return
#latest inserted id (using auto increment in database)

if ( ! function_exists('insertRow')){
    function insertRow($table,$data){
        $CI =& get_instance();
        $CI->db->insert($table,$data);
        return $CI->db->insert_id();
    }
}

#updateRow= Script to help you update data to database

#Arguments
#table = String, Table Name (ex: "tbl_user")
#data = Key-Value Array, Data will be updated. [ex: array("username" => "Joshua Natan")] 
#where = Key-Value Array, Condition. [ex: array("id_user" => "001")]

if ( ! function_exists('updateRow')){
    function updateRow($table,$data,$where){
        $CI =& get_instance();
        $CI->db->update($table,$data,$where);
    }
}

#deleteRow= Script to help you delete data from database

#Arguments
#table = String, Table Name (ex: "tbl_user")
#where = Key-Value Array, Condition. [ex: array("id_user" => "001")]

if ( ! function_exists('deleteRow')){
    function deleteRow($table,$where){
        $CI =& get_instance();
        $CI->db->delete($table,$where);
    }
}

#selectRow= Script to help you select data from database

#Arguments
#table = String, Table Name (ex: "tbl_user")
#where = Key-Value Array, Condition. [ex: array("id_user" => "001")]
#field = String Array, Column that will be selected [ex: array("col1","col2","col3")]
#limit = Integer, Amount of data will be selected [ex:1]
#offset = Integer, Start select from.. [ex:10] (start from 10)
#order = String, Column Name (ex: "id_user")
#order_direction = String, order direction (ASC/DESC)
#group_by = String Array, Column that will be grouped by [ex: array("col1","col2")]
#like = Key-Value Array, Condition like [ex: array("id_user" => "01")]

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

#isExistsInTable= Script to help you check whether data is existed in database or not.

#Arguments
#table = String, Table Name (ex: "tbl_user")
#where = Key-Value Array, Condition. [ex: array("id_user" => "001")]

#return
#true if exists, false otherwise

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

#getMaxId= Script to help you get the latest ID (last ID + 1)

#Arguments
#table = String, Table Name (ex: "tbl_user")
#column = String, Primary key column (ex: "id_user")
#where = Key-Value Array, Condition. [ex: array("status_user" => "ACTIVE")]

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

#get1Value= Script to help you get specific data with certain condition 

#Arguments
#table = String, Table Name (ex: "tbl_user")
#column = String, Primary key column (ex: "id_user")
#where = Key-Value Array, Condition. [ex: array("status_user" => "ACTIVE")]

#return
#String, only 1 data that match the condition

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

#getAmount= Script to help you get amount of data with certain condition (count function)

#Arguments
#table = String, Table Name (ex: "tbl_user")
#column = String, Primary key column (ex: "id_user")
#where = Key-Value Array, Condition. [ex: array("status_user" => "ACTIVE")]

#return
#int, amount of data

if ( ! function_exists('getAmount')){
    function getAmount($table,$coloumn,$where){
        $CI =& get_instance();
        $CI->db->select("count(".$coloumn.") as 'amount'");
        $CI->db->group_by($coloumn);
        $result = $CI->db->get_where($table,$where);
        return $result->num_rows(); /*karena yang penting bukan di count dari sqlnya melainkan jumlah row yang didapat dari query ini*/
    }
}

#getAmount= Script to help you get a sum of data with certain condition (sum function)

#Arguments
#table = String, Table Name (ex: "tbl_user")
#column = String, Primary key column (ex: "id_user")
#where = Key-Value Array, Condition. [ex: array("status_user" => "ACTIVE")]

#return
#int/float, depends of data that is summed

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

#selectRowBetweenDates= Script to help you get data between 2 dates condition. This script is intended to be used for a table structure like below.
/*
activity  | activity_date
activity1 | 01-03-2019
activity2 | 05-03-2019
activity3 | 08-03-2019
*/
#cont.. And you want to know what activity you've done between 05-03-2019 and 08-03-2019.

#Arguments
#table = String, Table Name (ex: "tbl_user")
#kolom_tgl = String, column that holds date condition
#constraint = Key-Value Array, Date values [ex: array("awal"=>"01-01-2019","akhir"=>"31-12-2019")] Please using your own database date-format (it could be dd-mm-yyyy / yyyy-mm-dd / mm-dd-yyyy)
#where = Key-Value Array, Condition. [ex: array("status_user" => "ACTIVE")]
#field = String Array, Column that will be selected [ex: array("col1","col2","col3")]
#group_by = String Array, Column that will be grouped by [ex: array("col1","col2")]

#return
#data between given dates

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

#executeQuery= Script to help you execute queries.

#Arguments
#query = String, Executabe query (execute first in your DBMS to know whether the query is valid or not) [ex: "select col1,col2,col3 from tbl_user where id_user = '123'"]

if(! function_exists('executeQuery')){
    function executeQuery($query){
        
        $CI =& get_instance();
        return $CI->db->query($query);
    }
}

?>