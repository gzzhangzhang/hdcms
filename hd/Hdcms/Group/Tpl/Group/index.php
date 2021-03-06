<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>会员组列表</title>
    <hdjs/>
</head>
<body>
<div class="wrap">
    <div class="menu_list">
        <ul>
            <li><a href="javascript:;" class="action">会员组列表</a></li>
            <li><a href="{|U:'add'}">添加会员组</a></li>
        </ul>
    </div>
    <table class="table2 hd-form">
        <thead>
        <tr>
            <td class="w30">gid</td>
            <td>会员组名</td>
            <td class="w150">系统组</td>
            <td class="w150">积分小于</td>
            <td class="w150">允许投稿</td>
            <td class="w150">投稿不需要审核</td>
            <td class="w150">允许发短消息</td>
            <td class="w100">操作</td>
        </tr>
        </thead>
        <list from="$data" name="d">
            <tr>
                <td>{$d.gid}</td>
                <td>
                    {$d.gname}
                </td>
                <td>
                    <if value="$d.system_group">
                        <font color="red">√</font>
                        <else/>
                        ×
                    </if>
                </td>
                <td>{$d.point}</td>
                <td>
                    <if value="$d.allowpost">
                        <font color="red">√</font>
                        <else/>
                        ×
                    </if>
                </td>
                <td>
                    <if value="$d.allowpostverify">
                        <font color="red">√</font>
                        <else/>
                        ×
                    </if>
                </td>
                <td><if value="$d.allowsendmessage">
                        <font color="red">√</font>
                        <else/>
                        ×
                    </if></td>
                <td>
                    <a href="{|U:'edit',array('gid'=>$d['gid'])}">修改</a>
                    <if value="$d.system_group eq 0">
                        <span class="line">|</span>
                        <a href="javascript:del_category({$c.cid})">删除</a>
                    </if>
                </td>
            </tr>
        </list>
    </table>
    <div class="h60"></div>
</div>
</body>
</html>