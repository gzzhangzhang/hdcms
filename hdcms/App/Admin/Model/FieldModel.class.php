<?php
class FieldModel extends Model
{
    public $table = "model_field";

    /**
     * 添加一个字段
     * @param array $field 字段信息
     * @return bool
     */
    public function addField($field)
    {
        //获得表名
        $db = M("model")->find($field['mid']);
        $table = $field['is_main_table'] ? $db['tablename'] : $db['tablename'] . '_data';
        //清除表缓存
        F(C("DB_DATABASE") . C("DB_PREFIX") . $table, NULL, TABLE_PATH);
        //字段所在表
        $field['table_name'] = $table;
        //字段html视图显示信息
        $field['set'] = var_export($field['set'], true);
        //根据参数$_POST['is_main_table']修改请表或副表的表结构
        $this->alterTable($field);
        //添加字段信息到model_field表
        $this->add($field);
        //将字段信息缓存，缓存名是模型mid
        return $this->updateCache($field['mid']);
    }

    /**
     * 修改表结构
     * @param array $field 字段信息
     */
    private function alterTable($field)
    {

        switch ($field['field_type']) {
            case "char":
            case "varchar":
                $_field = $field['field_name'] . " " . $field['field_type'] . "(255)";
                break;
            case "text":
                $_field = $field['field_name'] . " " . $field['field_type'];
                break;
            case "decimal":
                $_field = $field['field_name'] . " " . $field['field_type'] . "(" . $field['set']['integer'] . "," . $field['set']['decimal'] . ")";
                break;
            default:
                $_field = $field['field_name'] . " " . $field['field_type'];
                break;
        }
        //是否已经存在字段
        $is_have = $this->is_field($field['field_name'], $field['table_name']);
        if ($is_have) {
            $sql = "ALTER TABLE " . C("DB_PREFIX") . $field['table_name'] . " CHANGE " . $field['field_name'] . " " . $_field;
        } else {
            $sql = "ALTER TABLE " . C("DB_PREFIX") . $field['table_name'] . " ADD " . $_field;
        }
        $this->exe($sql);
    }

    /**
     * 更新字段缓存
     * @param int $mid 模型mid
     * @return bool
     */
    public function updateCache($mid)
    {
        //获得当前模型所有表单信息
        $field = M("model_field")->all("mid=$mid");
        if (empty($field)) {
            return F($mid, NULL, './data/field/');
        }
        foreach ($field as $k => $f) {
            eval("\$field[\$k]['set']=" . $f['set'] . ';');
            $field[$k]['html'] = $this->getHtml($field[$k]);
        }
        //缓存字段信息
        return F($mid, $field, './data/field/');
    }

    /**
     * 获得表单的Html表示
     * @param array $f 表单信息
     * @return string
     */
    private function getHtml($f)
    {
        $html = '';
        //表单name值
        $name = $f['is_main_table'] == 1 ? $f['field_name'] : $f['table_name'] . "[{$f['field_name']}]";
        switch ($f['show_type']) {
            case "input":
                $html = "<tr>
                <th>{$f['title']}</th>
                <td><input name='$name' value='{FIELD_VALUE}' size='{$f['set']['size']}'
                 css='{$f['css']}'/><span class='validation'>{$f['message']}</span>
                 </td></tr>";
                break;
            case "image":
                $html = "<tr>
                <th>{$f['title']}</th>
                <td><input name='$name' lab='pic_{$f['field_name']}' style='width:300px' value='{FIELD_VALUE}'/>
                 <input class='inputbut' type='button' onclick='selectImage(this)' value='浏览...'>
                 </td></tr>";
                break;
            case "textarea":
                $html = "<tr>
                <th>{$f['title']}</th>
                <td><textarea name='$name' style=\"width:{$f['set']['width']}px;height:{$f['set']['height']}px;\"
                 css='{$f['css']}'/>{FIELD_VALUE}</textarea><span class='validation'>{$f['message']}</span>
                 </td></tr>";
                break;
            case "num":
                $html = "<tr>
                <th>{$f['title']}</th>
                <td><input name='$name' value='{FIELD_VALUE}' size='{$f['set']['size']}'
                 css='{$f['css']}'/><span class='validation'>{$f['message']}</span>
                 </td></tr>";
                break;
            case "datetime":
                $html = "<tr><th>{$f['title']}</th>
                <td><input name='$name' id='date_$name' value='{FIELD_VALUE}' size='{$f['set']['size']}'
                 css='{$f['css']}'/><span class='validation'>{$f['message']}</span>";
                $html .= "<script>
                        $(function(){
                        var dateFormat = {
                        dateFormat: 'yy-mm-dd'
                        ,monthNames: [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ]
                        ,dayNamesMin: [ '日', '一', '二', '三', '四', '五', '六' ]
                        };
                        $('#date_$name').datepicker(dateFormat);
                        });
                        </script>";
                $html .= "</td></tr>";
                break;
            case "select":
                $param = explode(",", $f['set']['param']);
                $html = "<tr>
                <th>{$f['title']}</th><td>";
                if ($f['set']['type'] == 'select') {
                    "<select name='$name'>";
                }
                foreach ($param as $p) {
                    $s = explode("|", $p); //男|1,女|0
                    $checked = $f['set']['default'] == $s[1] ? "checked='checked'" : "";
                    switch ($f['set']['type']) {
                        case "radio":
                            $html .= " <input type='radio' name='$name' value='{$s[1]}' {$checked} css='{$f['css']}'/> " . $s[0];
                            break;
                        case "checkbox":
                            $html .= " <input type='checkbox' name='$name' value='{$s[1]}' {$checked} css='{$f['css']}'/> " . $s[0];
                            break;
                        case "select":
                            $html .= " <option value='{$s[1]}' $checked>{$s[0]}</option>";
                            break;
                    }
                }
                if ($f['set']['type'] == 'select') {
                    "</select>";
                }
                $html .= "<span class='validation'>{$f['message']}</span></td></tr>";
                break;
            case "editor":
                $html = "<tr><th>{$f['title']}</th><td>";
                $html .= <<<str
<script id="hd_$name" name="$name" type="text/plain"></script>
    <script type='text/javascript'>
        var ue = UE.getEditor('hd_$name',{
        imageUrl:url_method//处理上传脚本
        ,zIndex : 0
        ,autoClearinitialContent:false
        ,initialFrameWidth:"100%" //宽度1000
        ,initialFrameHeight:"{$f['set']['height']}" //宽度1000
        ,autoHeightEnabled:false //是否自动长高,默认true
        ,autoFloatEnabled:false //是否保持toolbar的位置不动,默认true
        ,initialContent:'{FIELD_VALUE}' //初始化编辑器的内容 也可以通过textarea/script给值
    });
        </script>
str;
                $html .= "<span class='validation'>{$f['message']}</span>";
                $html .= "</td></tr>";
                break;
            case "image":

                break;
        }
        return $html;
    }

    /**
     * 格式化表单，替换初始值等操作
     */
    public function replaceValue($field, $value = null)
    {
        //默认值
        $value = $value == null ? $field['set']['default'] : $value;
        $html = '';
        switch ($field['show_type']) {
            case "input":
            case "textarea":
            case "num":
            case "editor":
            case "datetime":
            case "image":
                $html = str_replace("{FIELD_VALUE}", $value, $field['html']);
                break;
        }
        return $html;
    }
}

?>