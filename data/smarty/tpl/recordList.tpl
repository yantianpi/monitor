{include file="header.tpl"}
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
                    <input name="recordId" value="{$recordId}" type="text" class="form-control" id="recordId"/>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="categoryId" class="control-label">category</label>
                    <select name="categoryId" class="form-control" id="categoryId">
                        {html_options options=$categoryOptionArray selected=$categoryId}
                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="projectId" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;project</label>
                    <select name="projectId" class="form-control" id="projectId">
                        {html_options options=$projectOptionArray selected=$projectId}
                    </select>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="form-group">
                    <label for="batchId" class="control-label">batch</label>
                    <select name="batchId" class="form-control" id="batchId">
                        {html_options options=$batchOptionArray selected=$batchId}
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordName" class="control-label">recordName</label>
                    <input name="recordName" value="{$recordName}" type="text" class="form-control" id="recordName"/>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordStatus" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;status</label>
                    <select name="recordStatus" class="form-control" id="recordStatus">
                        {html_options options=$recordStatusArray selected=$recordStatus}
                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="recordRunStatus" class="control-label">runStatus</label>
                    <select name="recordRunStatus" class="form-control" id="recordRunStatus">
                        {html_options options=$recordRunStatusArray selected=$recordRunStatus}
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
                        {html_options options=$orderOptionArray selected=$order}
                    </select>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="sort" class="control-label">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sort
                    </label>
                    <select name="sort" class="form-control" id="sort">
                        {html_options options=$sortOptionArray selected=$sort}
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
        <div class="col-xs-12">{$pagebar}</div>
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
                {foreach $recordList as $recordInfo}
                    <tr class="info">
                        <td>{$recordInfo.Id}</td>
                        <td>{$recordInfo.CategoryAlias|default:'unknow'}</td>
                        <td>{$recordInfo.ProjectName|default:'unknow'}</td>
                        <td>{$recordInfo.Name}</td>
                        <td>{$recordInfo.Description}</td>
                        <td>{$recordInfo.CronTime}</td>
                        <td>{$recordInfo.Batch}</td>
                        <td>
                            {$recordInfo.NotifyType}
                            {foreach $recordInfo.NotifyObjectInfo as $NotifyInfo}
                                <hr style="width:%95;" />
                                    {$NotifyInfo@key} : {$NotifyInfo}
                            {/foreach}
                        </td>
                        <td>
                            monitor:{$recordInfo.MonitorCount}<br />
                            alert:{$recordInfo.AlertCount}
                            <hr style="width:%95;" />
                            seriesalert:{$recordInfo.SeriesAlertCount}<br />
                            limit:{$recordInfo.AlertLimit}
                        </td>
                        <td>
                            lastmonitor:{$recordInfo.LastMonitorTime}<br />
                            lastalert:{$recordInfo.LastAlertTime}
                            <hr style="width:%95;" />
                            start:{$recordInfo.StartTime}<br />
                            end:{$recordInfo.EndTime}
                            <hr style="width:%95;" />
                            add:{$recordInfo.AddTime}<br />
                            update:{$recordInfo.UpdateTime}<br />
                            timestamp:{$recordInfo.Timestamp}
                        </td>
                        <td>
                            {$recordInfo.Status}
                            <hr style="width:%95;" />
                            {$recordInfo.RunStatus}
                        </td>
                        <td>
                            <a href="/set/recordEdit.php?id={$recordInfo.Id}" class="btn btn-default">Edit</a>
                            <hr style="width:%95;" />
                            {if {$recordInfo.Status} eq "ACTIVE"}
                                <a href="/set/recordChangeStatus.php?id={$recordInfo.Id}&type=delete" class="btn btn-default">Delete</a>
                            {elseif {$recordInfo.Status} eq "INACTIVE"}
                                <a href="/set/recordChangeStatus.php?id={$recordInfo.Id}&type=recovery" class="btn btn-default">Recovery</a>
                            {/if}
                            <hr style="width:%95;" />
                            <button type="button" class="btn btn-primary btn-lg click" id="{$recordInfo.Id}">Test</button>
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <a href="/set/recordAdd.php">add url record</a>
        </div>
        <div class="col-xs-8"></div>
        <div class="col-xs-12">{$pagebar}</div>
    </div>
</div>
{include file="footer.tpl"}
