<?php

//模板管理模块
class TemplateControl extends AuthControl
{
    public function __init()
    {
        parent::__init();
    }

    //模板风格列表
    public function style_list()
    {
        $style = array();
        foreach (glob("./template/*") as $tpl) {
            //去除plug公共模板目录
            if (strstr($tpl, 'plug')) continue;
            //说明文档
            $readme = $tpl . '/readme.txt';
            if (is_file($readme) && is_readable($readme)) {
                $readme = trim(preg_replace('@#.*@im', "", file_get_contents($readme)));
                $config = preg_split('@\n@', $readme);
            } else {
                $config = array("HDCMS免费模板", "后盾网", "houdunwang.com");
            }
            //模板目录名
            $config['dir_name'] = basename($tpl);
            //模板缩略图
            if (is_file($tpl . '/template.jpg')) {
                $config['img'] = $tpl . '/template.jpg';
            } else {
                $config['img'] = __CONTROL_TPL__ . '/img/default.jpg';
            }
            //正在使用的模板
            if (C("WEB_STYLE") == $config['dir_name']) {
                $config['current'] = true;
            }
            $style[] = $config;
        }
        $this->style = $style;
        $this->display();
    }

    //选择模板风格（使用模板）
    public function select_style()
    {
        $dir_name = Q("dir_name");
        if ($dir_name) {
            import('Config.Model.ConfigModel');
            $db = K("config");
            K("config")->join()->where("name='WEB_STYLE'")->save(array(
                "value" => $dir_name
            ));
            //更新配置文件
            $db->update_config_file();
            //删除前台编译文件
            is_dir("./temp/hdcms/Content/Compile") and Dir::del("./temp/hdcms/Content/Compile");
            //删除编译文件
            is_dir('temp/Hdcms/Index') and dir::del('temp/Hdcms/Index');
            $this->ajax(array('state' => 1, 'message' => '操作成功'));
        }
    }

    //读取模板目录
    public function show_dir()
    {
        $dir_name = "./template/" . Q("get.dir_name", C("WEB_STYLE"));
        $dirs = Dir::tree($dir_name, 'html');
        $this->assign("dirs", $dirs);
        $this->display();
    }

    //编辑模板
    public function edit_tpl()
    {
        if (IS_POST) {
            //检测模板文件写权限
            if (!is_writable($_POST['file_path'])) {
                $this->ajax(2);
            }
            //新文件名
            $new = dirname($_POST['file_path']) . '/' . $_POST['file_name'] . '.html';
            //修改文件名
            rename($_POST['file_path'], $new);
            //修改模板内容
            if (file_put_contents($new, $_POST['content'])) {
                $this->_ajax(1,'修改成功');
            }else{
                $this->_ajax(1,'修改失败，请修改模板文件为可写');
            }
        } else {
            $file_path = Q("get.file_path", "", "urldecode");
            $content = file_get_contents($file_path);
            //模板文件详细信息
            $info = pathinfo($file_path);
            $field = array(
                "file_path" => $file_path,
                "file_name" => $info['filename'],
                "content" => $content
            );
            $this->assign("field", $field);
            $this->display();
        }
    }

    //选择模板文件（内容页与栏目管理页使用)
    public function select_tpl()
    {
        //模板目录
        $stylePath = ROOT_PATH . 'template/' . C("WEB_STYLE");
        $path = Q("get.path", $stylePath);
        $file = Dir::tree($path, "html");
        foreach ($file as $n => $v) {
            if ($v['type'] == 'dir') {
                $file[$n]['path'] = $v['path'];
            } else {
                $file[$n]['path'] = str_replace($stylePath, '{style}', $v['path']);
            }
        }
        $history = "";
        if ($dir = Q("get.path")) {
            if ($dir == $stylePath) {
                $history = "";
            } else {
                $history = __METH__ . '&path=' . dirname($dir);
            }
        }
        $this->assign("history", $history);
        $this->assign("file", $file);
        $this->display();
    }
}