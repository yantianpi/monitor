<?php
/* Smarty version 3.1.30, created on 2017-11-15 03:02:39
  from "D:\test\monitor\data\smarty\tpl\header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a0c02afda2c65_78436640',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8322191ae94f5cdda13c8520c6d6dcb52232ee30' => 
    array (
      0 => 'D:\\test\\monitor\\data\\smarty\\tpl\\header.tpl',
      1 => 1508728777,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a0c02afda2c65_78436640 (Smarty_Internal_Template $_smarty_tpl) {
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/common.css" rel="stylesheet" type="text/css">
    <link href="/css/header.css" rel="stylesheet" type="text/css">
    <?php if (isset($_smarty_tpl->tpl_vars['pageTag']->value) && $_smarty_tpl->tpl_vars['pageTag']->value == 'recordList') {?>
        <link href="/css/recordList.css" rel="stylesheet" type="text/css">
    <?php }?>
    <?php echo '<script'; ?>
 src="/lib/bootstrap/js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/lib/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/lib/bootstrap/js/bootstrapValidator.min.js"><?php echo '</script'; ?>
>
    <?php if (isset($_smarty_tpl->tpl_vars['pageTag']->value) && ($_smarty_tpl->tpl_vars['pageTag']->value == 'recordAddUrl' || $_smarty_tpl->tpl_vars['pageTag']->value == 'recordEditUrl')) {?>
        <?php echo '<script'; ?>
 src="/js/record.js"><?php echo '</script'; ?>
>
    <?php }?>
    <?php if (isset($_smarty_tpl->tpl_vars['pageTag']->value) && ($_smarty_tpl->tpl_vars['pageTag']->value == 'recordList')) {?>
        <?php echo '<script'; ?>
 src="/js/recordList.js"><?php echo '</script'; ?>
>
    <?php }?>
</head>
<body>
<ul class="nav nav-pills navbar navbar-default" >
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
            record<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="/set/recordList.php?batchId=-1">record list</a>
            </li>
            <li>
                <a href="/set/recordAdd.php">record add url</a>
            </li>
        </ul>
    </li>
    <li class="disabled">
        <a href="javascript:void(0);" >attribute</a>
    </li>
    <li class="disabled">
        <a href="javascript:void(0);">mail</a>
    </li>
    <li class="disabled">
        <a href="javascript:void(0);">log</a>
    </li>
</ul>
<div>
    <div style="float: right;">
        <h2><?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
</h2>
    </div>
</div>
<div style="clear: both;"></div>
<center>
    <h1><?php echo $_smarty_tpl->tpl_vars['pageName']->value;?>
</h1>
</center>
<?php }
}
