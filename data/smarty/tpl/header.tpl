<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{$title}</title>
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/common.css" rel="stylesheet" type="text/css">
    <link href="/css/header.css" rel="stylesheet" type="text/css">
    {if isset($pageTag) && $pageTag == 'recordList'}
        <link href="/css/recordList.css" rel="stylesheet" type="text/css">
    {/if}
    <script src="/lib/bootstrap/js/jquery.min.js"></script>
    <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="/lib/bootstrap/js/bootstrapValidator.min.js"></script>
    {if isset($pageTag) && ($pageTag == 'recordAddUrl' || $pageTag == 'recordEditUrl') }
        <script src="/js/record.js"></script>
    {/if}
    {if isset($pageTag) && ($pageTag == 'recordList') }
        <script src="/js/recordList.js"></script>
    {/if}
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
        <h2>{$userName}</h2>
    </div>
</div>
<div style="clear: both;"></div>
<center>
    <h1>{$pageName}</h1>
</center>
