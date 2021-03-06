<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>会员列表</title>
    <hdjs/>
</head>
<body>
<div class="wrap">
    <div class="menu_list">
        <ul>
            <li><a href="javascript:;" class="action">会员列表</a></li>
            <li><a href="{|U:'add'}">添加会员</a></li>
        </ul>
    </div>
    <table class="table2 hd-form">
        <thead>
        <tr>
            <td class="w30">uid</td>
            <td class="w200">昵称</td>
            <td>帐号</td>
            <td class="w150">登录时间</td>
            <td class="w150">登录IP</td>
            <td class="w150">已审核</td>
            <td class="w150">积分</td>
            <td class="w100">操作</td>
        </tr>
        </thead>
        <list from="$data" name="d">
            <tr>
                <td>{$d.uid}</td>
                <td>
                    {$d.nickname}
                </td>
                <td>
                    {$d.username}
                </td>
                <td>{$d.logintime}</td>
                <td>{$d.ip}</td>
                <td>
                    <if value="$d.status">
                        <font color="red">√</font>
                        <else/>
                        ×
                    </if>
                </td>
                <td>{$d.credits}</td>
                <td>
                    <a href="{|U:'edit',array('uid'=>$d['uid'])}">修改</a>
                    <span class="line">|</span>
                    <a href="javascript:confirm('删除用户将删除所有信息,确定删除吗？')?hd_ajax('{|U:'del'}',{uid:{$d.uid}}):''">删除</a>
                </td>
            </tr>
        </list>
    </table>
    <div class="h60"></div>
</div>
</body>
</html>