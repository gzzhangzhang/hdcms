<?php

/**
 * 管理员个人信息管理模块
 * Class AdminControl
 * @author 向军 <houdunwangxj@gmail.com>
 */
class PersonalControl extends AuthControl
{
    //操作模型
    private $_db;

    //构造函数
    public function __construct()
    {
        $this->_db = K('Personal');
    }

    /**
     * 编辑个人信息
     */
    public function edit_info()
    {
        if (IS_POST) {
            if ($this->_db->edit_info()) {
                $this->_ajax(1, '修改个人资料成功');
            }
        } else {
            $this->user = $this->_db->find(session('uid'));
            $this->display();
        }
    }

    /**
     * 修改密码
     */
    public function edit_password()
    {
        if (IS_POST) {
            $_POST['code'] = get_user_code();
            $_POST['password'] = get_user_password($_POST['new_password'], $_POST['code']);
            $_POST['uid'] = session('uid');
            if ($this->_db->save()) {
                $this->_ajax(1, '修改修改密码成功');
            }
        } else {
            $this->user = $this->_db->find(session('uid'));
            $this->display();
        }
    }

    /**
     * 修改密码操作时Ajax验证密码
     */
    public function check_password()
    {
        $user = $this->_db->find(session('uid'));
        $this->_db->where('uid=' . session('uid'));
        $password = get_user_password($_POST['old_password'], $user['code']);
        $this->_db->where("password='$password'");
        if ($this->_db->find()) {
            $this->ajax(1);
        }
        exit;
    }
}
