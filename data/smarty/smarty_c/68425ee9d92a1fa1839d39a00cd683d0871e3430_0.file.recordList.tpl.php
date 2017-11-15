<?php
/* Smarty version 3.1.30, created on 2017-11-15 03:04:55
  from "D:\test\monitor\data\smarty\tpl\recordList.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a0c033755a771_38515809',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '68425ee9d92a1fa1839d39a00cd683d0871e3430' => 
    array (
      0 => 'D:\\test\\monitor\\data\\smarty\\tpl\\recordList.tpl',
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
function content_5a0c033755a771_38515809 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_html_options')) require_once 'D:\\test\\monitor\\lib\\smarty\\libs\\plugins\\function.html_options.php';
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title" >
                    Test Message
                </h4>
            </div>
            <div class="modal-body" id="mess">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    关闭
                </button>
            </div>
        </div>
    </div>
</div>

<form method="get" action="/set/recordList.php" class="form-inline form">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordId" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;recordId</label>
                    <input name="recordId" value="<?php echo $_smarty_tpl->tpl_vars['recordId']->value;?>
" type="text" class="form-control" id="recordId"/>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="categoryId" class="control-label">category</label>
                    <select name="categoryId" class="form-control" id="categoryId">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['categoryOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['categoryId']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="projectId" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;project</label>
                    <select name="projectId" class="form-control" id="projectId">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['projectOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['projectId']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="form-group">
                    <label for="batchId" class="control-label">batch</label>
                    <select name="batchId" class="form-control" id="batchId">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['batchOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['batchId']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordName" class="control-label">recordName</label>
                    <input name="recordName" value="<?php echo $_smarty_tpl->tpl_vars['recordName']->value;?>
" type="text" class="form-control" id="recordName"/>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordStatus" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;status</label>
                    <select name="recordStatus" class="form-control" id="recordStatus">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['recordStatusArray']->value,'selected'=>$_smarty_tpl->tpl_vars['recordStatus']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordRunStatus" class="control-label">runStatus</label>
                    <select name="recordRunStatus" class="form-control" id="recordRunStatus">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['recordRunStatusArray']->value,'selected'=>$_smarty_tpl->tpl_vars['recordRunStatus']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-7"></div>
        </div>
        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="order" class="control-label">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order
                    </label>
                    <select name="order" class="form-control" id="order">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['orderOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['order']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="sort" class="control-label">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sort
                    </label>
                    <select name="sort" class="form-control" id="sort">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['sortOptionArray']->value,'selected'=>$_smarty_tpl->tpl_vars['sort']->value),$_smarty_tpl);?>

                    </select>
                </div>
            </div>
            <div class="col-xs-14"></div>
        </div>
        <div class="row">
            <div class="col-xs-24">
                <button class="btn btn-default btn-info" type="submit">Query</button>
            </div>
        </div>
    </div>
</form>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-4">
            <a href="/set/recordAdd.php">add url record</a>
        </div>
        <div class="col-xs-8"></div>
        <div class="col-xs-12"><?php echo $_smarty_tpl->tpl_vars['pagebar']->value;?>
</div>
    </div>
    <div class="row">
        <div class="col-xs-24">
            <table class="table table-striped table-hover">
                <tbody>
                    <td>ID</td>
                    <td>Category</td>
                    <td>Project</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>CronTime</td>
                    <td>Batch</td>
                    <td>NotifyInfo</td>
                    <td>Statistic</td>
                    <td>Time</td>
                    <td>Status</td>
                    <td>Operation</td>
                </tbody>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['recordList']->value, 'recordInfo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['recordInfo']->value) {
?>
                    <tr class="info">
                        <td><?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Id'];?>
</td>
                        <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['recordInfo']->value['CategoryAlias'])===null||$tmp==='' ? 'unknow' : $tmp);?>
</td>
                        <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['recordInfo']->value['ProjectName'])===null||$tmp==='' ? 'unknow' : $tmp);?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Name'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Description'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['CronTime'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Batch'];?>
</td>
                        <td>
                            <?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['NotifyType'];?>

                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['recordInfo']->value['NotifyObjectInfo'], 'NotifyInfo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['NotifyInfo']->key => $_smarty_tpl->tpl_vars['NotifyInfo']->value) {
$__foreach_NotifyInfo_1_saved = $_smarty_tpl->tpl_vars['NotifyInfo'];
?>
                                <hr style="width:%95;" />
                                    <?php echo $_smarty_tpl->tpl_vars['NotifyInfo']->key;?>
 : <?php echo $_smarty_tpl->tpl_vars['NotifyInfo']->value;?>

                            <?php
$_smarty_tpl->tpl_vars['NotifyInfo'] = $__foreach_NotifyInfo_1_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                        </td>
                        <td>
                            monitor:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['MonitorCount'];?>
<br />
                            alert:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['AlertCount'];?>

                            <hr style="width:%95;" />
                            seriesalert:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['SeriesAlertCount'];?>
<br />
                            limit:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['AlertLimit'];?>

                        </td>
                        <td>
                            lastmonitor:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['LastMonitorTime'];?>
<br />
                            lastalert:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['LastAlertTime'];?>

                            <hr style="width:%95;" />
                            start:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['StartTime'];?>
<br />
                            end:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['EndTime'];?>

                            <hr style="width:%95;" />
                            add:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['AddTime'];?>
<br />
                            update:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['UpdateTime'];?>
<br />
                            timestamp:<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Timestamp'];?>

                        </td>
                        <td>
                            <?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Status'];?>

                            <hr style="width:%95;" />
                            <?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['RunStatus'];?>

                        </td>
                        <td>
                            <a href="/set/recordEdit.php?id=<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Id'];?>
" class="btn btn-default">Edit</a>
                            <hr style="width:%95;" />
                            <?php ob_start();
echo $_smarty_tpl->tpl_vars['recordInfo']->value['Status'];
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == "ACTIVE") {?>
                                <a href="/set/recordChangeStatus.php?id=<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Id'];?>
&type=delete" class="btn btn-default">Delete</a>
                            <?php } else {
ob_start();
echo $_smarty_tpl->tpl_vars['recordInfo']->value['Status'];
$_prefixVariable2=ob_get_clean();
if ($_prefixVariable2 == "INACTIVE") {?>
                                <a href="/set/recordChangeStatus.php?id=<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Id'];?>
&type=recovery" class="btn btn-default">Recovery</a>
                            <?php }}?>
                            <hr style="width:%95;" />
                            <button type="button" class="btn btn-primary btn-lg click" id="<?php echo $_smarty_tpl->tpl_vars['recordInfo']->value['Id'];?>
">Test</button>
                        </td>
                    </tr>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <a href="/set/recordAdd.php">add url record</a>
        </div>
        <div class="col-xs-8"></div>
        <div class="col-xs-12"><?php echo $_smarty_tpl->tpl_vars['pagebar']->value;?>
</div>
    </div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
