<?php
/* Smarty version 3.1.30, created on 2017-11-15 03:02:39
  from "D:\test\monitor\data\smarty\tpl\recordEdit.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a0c02afc9d068_83193630',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'af1e03d26a2b91be1fbc24366ad00290050bf3e9' => 
    array (
      0 => 'D:\\test\\monitor\\data\\smarty\\tpl\\recordEdit.tpl',
      1 => 1508728777,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5a0c02afc9d068_83193630 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_html_options')) require_once 'D:\\test\\monitor\\lib\\smarty\\libs\\plugins\\function.html_options.php';
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<form method="post" action="/set/recordEdit.php" class="form-horizontal" role="form" id="myform">
    <input type="hidden" name="update" value="true"/>
    <input type="hidden" name="Id" value="<?php echo $_smarty_tpl->tpl_vars['recordId']->value;?>
"/>
    <div class="form-group">
        <label class="col-sm-2 control-label">Name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Name" placeholder="请输入名字" value="<?php echo $_smarty_tpl->tpl_vars['recordList']->value['Name'];?>
">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Description" placeholder="请输入描述" value="<?php echo $_smarty_tpl->tpl_vars['recordList']->value['Description'];?>
">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">ProjectId</label>
        <div class="col-sm-6">
            <select name="ProjectId" class="form-control">
                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['projectOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['recordList']->value['ProjectId']),$_smarty_tpl);?>

            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Content</label>
    </div>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['attributeOptionArray']->value, 'attributeInfo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['attributeInfo']->key => $_smarty_tpl->tpl_vars['attributeInfo']->value) {
$__foreach_attributeInfo_0_saved = $_smarty_tpl->tpl_vars['attributeInfo'];
?>
        <?php if ($_smarty_tpl->tpl_vars['attributeInfo']->key == "Url") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Url&nbsp;&nbsp;<span style="color:red">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Url" placeholder="请输入名字" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['Url']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['Url']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "Type") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Type</label>
                <div class="col-sm-2">
                    <select name="Type" class="form-control">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['TypeOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['contentArray']->value['Type']['Value']),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "LoginMethodId") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">LoginMethodId</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="LoginMethodId" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['LoginId']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['LoginId']['Value'];
}?>">
                </div>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "Method") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Method</label>
                <div class="col-sm-2">
                    <select name="Method" class="form-control">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MethodOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['contentArray']->value['Method']['Value']),$_smarty_tpl);?>

                    </select>
                </div>
            </div>

        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "Params") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Params</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Params" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['Params']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['Params']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "HttpCode") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">HttpCode</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="HttpCode" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['HttpCode']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['HttpCode']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "Header") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Header</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Header" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['Header']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['Header']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "ResponseTime") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">ResponseTime</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ResponseTime" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['ResponseTime']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['ResponseTime']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "ContentSize") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">ContentSize</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ContentSize" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['ContentSize']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['ContentSize']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "WhiteList") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['WhiteList']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['WhiteList']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "WhiteList2") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList2" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['WhiteList2']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['WhiteList2']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "WhiteList3") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList3</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="WhiteList3" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['WhiteList3']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['WhiteList3']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "BlackList") {?>
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="BlackList" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['BlackList']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['BlackList']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['attributeInfo']->key == "BlackList2") {?> 
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="BlackList2" value="<?php if (!empty($_smarty_tpl->tpl_vars['contentArray']->value['BlackList2']['Value'])) {
echo $_smarty_tpl->tpl_vars['contentArray']->value['BlackList2']['Value'];
}?>">
                </div>
                <p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['attributeInfo']->value['ContentType'];?>
</p>
            </div>
        <?php }?>
    <?php
$_smarty_tpl->tpl_vars['attributeInfo'] = $__foreach_attributeInfo_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    

    <div class="form-group">
        <label class="col-sm-2 control-label">batchId</label>
        <div class="col-sm-4">
            <select name="batchId" class="form-control" id="BatchId">
                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['batchOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['recordList']->value['Batch']),$_smarty_tpl);?>

            </select>
        </div>
    </div>

    <div class="form-group" <?php if ($_smarty_tpl->tpl_vars['recordList']->value['Batch'] != 0) {?> style="display: none" <?php }?> id="otherBatch">
        <label  class="col-sm-2 control-label">每</label>
        <div class="col-lg-2">
            <input type="text" class="form-control" name="otherBatch" id="otherBatchVal" value="<?php if ($_smarty_tpl->tpl_vars['recordList']->value['Batch'] == 0) {
echo $_smarty_tpl->tpl_vars['recordList']->value['CromMinute'];
}?>">
        </div>
        <label  class="control-label">分钟</label>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">notifyType</label>
        <div class="col-sm-6">
            <select name="notifyType" class="form-control">
                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['notifyTypeOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['notifyType']->value),$_smarty_tpl);?>

            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">notifyObject</label>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Addressee&nbsp;&nbsp;<span style="color:red">*</span></label>
        <div class="col-lg-10">
            <div class="checkbox" class="col-lg-2">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mailOptionArray']->value, 'mailInfo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mailInfo']->value) {
?>
                    <label><input type="checkbox" name="Addressee[]" value="<?php echo $_smarty_tpl->tpl_vars['mailInfo']->value['Id'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['NotifyObjectArray']->value['Addressee']) && in_array($_smarty_tpl->tpl_vars['mailInfo']->value['Id'],$_smarty_tpl->tpl_vars['NotifyObjectArray']->value['Addressee'])) {?>checked="checked"<?php }?>><?php echo $_smarty_tpl->tpl_vars['mailInfo']->value['Mail'];?>
</label>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-lg-10">
            <div class="checkbox" class="col-lg-2">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mailOptionArray']->value, 'mailInfo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['mailInfo']->value) {
?>
                    <label><input type="checkbox" name="CC[]" value="<?php echo $_smarty_tpl->tpl_vars['mailInfo']->value['Id'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['NotifyObjectArray']->value['CC']) && in_array($_smarty_tpl->tpl_vars['mailInfo']->value['Id'],$_smarty_tpl->tpl_vars['NotifyObjectArray']->value['CC'])) {?>checked="checked"<?php }?>><?php echo $_smarty_tpl->tpl_vars['mailInfo']->value['Mail'];?>
</label>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonTitle</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" name="commonTitle" value="<?php if (!empty($_smarty_tpl->tpl_vars['NotifyObjectArray']->value['commonTitle'])) {?> <?php echo $_smarty_tpl->tpl_vars['NotifyObjectArray']->value['commonTitle'];
}?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonBody</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" name="commonBody" value="<?php if (!empty($_smarty_tpl->tpl_vars['NotifyObjectArray']->value['commonBody'])) {?> <?php echo $_smarty_tpl->tpl_vars['NotifyObjectArray']->value['commonBody'];
}?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">AlertLimit&nbsp;&nbsp;<span style="color:red">*</span></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="alertLimit" placeholder="请输入预警上限次数" value="<?php echo $_smarty_tpl->tpl_vars['recordList']->value['AlertLimit'];?>
">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">status</label>
        <div class="col-sm-6">
            <select name="status" class="form-control">
                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['StatusOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['status']->value),$_smarty_tpl);?>

            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-1">
            <button type="submit" class="btn btn-default">确认<tton>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <a href="/set/recordList.php" class="btn btn-default">取消</a>
        </div>
    </div>
</form>

<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
