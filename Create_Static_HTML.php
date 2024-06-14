<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } 

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# add in this plugin's language file
# i18n_merge($create_static_html) || i18n_merge($create_static_html, 'en_US');

register_plugin(
	$thisfile, 			# ID of plugin, should be filename minus php
	'Create Static HTML 📑', 	# Title of plugin // Based on "HTML Navigation" by Abhishek Shukla
	'1.0', 				# Version of plugin
	'risingisland', 	# Author of plugin
	'https://getsimple-ce.ovh/', # Author URL
	'Generate static HTML files from site navigation.', # Plugin Description
	'backups', 			# Page type of plugin
	'create_static' 	# Function that displays content
);

# Back-End Hooks
add_action('backups-sidebar','createSideMenu',array($thisfile,'Create Static HTML 📑'));
add_action('theme-header', 'cs_theme_header_js');

function cs_theme_header_js(){
	global $SITEURL;
	if (file_exists(GSROOTPATH.'index.html') && $_GET['create']<>'html') {
		echo '
		<script type="text/javascript"> 
			window.location="'.$SITEURL.'index.html";
		</script>';
	}
}

function create_static(){
	$msg = '';
	global $SITEURL;

	if (isset($_POST['recreateatroot'])) {
		$files = scandir(GSDATAPAGESPATH);
		$msg = '<span class="alert-red">No HTML Files Created</span>';
		$replace_urls = isset($_POST['replace_urls']);

		foreach ($files as $file){
			if (strpos($file,'.xml') !== false) {
				$id = strpos($file,'.xml');
				$id = substr($file,0,$id);
				cs_wwwcopy($SITEURL."index.php?id=$id&create=html", GSROOTPATH."$id.html", $replace_urls);
				$msg = '<span class="alert-green"><span style="font-size:1.6em;">&checkmark;</span> HTML pages recreated in GS ROOT. Visit Site to navigate html pages.</span>';
			}
		}

		if ($replace_urls) {
			cs_handle_fancy_url();
		}
	}

	if (isset($_POST['deleteatroot'])) {
		$msg = cs_delete_html_files(GSROOTPATH);
	}

	if (file_exists(GSROOTPATH."index.html")) { 
		$createinputvalue="Recreate HTML Files";
	} else { 
		$createinputvalue="Create HTML Files";
	}
	?>
	
	<style>
	.cs-ul{list-style-type:none;padding:0;margin:0} .cs-ul li{padding:8px 16px; border-bottom:1px solid #ddd} .cs-ul li:last-child{border-bottom:none} .cs-hoverable tbody tr:hover,.cs-ul.cs-hoverable li:hover{background-color:#ccc} .cs-centered tr td,.cs-centered tr th{text-align:center} .cs-check,.cs-radio{width:24px; height:24px; position:relative;top:6px}hr{border:0; border-top:1px solid #ccc;margin:20px 0;box-sizing:content-box;height:0;overflow:visible}code,kbd,pre,samp{font-family:monospace,monospace; font-size:1em;color:#00f;background-color:#eee;padding:5px} .cs-button{display:inline-block; cursor:pointer;font-size:14px;line-height:20px;border-radius:8px;padding:7px 14px;border:1px solid #222;background:#fff;color:#222} .alert-green,.alert-red{padding:10px 20px;background-color:#eee;font-weight:600} .cs-button:hover{border-color:#000; background:#ccc} .alert-red{color:#bf0000;border-radius:8px; border:1px solid #bf0000;-webkit-box-shadow:5px 5px 10px 0 rgba(0,0,0,.33);-moz-box-shadow:5px 5px 10px 0 rgba(0,0,0,.33);box-shadow:5px 5px 10px 0 rgba(0,0,0,.33)} .alert-green{color:#090;border-radius:8px;border:1px solid #090;-webkit-box-shadow:5px 5px 10px 0 rgba(0,0,0,.33);-moz-box-shadow:5px 5px 10px 0 rgba(0,0,0,.33);box-shadow:5px 5px 10px 0 rgba(0,0,0,.33)}
	</style>

	<h3>Create Static HTML <span style="font-style:normal">📑</span></h3>
	<p>Generate static HTML files from site navigation.</p>
	<div style="margin-left:30px;"><p><?php echo $msg; ?></p></div>
	<hr>
	
	<div>
		<p style="font-weight:600;font-size:1.1em">Info:</p>
		<ul>
			<li><b>Creating</b> HTML Files in GetSimple ROOT <b>will enable</b> HTML Navigation in the front-end. </li> 
			<li><b>Deleting</b> HTML Pages <b>will remove</b> HTML Navigation and restore normal navigation.</li> 
			<li>Changes in site/content/theme will reflect in the front-end only after Recreating HTML Pages.</li>  
			<li>If moving HTML files to new location, folders <u>/data/uploads</u>, <u>/themes</u> and <u>/plugins</u> my be required.</li>
			<li>Adding <code>DirectoryIndex index.html index.php</code> to <span style="color:green">.htaccess</span> in GS ROOT will ensure that index.html gets priority over index.php and avoid chances of php redirecting to html everytime the home page is visited.</li>
		</ul>
		<hr>

		<form method='post'>
			<label>
				<input class="cs-check" type='checkbox' name='replace_urls' <?php if (isset($_POST['replace_urls'])) echo 'checked'; ?>>
				Replace absolute URLs with relative URLs?
			</label>
			<br/><br/>
			
			<button class="cs-button" type="submit" name="recreateatroot"><svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="1.2rem" height="1.2rem" viewBox="0 0 32 32"><path fill="#008000" d="M6 3v26h20V9.594l-.28-.313l-6-6l-.314-.28H6zm2 2h10v6h6v16H8zm12 1.438L22.563 9H20zM16 13l-2 12h2l2-12zm-3.78 2.375l-2.5 3l-.533.625l.532.625l2.5 3l1.56-1.25L11.813 19l1.968-2.375zm7.56 0l-1.56 1.25L20.187 19l-1.97 2.375l1.563 1.25l2.5-3l.532-.625l-.53-.625l-2.5-3z"/></svg> <?php echo $createinputvalue;?></button>
			
			<button class="cs-button" style="margin-left:20px"type="submit" name="deleteatroot"><svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="1.2rem" height="1.2rem" viewBox="0 0 24 24"><path fill="#800000" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z"/></svg> Delete HTML Files</button>
		</form>
		<br/>
	</div>
	
	<?php 
	$list = cs_list_html_files(GSROOTPATH,$SITEURL);
	if ($list != '') {
		echo "
		<hr>
		<h3>List of HTML Files Created:</h3>
		<ol class='cs-ul cs-hoverable'>
			".$list."
		</ol>";
	}
	?>
	<br/>

	<?php
}

function cs_delete_html_files($directory){
	$msg = '<span class="alert-red">No Files to delete. HTML Navigation is <u>disabled</u> in the front-end.</span>';
	$files = scandir($directory);
	foreach ($files as $file){
		if (strpos($file,'.html') !== false && $file != '.' && $file != '..') {
			unlink(GSROOTPATH.$file);
			$msg = '<span class="alert-red"><span style="font-size:1.6em;">&cross;</span> HTML files deleted from GS ROOT. HTML Navigation is <u>disabled</u> in the front-end.</span>';
		}
	}
	return $msg;
}

function cs_list_html_files($directory, $directoryurl = ''){
	$list = '';
	$files = scandir($directory);
	foreach ($files as $file){
		if (strpos($file,'.html') !== false && $file != '.' && $file != '..') {
			if ($directoryurl && $directoryurl != ''){
				$list .= '<li><svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="0.9rem" height="1.2rem" viewBox="0 0 384 512"><path fill="#808080" d="M384 121.941V128H256V0h6.059c6.365 0 12.47 2.529 16.971 7.029l97.941 97.941A24.005 24.005 0 0 1 384 121.941M248 160c-13.2 0-24-10.8-24-24V0H24C10.745 0 0 10.745 0 24v464c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24V160zM123.206 400.505a5.4 5.4 0 0 1-7.633.246l-64.866-60.812a5.4 5.4 0 0 1 0-7.879l64.866-60.812a5.4 5.4 0 0 1 7.633.246l19.579 20.885a5.4 5.4 0 0 1-.372 7.747L101.65 336l40.763 35.874a5.4 5.4 0 0 1 .372 7.747zm51.295 50.479l-27.453-7.97a5.402 5.402 0 0 1-3.681-6.692l61.44-211.626a5.402 5.402 0 0 1 6.692-3.681l27.452 7.97a5.4 5.4 0 0 1 3.68 6.692l-61.44 211.626a5.397 5.397 0 0 1-6.69 3.681m160.792-111.045l-64.866 60.812a5.4 5.4 0 0 1-7.633-.246l-19.58-20.885a5.4 5.4 0 0 1 .372-7.747L284.35 336l-40.763-35.874a5.4 5.4 0 0 1-.372-7.747l19.58-20.885a5.4 5.4 0 0 1 7.633-.246l64.866 60.812a5.4 5.4 0 0 1-.001 7.879"/></svg> <a href="'.$directoryurl.$file.'" target="_blank">'.$file.'</a></li>';
			} else {
				$list .= '<li><svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="0.9rem" height="1.2rem" viewBox="0 0 384 512"><path fill="#808080" d="M384 121.941V128H256V0h6.059c6.365 0 12.47 2.529 16.971 7.029l97.941 97.941A24.005 24.005 0 0 1 384 121.941M248 160c-13.2 0-24-10.8-24-24V0H24C10.745 0 0 10.745 0 24v464c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24V160zM123.206 400.505a5.4 5.4 0 0 1-7.633.246l-64.866-60.812a5.4 5.4 0 0 1 0-7.879l64.866-60.812a5.4 5.4 0 0 1 7.633.246l19.579 20.885a5.4 5.4 0 0 1-.372 7.747L101.65 336l40.763 35.874a5.4 5.4 0 0 1 .372 7.747zm51.295 50.479l-27.453-7.97a5.402 5.402 0 0 1-3.681-6.692l61.44-211.626a5.402 5.402 0 0 1 6.692-3.681l27.452 7.97a5.4 5.4 0 0 1 3.68 6.692l-61.44 211.626a5.397 5.397 0 0 1-6.69 3.681m160.792-111.045l-64.866 60.812a5.4 5.4 0 0 1-7.633-.246l-19.58-20.885a5.4 5.4 0 0 1 .372-7.747L284.35 336l-40.763-35.874a5.4 5.4 0 0 1-.372-7.747l19.58-20.885a5.4 5.4 0 0 1 7.633-.246l64.866 60.812a5.4 5.4 0 0 1-.001 7.879"/></svg> '.$file.'</li>';
			}
		}
	}
	return $list;
}

function cs_handle_fancy_url(){
	global $SITEURL;
	$files = scandir(GSROOTPATH);
	$i = 0;
	$id_to_filename = array();
	
	foreach ($files as $file){
		if (strpos($file,'.html') !== false && $file != '.' && $file != '..') {
			$id = pathinfo($file, PATHINFO_FILENAME);
			$id_to_filename[$id] = $file;
		}
	}
	
	foreach ($files as $file){
		if (strpos($file,'.html') !== false && $file != '.' && $file != '..') {
			$html = file_get_contents(GSROOTPATH.$file); 
			
			foreach ($id_to_filename as $id => $filename) {
				$html = str_replace('index.php?id='.$id, $filename, $html);
			}
			
			file_put_contents(GSROOTPATH.$file, $html);
		}
	}
}

function cs_wwwcopy($link, $file, $replace_urls){
	global $SITEURL;
	$cont = '';
	$fp = @fopen($link, "r"); 
	while (!feof($fp)) { 
		$cont .= fread($fp, 1024); 
	} 
	fclose($fp); 

	if ($replace_urls) {
		$cont = str_replace($SITEURL, '', $cont);
	} else {
		$cont = preg_replace('/index\.php\?id=([a-zA-Z0-9_-]+)/', '$1.html', $cont);
	}

	$fp2 = @fopen($file, "w"); 
	fwrite($fp2, $cont); 
	fclose($fp2); 
}
?>
