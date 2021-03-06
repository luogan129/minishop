<?php
// +----------------------------------------------------------------------
// | Minishop [ Easy to handle for Micro businesses ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.qasl.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: tangtanglove <dai_hang_love@126.com> <http://www.ixiaoquan.com>
// +----------------------------------------------------------------------

use think\Db;

/**
 * key-value键值对操作方法库
 */

/**
 * 插入key_value通用方法
 * $collection 关联表
 * $data keyvalud键值对
 * $uuid 系统唯一标示符
 * @author  tangtanglove
 */
function insert_key_value($collection,$data,$uuid = 'default')
{
    if (empty($collection) || empty($data)) {
        return false;
    }
    // 初始化结果
    $result = true;
    $data = json_encode($data);    
    $info = Db::name('KeyValue')->where(['collection' => $collection,'uuid' => $uuid])->find();
    // 已存在信息，更新value里的json值，添加键值对
    if($info){
        $result = Db::name('KeyValue')->where(['collection' => $collection,'uuid' => $uuid])->setField('value',$data);
    }else{
        // 不存在信息，添加新数据
        $result = Db::name('KeyValue')->insert(['collection' => $collection,'uuid' => $uuid,'value' =>  $data]);
    }    
    // 返回插入结果
    return $result;
}

/**
 * 更新key_value通用方法
 * $collection 关联表
 * $data keyvalud键值对
 * $uuid 系统唯一标示符
 * @author  tangtanglove
 */
function update_key_value($collection,$data,$uuid = 'default')
{
    if (empty($collection) || empty($data)) {
        return false;
    }
    // 初始化结果
    $result = 0;
    // 组合条件
    $map['collection']  = $collection;
    $map['uuid']        = $uuid;
    $data = json_encode($data);    
    Db::name('KeyValue')->where(['collection' => $collection,'uuid' => $uuid])->delete();
    $info = Db::name('KeyValue')->where(['collection' => $collection,'uuid' => $uuid])->find();
    // 已存在信息，更新value里的json值，添加键值对    
    if($info){
        $result = Db::name('KeyValue')->where(['collection' => $collection,'uuid' => $uuid])->setField('value',$data);
    }else{
        // 不存在信息，添加新数据
        $result = Db::name('KeyValue')->insert(['collection' => $collection,'uuid' => $uuid,'value' =>  $data]);
    }    
    // 返回插入结果
    return $result;
}

/**
 * 获取key_value数组通用方法
 * $collection 关联表
 * $uuid 系统唯一标示符
 * @author  tangtanglove
 */
function select_key_value($collection,$uuid = '')
{
    if (empty($collection)) {
        return false;
    }
    // 组合条件
    $map['collection']  = $collection;
    if (!empty($uuid)) {
        $map['uuid']        = $uuid;
    }
    // 查询数据
    $getKeyValueList    = Db::name('KeyValue')->where($map)->find();
    if (!empty($getKeyValueList)) {        
        $data = json_decode($getKeyValueList['value'],true);
    } else {
        return false;
    }    
    return $data;
}

/**
 * 获取一条key_value数组通用方法
 * $collection 关联表
 * $uuid 系统唯一标示符
 * @author  tangtanglove
 */
function find_key_value($collection,$uuid = 'default',$name='')
{
    if (empty($collection)) {
        return false;
    }
    // 组合条件
    $map['collection']  = $collection;
    $map['uuid']        = $uuid;    
    // 查询数据
    $getKeyValueInfo    = Db::name('KeyValue')->where($map)->find();    
    if (!empty($getKeyValueInfo)) {
        // 返回结果
        $data = json_decode($getKeyValueInfo['value'],true);
        if(!$data){
            return false;
        }
        if(!empty($name)){
            return $data[$name];
        }else{            
            foreach ($data as $key => $value) {
                $data = $value;
            }
            return $data;
        }
    } else {
        return false;
    }
} 

/**
 * 删除key_value数组通用方法
 * $collection 关联表
 * $uuid 系统唯一标示符
 * @author  tangtanglove
 */
function delete_key_value($collection,$uuid = 'default')
{
    if (empty($collection)) {
        return false;
    }
    // 组合条件
    $map['collection']  = $collection;
    $map['uuid']        = $uuid;
    // 删除数据
    return  Db::name('KeyValue')->where($map)->delete();
}

/**
 * 通过UUID获取val值
 * $length 截取的长度，为0则不截取
 * @return [type] [description]
 */
function get_val($uuid,$name,$length=0){
    $map['uuid']    = $uuid;
    $value = Db::name('KeyValue')->where($map)->value('value');
    $array = json_decode($value,true);
    if(array_key_exists($name, $array)){
        $string = $array[$name];
    }else{
        return false;
    }
    if($length>0){
        if(mb_strlen($string,'utf8')>$length){
            $result = mb_substr($string,0,$length,'utf-8')."...";
        }else{
            $result = $string;  
        }
    }
    if($result){        
        return $result;
    }else{
        return false;
    }
    
}