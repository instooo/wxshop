<?php
/**
 * RBAC配置
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2016/11/2
 */

return array(
    'RBAC_MAX_LEVEL'=>5,
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, //实时认证
    'USER_AUTH_KEY' => 'authId',
    'ADMIN_AUTH_KEY' => 'administrator',
    'USER_AUTH_MODEL' => 'user',//用户表
    'AUTH_PWD_ENCODER' => 'md5',
    'USER_AUTH_GATEWAY' => '/Public/Login',
    'NOT_AUTH_MODULE' => 'Public,Common,Index', //不验证的模块
    'REQUIRE_AUTH_MODULE' => '',
    'NOT_AUTH_ACTION' => 'readlog',
    'REQUIRE_AUTH_ACTION' => '',
    'GUEST_AUTH_ON' => false, //游客不允许登录
    'GUEST_AUTH_ID' => 0,
    'RBAC_ROLE_TABLE' => 'mygame_role',
    'RBAC_USER_TABLE' => 'mygame_role_user',
    'RBAC_ACCESS_TABLE' => 'mygame_access',
    'RBAC_NODE_TABLE' => 'mygame_node',
);