<?php
require_once dirname(__FILE__) . '/smarty/libs/Smarty.class.php';

class SmartyExt extends Smarty {

	public function __construct($project='', $tplDir='', $configDir='') {
		parent::__construct();
		if($tplDir && substr($tplDir, 0, 1) != "/") {
			$tplDir = DATA_DIR . $tplDir;
		}
		$templateDir = !empty($tplDir) ? $tplDir : DATA_DIR . 'smarty/tpl';
		$compileDir  = DATA_DIR . 'smarty/smarty_c/' . $project;
		$cacheDir  = DATA_DIR . 'smarty/smarty_cache/' . $project;
		$configDir = $configDir ? $configDir : $this->template_dir;
        $this->setTemplateDir($templateDir)
            ->setCompileDir($compileDir)
            ->setCacheDir($cacheDir)
            ->setConfigDir($configDir);
            
		$this->config_booleanize = false;
		$this->left_delimiter = '{';
		$this->right_delimiter = '}';
		$this->compile_check = defined("DEBUG_MODE") && DEBUG_MODE == false ? false : true;
	}
	
	
	public function displayTpl($tplname, $show="NO",$echo = true) {
	    $this->display($tplname);
        die;
		$content = parent::fetch($tplname);
		$content = preg_replace("/\r/", "", $content);
		$content = preg_replace("/\n\s*\n/", "\n", $content);
		if($show=="YES" && Common::getRequestVar("nofollow", "get") != "no"){
			$urlarr = array();
			$content_arr = array();
			preg_match_all('/<a[^>]*>/si', $content, $content_arr, PREG_OFFSET_CAPTURE);
			$pos_current = 0;
			$processed_content = "";
			if(count($content_arr) && count($content_arr[0])){
				foreach($content_arr[0] as $key => $n){
					$href_matches = array();
					$rel_matches = array();
					$processed_content .= substr($content, $pos_current, $n[1] - $pos_current);
					$pos_current = $n[1];
					$url_tmp = "";
					$rel = "";
					if(preg_match("/href=\"([^\"]*)\"/si", $n[0], $href_matches, PREG_OFFSET_CAPTURE)){
						$url_tmp = trim($href_matches[1][0]);
					}
					if(preg_match("/rel=\"([^\"]*)\"/si", $n[0], $rel_matches, PREG_OFFSET_CAPTURE)){
						$rel = trim($rel_matches[1][0]);
					}
					if(!empty($url_tmp) && (stripos($url_tmp, SITE_URL) === 0 || stripos($url_tmp, "/") === 0)){
						$url = trim(str_ireplace(SITE_URL, "" , $url_tmp));
						if (array_key_exists($url, $urlarr)) {
							if(empty($rel)){
								$rel = "nofollow";
								$processed_content .= substr($content, $pos_current, strlen($n[0]) - 1)." rel=\"{$rel}\">";
							}else if (stripos($rel, "nofollow") === false) {
								$rel =  $rel." nofollow";								
								$processed_content .= substr($content, $pos_current, $rel_matches[1][1]);
								$processed_content .= $rel . substr($content, $pos_current + $rel_matches[1][1] + strlen($rel_matches[1][0]), strlen($n[0]) - ($rel_matches[1][1] + strlen($rel_matches[1][0])));
							}else{
								$processed_content .= substr($content, $pos_current, strlen($n[0]));
							}
						}else{
							$processed_content .= substr($content, $pos_current, strlen($n[0]));
						}
						$urlarr[$url] = $rel;
					}else{
						$processed_content .= substr($content, $pos_current, strlen($n[0]));
					}
					$pos_current = $n[1] + strlen($n[0]);
				}
				$processed_content .= substr($content, $pos_current);
				$content = $processed_content;
			}
		}
		$rtn = "";
		$arr = preg_split("/(<script[^>]*?>.*?<\/script>|<textarea[^>]*?>.*?<\/textarea>)/ims", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
		foreach($arr as $k => $v){
			if($k % 2 == 1){
				$rtn .= $v;
			}else{
				$rtn .= preg_replace("/\s+/m", " ", $v);
			}
		}
		if($echo == false){
			return trim($rtn);
		}
		echo trim($rtn);
	}
	
}
?>