$(function () {
    $("form").validate({
        'gname': {
            rule: {
                required: true,
                ajax: {url: CONTROL + '&m=check_gname', field: ['gid']}
            },
            error: {
                required: '组名不能为空',
                ajax: '会员组已经存在'
            }
        },
        'point': {
            rule: {
                required: true,
                regexp: /^\d+$/
            },
            error: {
                required: '积分不能为空',
                regexp: '必须为数字'
            }
        }
    })
})