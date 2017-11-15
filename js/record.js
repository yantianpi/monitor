$(document).ready(function () { 
    $("#BatchId").bind("change",function(){ 
        var options=$("#BatchId option:selected").text();
        if ( options == 'other' || options == 'exclusive' ){
            $("#otherBatch").show();
        }else{
            $("#otherBatch").hide();
        }
    }); 
    $(".preg").bind("change",function(){
        var preg = $(this).val();
        try{
            window.RegExp(preg)
        }catch(e){
            alert('正则语法错误！')
        }
    });
});

$(function () {
    $('form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
        　　},
        fields: {
            'Url': {
                message: 'Url验证失败',
                validators: {
                    notEmpty: {
                        message: 'Url不能为空'
                    },
                    uri: {
                        message: '请输入正确的url地址！',
                        'default': '请输入一个有效的URL地址'
                    },
                }
            },
            'otherBatch': {
                message: 'otherTime验证失败',
                validators: {
                    notEmpty: {
                        message: 'otherTime不能为空'
                    },
                    between: {
                        min: 10,
                        max: 60,
                        message: '请输入10到60之间的整数!'
                    }
                }
            },
            'alertLimit': {
                message: 'AlertLimit验证失败',
                validators: {
                    notEmpty: {
                        message: 'AlertLimit不能为空'
                    },
                    between: {
                        min: 1,
                        max: 100,
                        message: '请输入1到100之间的整数!'
                    }
                }
            },
            'Addressee[]': {
                message: 'Addressee验证失败',
                validators: {
                    choice: {
                        min: 1,
                        message: '请至少选择一个收件人'
                    }
                }
            },
        }
    });
});
