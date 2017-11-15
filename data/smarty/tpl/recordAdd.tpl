{include file="header.tpl"}
<form method="post" action="/set/recordAdd.php" class="form-horizontal" role="form" id="myform">
    <input type="hidden" name="Add" value="true"/>
    <div class="form-group">
        <label class="col-sm-2 control-label">Name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Name" placeholder="请输入名字">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="Description" placeholder="请输入描述">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">ProjectId</label>
        <div class="col-sm-6">
            <select name="ProjectId" class="form-control">
                {html_options options=$projectOptionArray}
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
                    <input type="text" class="form-control" name="Url" placeholder="请输入监控链接">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "Type"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Type</label>
                <div class="col-sm-2">
                    <select name="Type" class="form-control">
                        {html_options options=$TypeOptionArray}
                    </select>
                </div>
            </div>
        {elseif $attributeInfo@key eq "LoginMethodId"}
            <div class="form-group">
                <label class="col-sm-2 control-label">LoginMethodId</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="LoginMethodId" placeholder="输入登陆链接id">
                </div>
            </div>
        {elseif $attributeInfo@key eq "Method"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Method</label>
                <div class="col-sm-2">
                    <select name="Method" class="form-control">
                        {html_options options=$MethodOptionArray}
                    </select>
                </div>
            </div>
        {elseif $attributeInfo@key eq "Params"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Params</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Params">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "HttpCode"}
            <div class="form-group">
                <label class="col-sm-2 control-label">HttpCode</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="HttpCode">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "Header"}
            <div class="form-group">
                <label class="col-sm-2 control-label">Header</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Header">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "ResponseTime"}
            <div class="form-group">
                <label class="col-sm-2 control-label">ResponseTime</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ResponseTime">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "ContentSize"}
            <div class="form-group">
                <label class="col-sm-2 control-label">ContentSize</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ContentSize">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList2"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control preg" name="WhiteList2">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "WhiteList3"}
            <div class="form-group">
                <label class="col-sm-2 control-label">WhiteList3</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="WhiteList3">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "BlackList"}
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="BlackList">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {elseif $attributeInfo@key eq "BlackList2"} 
            <div class="form-group">
                <label class="col-sm-2 control-label">BlackList2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="BlackList2">
                </div>
                <p class="form-control-static">{$attributeInfo['ContentType']}</p>
            </div>
        {/if}
    {/foreach}
    
    <div class="form-group">
        <label class="col-sm-2 control-label">BatchId</label>
        <div class="col-sm-4">
            <select name="BatchId" class="form-control" id="BatchId">
                {html_options options=$batchOptionArray}
            </select>
        </div>
    </div>
    <div class="form-group" id="otherBatch">
        <label  class="col-sm-2 control-label">每</label>
        <div class="col-lg-2">
            <input type="text" class="form-control" name="otherBatch" id="otherBatchVal">
        </div>
        <label  class="control-label">分钟</label>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">notifyType</label>
        <div class="col-sm-6">
            <select name="notifyType" class="form-control">
                {html_options options=$notifyTypeOptionArray}
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
                    <label><input type="checkbox" name="Addressee[]" value="{$mailInfo.Id}">{$mailInfo['Mail']}</label>
                {/foreach}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-lg-10">
            <div class="checkbox" class="col-lg-2">
                {foreach $mailOptionArray as $mailInfo}
                    <label><input type="checkbox" name="CC[]" value="{$mailInfo.Id}">{$mailInfo['Mail']}</label>
                {/foreach}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonTitle</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" placeholder="value" name="commonTitle">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">commonBody</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" placeholder="value" name="commonBody">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">AlertLimit&nbsp;&nbsp;<span style="color:red">*</span></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="alertLimit" placeholder="请输入预警上限次数">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">status</label>
        <div class="col-sm-6">
            <select name="status" class="form-control">
                {html_options options=$StatusOptionArray}
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
