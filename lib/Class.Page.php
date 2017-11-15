<?php
/**
 * page class
 *
 * @author lee
 * @package Mylib
 */
/*
	$objPB = new OPB($nTotalCount, $perPageCount);
	$objPB->linkhead = $weblk->getLinkURL(array("channelID"=>$nChannelID,"page"=>''));
	$linksarr = $weblk->getAllLinksByChannelP($nChannelID,$objPB->offset,$perPageCount);
	$pagebar = $objPB->whole_bar(3, 8);
*/

class Page {

    var $total; //total records
    var $onepage; //the num of each page
    var $num;
    var $page; //the current Page it's a param in get
    var $total_page; //total pages
    var $offset; //the offset records
    var $linkhead; //the content of requestUri
    var $default_onepage = 10;

    function __construct($total = 0, $onepage = "", $form_vars = '', $max_per_page = 500) {
        if (isset($_GET["onepage"])) {
            $onepage = $_GET["onepage"];
            if (empty($onepage) || $onepage > $max_per_page) {
                $onepage = $max_per_page;
            }
            setcookie("onepage", $onepage, time() + 60 * 60 * 24 * 30);
        } else {
            if (!isset($_COOKIE['onepage']) || empty($_COOKIE['onepage'])) {
                if (empty($onepage)) {
                    $onepage = $this->default_onepage;
                }
                if ($onepage > $max_per_page) {
                    $onepage = $max_per_page;
                }
                setcookie("onepage", $onepage, time() + 60 * 60 * 24 * 30);
                $_COOKIE['onepage'] = $onepage;
            } else {
                $onepage = trim($_COOKIE['onepage']);
                if ($onepage != "") {
                    if ($onepage > $max_per_page) {
                        $onepage = $max_per_page;
                    }
                    setcookie("onepage", $onepage, time() + 60 * 60 * 24 * 30);
                    $_COOKIE['onepage'] = $onepage;
                }
            }
        }
        if ($onepage == "") {
            $onepage = $this->default_onepage;
        }
        $page =&$_GET['page'];
        $this->total =&$total;
        $this->onepage =&$onepage;

        $this->total_page = ceil($total / $onepage);

        if ($page == '') {
            $this->page = 1;
            $this->offset = 0;
        } else {
            $this->page =&$page;
            $this->offset = ($page - 1) * $onepage;
        }
        $formlink = '';
        if ($form_vars != '') {
            $vars = explode("|", $form_vars);
            $chk = $vars[0];
            $chk2 = $vars[1];
            $chk_value =& $_POST["$chk"];
            $chk_value2 =& $_POST["$chk2"];
            if ($chk_value == '' && $chk_value2 == '') {
                $formlink = '';
            } else {
                for ($i = 0; $i < sizeof($vars); $i++) {
                    $var = $vars[$i];
                    $value =& $_POST["$var"];
                    $addchar = $var . "=" . urlencode($value);
                    $formlink = $formlink . $addchar . "&";
                }
            }
        } else {
            $formlink = '';
        }
        $tmpString = Common::obtainVariable('QUERY_STRING', 'server');
        $linkarr = explode("page=", $tmpString);
        $linkft = $linkarr[0];
        if ($linkft == '') {
            $this->linkhead = $_SERVER['PHP_SELF'] . "?" . $formlink;
        } else {
            $linkft = substr($linkft, -1) == "&" ? $linkft : $linkft . "&";
            $this->linkhead = $_SERVER['PHP_SELF'] . "?" . $linkft . $formlink;
        }

    }

    function setTotal($total) {
        $this->total =&$total;
        $this->total_page = ceil($total / $this->onepage);
    }

    function offset() {
        return $this->offset;
    }

    function first_page($link = '', $char = '', $color = '') {
        $linkhead =&$this->linkhead;
        $linkchar = $char == '' ? "" : $char;
        if ($link == 1) {
            return "<a href=\"$linkhead" . "page=1\" title=\"The first page\">$linkchar</a> ";
        } else {
            return 1;
        }
    }

    function total_page($link = '', $char = '', $color = '') {
        $linkhead =&$this->linkhead;
        $total_page =&$this->total_page;
        $linkchar = $char == '' ? "" : $char;
        if ($link == 1) {
            return "<a href=\"$linkhead" . "page=$total_page\" title=\"The lasted page\">$linkchar</a>";
        } else {
            return $total_page;
        }
    }

    function pre_page($char = '') {
        $linkhead =&$this->linkhead;
        $page =&$this->page;
        if ($char == '') {
            $char = "";
        }

        if ($page > 1) {
            $pre_page = $page - 1;
            return "<a href=\"$linkhead" . "page=$pre_page\" title=\"previous page\">$char</a> ";
        } else {
            return '';
        }
    }

    function next_page($char = '') {
        $linkhead =&$this->linkhead;
        $total_page =&$this->total_page;
        $page =&$this->page;
        if ($char == '') {
            $char = "";
        }
        if ($page < $total_page) {
            $next_page = $page + 1;
            return "<a href=\"$linkhead" . "page=$next_page\" title=\"next page\">$char</a> ";
        } else {
            return '';
        }
    }

    function num_bar($num = 8, $color = '', $maincolor = '', $left = '', $right = '') {
        $num = $num == '' ? 10 : $num;
        $this->num =& $num;
        $mid = floor($num / 2);
        $last = $num - 1;
        $page =& $this->page;
        $totalpage =& $this->total_page;
        $linkhead =& $this->linkhead;
        $left = $left == '' ? "" : $left;
        $right = $right == '' ? "" : $right;
        $color = $color == '' ? "#ff0000" : $color;
        $minpage = ($page - $mid) < 1 ? 1 : $page - $mid;
        $maxpage = $minpage + $last;
        if ($maxpage > $totalpage) {
            $maxpage =& $totalpage;
            $minpage = $maxpage - $last;
            $minpage = $minpage < 1 ? 1 : $minpage;
        }

        $linkbar = "";
        for ($i = $minpage; $i <= $maxpage; $i++) {
            $chars = $left . $i . $right;
            $char = "" . $chars . "";
            if ($i == $page) {
                $char = "$chars";
            }
            if ($i == $page) {
                $linkchar = "<font color='red'>" . $char . "</font>";
            } else {
                $linkchar = " <a href='$linkhead" . "page=$i'>" . $char . "</a> ";
            }

            $linkbar .= $linkchar;
        }

        return $linkbar;
    }

    function pre_group($char = '') {
        $page =& $this->page;
        $linkhead =& $this->linkhead;
        $num =& $this->num;
        $mid = floor($num / 2);
        $minpage = ($page - $mid) < 1 ? 1 : $page - $mid;
        $char = $char == '' ? "" : $char;
        $pgpage = $minpage > $num ? $minpage - $mid : 1;
        return "<a href='$linkhead" . "page=$pgpage' title=\"previous group number bar\">" . $char . "</a> ";
    }


    function next_group($char = '') {
        $page =& $this->page;
        $linkhead =& $this->linkhead;
        $totalpage =& $this->total_page;
        $num =& $this->num;
        $mid = floor($num / 2);
        $last = $num;
        $minpage = ($page - $mid) < 1 ? 1 : $page - $mid;
        $maxpage = $minpage + $last;
        if ($maxpage > $totalpage) {
            $maxpage =& $totalpage;
            $minpage = $maxpage - $last;
            $minpage = $minpage < 1 ? 1 : $minpage;
        }

        $char = $char == '' ? "" : $char;
        $ngpage = ($totalpage > $maxpage + $last) ? $maxpage + $mid : $totalpage;

        return "<a href='$linkhead" . "page=$ngpage' title=\"next group number bar\">" . $char . "</a> ";
    }


    function whole_num_bar($num = '', $color = '', $maincolor = '') {
        $num_bar = $this->num_bar($num, $color, $maincolor);


        return $this->first_page(1, '', $maincolor) . $this->pre_group("") . $this->pre_page("previous") . $num_bar . $this->next_page("next") . $this->next_group("") . $this->total_page(1, '', $maincolor);
    }

    function whole_bar($jump = '', $num = '', $color = '#000000', $maincolor = '#666666') {
        $whole_num_bar = $this->whole_num_bar($num, $color, $maincolor) . "&nbsp;";
        $jump_form = $this->jump_form($jump);
        if (($this->offset + $this->onepage) >= $this->total) {
            $tmpStr = $this->total;
        } else {
            $tmpStr = strval($this->offset + $this->onepage);
        }
        $tmpoffsetStr = $this->offset + 1;
        $perpagenumBar = $this->AddPerpageNum($jump);
        return " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" . "   <tr>\n" . "      <td align=\"right\" class=\"main\"> $perpagenumBar   Showing " . $tmpoffsetStr . "-" . $tmpStr . " of " . $this->total . " | $whole_num_bar</td>\n" . "      <td width=\"100\">$jump_form</td>\n" . "   </tr>\n" . " </table>\n";
    }

    function Jump_form($jump = '') {
        $formname = "pagebarjumpform" . $jump;
        $jumpname = "jump" . $jump;
        $linkhead = $this->linkhead;
        $total = $this->total_page;
        return "<table width=\"100\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" . "<script language=\"javascript\">\n" . "    function $jumpname(linkhead, total, jump)\n" . "    {\n" . " 		var pagenum = document.getElementById('perpage$jump').value;\n" . " 		var form = document.getElementById('$formname');\n" . "		var page = form.page;\n" . "        var page = (page.value>total)?total:page.value;\n" . "        page     = (page<1)? 1 : page; \n" . "        location.href = linkhead + \"page=\" + page + '&onepage=' + pagenum;\n" . "        return false;\n" . "    }\n" . "</script>\n" . "<form name=\"$formname\" id='$formname' method=\"post\" onSubmit=\"return $jumpname('$linkhead', $total, '$jump')\">\n" . "  <tr>\n" . "    <td>&nbsp;&nbsp;\n" . "       <input name=\"page\" type=\"text\" size=\"1\"></td>\n" . "       <td><input type=\"button\" name=\"Submit\" value=\"Go\" onClick=\"return $jumpname('$linkhead', $total, '$jump')\" size =1>\n" . "    </td>\n" . "  </tr>\n" . "</form>\n" . "</table>\n";
    }

    function getOnePage() {
        return $this->onepage;
    }

    function getCurrentPage() {
        return $this->page;
    }

    #End of function Jump_form();
    function AddPerpageNum($jump = '') {
        $perpagenum = $this->onepage;
        if (isset($_REQUEST["onpage"])) {
            $perpagenum = $_REQUEST["onpage"];
            $this->onepage = $perpagenum;
        }
        $resStr = " Num/Page: <input type=\"text\" size=\"5\" value=\"{$perpagenum}\" name=\"perpage$jump\" id=\"perpage$jump\"/>";
        return $resStr;
    }
}

?>