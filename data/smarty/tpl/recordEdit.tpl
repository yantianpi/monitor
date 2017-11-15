{include file="header.tpl"}
<form method="post" action="/set/recordEdit.php" class="form-horizontal" role="form" id="myform">
    <input type="hidden" name="update" value="true"/>
    <input type="hidden" name="Id" value="{$recordId}"/>
    <div class="form-group">
        <label class="col-sm-2 control-label">Name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Name" placeholder="请输入名字" value="{$recordList['Name']}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Description" placeholder="请输入描述" value="{$recordList['Description']}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">ProjectId</label>
        <div class="col-sm-6">
            <select name="ProjectId" class="form-control">
                {html_options options=$projectOptionArray selected=$recordList.ProjectId}
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Content</label>
    </div>
    {foreach $attributeOptionArray as $attributeInfo}
        {if $attributeInfo@key eq "Url"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Url&nbsp;&nbsp;<span style="color:red">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Url" placeholder="请输入名字" value="{if !empty($contentArray.Url.Value)}{$contentArray.Url.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "Type"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Type</label>
                <div class="col-sm-2">
                    <select name="Type" class="form-control">
                        {html_options options=$TypeOptionArray selected=$contentArray.Type.Value}
                    </select>
                </div>
            </div>
        {elseif $attributeInfo@key eq "LoginMethodId"}
            <div class="form-group">
                <label class="col-sm-2 control-label">LoginMethodId</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="LoginMethodId" value="{if !empty($contentArray.LoginId.Value)}{$contentArray.LoginId.Value}{/if}">
                </div>
            </div>
        {elseif $attributeInfo@key eq "Method"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Method</label>
                <div class="col-sm-2">
                    <select name="Method" class="form-control">
                        {html_options options=$MethodOptionArray selected=$contentArray.Method.Value}
                    </select>
                </div>
            </div>

        {elseif $attributeInfo@key eq "Params"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Params</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Params" value="{if !empty($contentArray.Params.Value)}{$contentArray.Params.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "HttpCode"}
            <div class="form-group">
                <label class="col-sm-2 control-label">HttpCode</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="HttpCode" value="{if !empty($contentArray.HttpCode.Value)}{$contentArray.HttpCode.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "Header"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Header</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Header" value="{if !empty($contentArray.Header.Value)}{$contentArray.Header.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "ResponseTime"}
            <div class="form-group">
                <label class="col-sm-2 control-label">ResponseTime</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ResponseTime" value="{if !empty($contentArray.ResponseTime.Value)}{$contentArray.ResponseTime.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "ContentSize"}
            <div class="form-group">
                <label class="col-sm-2 control-label">ContentSize</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ContentSize" value="{if !empty($contentArray.ContentSize.Value)}{$contentArray.ContentSize.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList" value="{if !empty($contentArray.WhiteList.Value)}{$contentArray.WhiteList.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList2"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList2" value="{if !empty($contentArray.WhiteList2.Value)}{$contentArray.WhiteList2.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList3"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList3</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="WhiteList3" value="{if !empty($contentArray.WhiteList3.Value)}{$contentArray.WhiteList3.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "BlackList"}
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="BlackList" value="{if !empty($contentArray.BlackList.Value)}{$contentArray.BlackList.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "BlackList2"} 
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="BlackList2" value="{if !empty($contentArray.BlackList2.Value)}{$contentArray.BlackList2.Value}{/if}">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {/if}
    {/foreach}
    

    <div class="form-group">
        <label class="col-sm-2 control-label">batchId</label>
        <div class="col-sm-4">
            <select name="batchId" class="form-control" id="BatchId">
                {html_options options=$batchOptionArray selected=$recordList.Batch}
            </select>
        </div>
    </div>

    <div class="form-group" {if $recordList.Batch neq 0} style="display: none" {/if} id="otherBatch">
        <label  class="col-sm-2 control-label">每</label>
        <div class="col-lg-2">
            <input type="text" class="form-control" name="otherBatch" id="otherBatchVal" value="{if $recordList.Batch eq 0}{$recordList.CromMinute}{/if}">
        </div>
        <label  class="control-label">分钟</label>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">notifyType</label>
        <div class="col-sm-6">
            <select name="notifyType" class="form-control">
                {html_options options=$notifyTypeOptionArray selected=$notifyType}
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
                {foreach $mailOptionArray as $mailInfo}
                    <label><input type="checkbox" name="Addressee[]" value="{$mailInfo.Id}" {if !empty($NotifyObjectArray['Addressee']) && in_array($mailInfo.Id,$NotifyObjectArray.Addressee) }checked="checked"{/if}>{$mailInfo['Mail']}</label>
                {/foreach}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-lg-10">
            <div class="checkbox" class="col-lg-2">
                {foreach $mailOptionArray as $mailInfo}
                    <label><input type="checkbox" name="CC[]" value="{$mailInfo.Id}" {if !empty($NotifyObjectArray['CC']) && in_array($mailInfo.Id,$NotifyObjectArray.CC) }checked="checked"{/if}>{$mailInfo['Mail']}</label>
                {/foreach}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonTitle</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" name="commonTitle" value="{if !empty($NotifyObjectArray.commonTitle)} {$NotifyObjectArray.commonTitle}{/if}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonBody</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" name="commonBody" value="{if !empty($NotifyObjectArray.commonBody)} {$NotifyObjectArray.commonBody}{/if}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">AlertLimit&nbsp;&nbsp;<span style="color:red">*</span></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="alertLimit" placeholder="请输入预警上限次数" value="{$recordList['AlertLimit']}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">status</label>
        <div class="col-sm-6">
            <select name="status" class="form-control">
                {html_options options=$StatusOptionArray selected=$status}
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

{include file="footer.tpl"}
