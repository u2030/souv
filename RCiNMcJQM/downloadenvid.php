<?php
if(!isset($_REQUEST["url"]) || strlen($_REQUEST["url"]) < 10){
	echo "no url";
	exit;
}
$url = $_REQUEST["url"];

$vid = findYoutubeId($url);
if($vid == null){
	echo "loading page...<br/>";
	//$source = getPageSource($url);
	$source =getengvidsource();
	if(strlen($source) <= 0){
		echo $url;
		echo 'no page html<br/>';
		exit;
	}
	echo '<p id="videotitle" style="width: 500px;">' . fiindTitle($source) . '</p>';
	$vid = findYoutubeId($source);
	if($vid == null){
		echo 'no youtube id found.<br/>';
		echo $source;
		exit;
	}
}
echo '<input id="youtubetitle" value="' . getYouTubeTitle($vid) . '" style="width: 500px;" type="text"/>';

$keepvidUrl = 'http://keepvid.com/?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D' .$vid;
//$text = getPageSource($keepvidUrl);
$keepsource = getkeepsource();
$table = parseKeepVid($keepsource);
echo $table;


function getYouTubeTitle($video_id){
	$url = "https://www.youtube.com/watch?v=".$video_id;
	$src = getPageSource($url);
	return RemoveFromEndIf(fiindTitle($src), " - YouTube");
}

function RemoveFromEndIf($haystack, $needle)
{
/*	// remvoe bad chars
	$bad = array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));
	$haystack = str_replace($bad, "", $haystack);
	*/
    $length = strlen($needle);
    if ($length == 0) {
        return $haystack;
    }

    if(substr($haystack, -$length) === $needle){
		return substr($haystack, 0, strlen($haystack) -$length);
	}
	
	return $haystack;
}

function fiindTitle($source){
	$ind1 = strpos($source, '<title>');
	if($ind1 < 0){
		return "No Title";
	}
	$ind2 = strpos($source, '</title>');
	return substr($source, $ind1 + 7 , $ind2 - $ind1 - 7);
}

function parseKeepVid($text){
	$ind = strpos($text, 'class="search-result-content"');
	if($ind < 0){
		echo "Could not find table";
		return $text;
	}
	$ind1 = strpos($text, '<table', $ind);
	//echo $ind1;
	$ind2 = strpos($text, '</table>', $ind);
	//echo $ind2;
	if($ind1 < 0 || $ind2 < 0){
		echo "Could not find table";
		return $text;
	}
	
	return substr($text, $ind1, $ind2 - $ind1 + 8);
	
/*
	
	$doc = new DOMDocument('1.0');
	$doc->loadHTML($text);
	return;
	foreach($doc->getElementsByTagName('div') AS $div) {
		$class = $div->getAttribute('class');
		if(strpos($class, 'news') !== FALSE) {
			if($div->getAttribute('title') == 'news alert') {
				echo 'title found';
			}
			else {
				echo 'title not found';
			}
		}
	}
*/
}

function getPageSource($url){
	return file_get_contents($url);
}

function getYoutubeId($url){
	//preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
	preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
	return $matches;
}

function findYoutubeId($source){
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
    //$longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';
    $longUrlRegex = "/(youtube.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";

    if (preg_match($longUrlRegex, $source, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
		return $youtube_id; 
    }

    if (preg_match($shortUrlRegex, $source, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
		return $youtube_id;
    }
	
	return null;
}





function getengvidsource(){
	return '<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# object: https://ogp.me/ns/object# engvid-english: https://ogp.me/ns/fb/engvid-english#">
<title>Improve Your Grammar: 4 ways to use -ING words in English &middot; engVid</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="title" content="Improve Your Grammar: 4 ways to use -ING words in English  engVid">
<meta property="fb:app_id" content="112080315494987">
<meta property="og:site_name" content="engVid">
<meta name="twitter:site" content="@engVid">
<link rel="publisher" href="https://plus.google.com/100025612711787119031">
<link rel="alternate" type="application/rss+xml" title="engVid RSS Feed" href="https://www.engvid.com/feed/">
<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
<meta name="twitter:card" content="player">
<meta property="twitter:player:width" content="1280">
<meta property="twitter:player:height" content="720">
<meta name="description" content="Words that end in -ing can be verbs, nouns, adjectives, or adverbs. Understanding the function of a word will help you decide whether it should end in -ing or not. In this lesson, I will teach you about the different uses of -ing words, and about their functions within sentences. By the end of the video, you will have a much better understanding of -ing words and will be able to form proper sentences with them. After watching, try my quiz to make sure youve understood everything.">
<meta property="og:description" content="Words that end in -ing can be verbs, nouns, adjectives, or adverbs. Understanding the function of a word will help you decide whether it should end in -ing or not. In this lesson, I will teach you about the different uses of -ing words, and about their functions within sentences. By the end of the video, you will have a much better understanding of -ing words and will be able to form proper sentences with them. After watching, try my quiz to make sure youve understood everything.">
<meta property="og:title" content="Improve Your Grammar: 4 ways to use -ING words in English">
<meta property="og:type" content="engvid-english:english_lesson">
<meta property="og:url" content="https://www.engvid.com/4-ways-to-use-ing-words-in-english/">
<meta property="og:video" content="https://www.youtube.com/v/6XUnG0OfEgE&amp;rel=0&amp;fs=0&amp;showinfo=0">
<meta property="og:video:height" content="480">
<meta property="og:video:width" content="853">
<link rel="image_src" href="https://img.youtube.com/vi/6XUnG0OfEgE/maxresdefault.jpg">
<meta name="thumbnail" content="https://img.youtube.com/vi/6XUnG0OfEgE/maxresdefault.jpg">
<meta name="medium" content="video">
<link rel="video_src" href="https://www.youtube.com/v/6XUnG0OfEgE&amp;rel=0&amp;fs=0&amp;showinfo=0">
<meta name="video_height" content="480">
<meta name="video_width" content="853">
<meta name="video_type" content="application/x-shockwave-flash">
</head>
<body>aaaaaaaa</body>
';
}

function getkeepsource(){
	return '<!doctype html>
<html>
<head>
<title>[OFFICIAL] KeepVid: Download YouTube Videos, Facebook, Vimeo, Twitch.Tv, Dailymotion, Youku, Tudou, Metacafe and more!</title>
<meta name="description" content="The #1 Free Online Video Downloader allows you to download videos from YouTube, Facebook, Vimeo, Twitch.Tv, Dailymotion, Youku, Tudou, Metacafe and heaps more!">
<meta name="keywords" content="download youtube videos, keepvid, youtube downloader, download online videos, download streaming videos">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="robots" content="All,index,follow"><meta name="revisit-after" content="7 days">
<meta property="og:image" content="https://pro.keepvid.com/images/keepvid-logo.png">
<meta itemprop="image" content="https://pro.keepvid.com/images/keepvid-logo.png">
<link rel="shortcut icon" href="http://keepvid.com/favicon.ico" />
<link rel="canonical" href="http://keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="en" href="http://keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="ar" href="http://ar.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="pt" href="http://br.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="cs" href="http://cs.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="da" href="http://da.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="de" href="http://de.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="el" href="http://el.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="es" href="http://es.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="fi" href="http://fi.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="fr" href="http://fr.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="ga" href="http://ga.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="hu" href="http://hu.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="it" href="http://it.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="ms" href="http://ms.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="nl" href="http://nl.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="no" href="http://no.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="pl" href="http://pl.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="sv" href="http://sv.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="th" href="http://th.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="tr" href="http://tr.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="vi" href="http://vi.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="alternate" hreflang="zh-cn" href="http://zh.keepvid.com/?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6XUnG0OfEgE" />
<link rel="stylesheet" type="text/css" href="new-style/global_fonts.css?nv=1.0">
<link rel="stylesheet" type="text/css" href="new-style/global.css?nv=1.06">
<link rel="stylesheet" type="text/css" href="new-style/download.css?nv=1.07">
</head>
<body><a name="toTop"></a>
<header>
	<div class="container">
		<h1>
			<a href="/" class="logo" title="KeepVid: Download YouTube Videos, Facebook, Vimeo, Twitch.Tv, Dailymotion, Youku, Tudou, Metacafe and more!">KeepVid: Download YouTube Videos, Facebook, Vimeo, Twitch.Tv, Dailymotion, Youku, Tudou, Metacafe and more!</a>
		</h1>
		<ul class="navs clear-both">
			<li class="curr"><a href="/">Online</a></li>
			<li><a href="https://pro.keepvid.com/">Desktop</a></li>
			<li ><a href="/keepvid-android">Mobile</a></li>
			<li ><a href="/extensions">Extensions</a></li>
			<li ><a href="/webmasters">Webmasters</a></li>
			<li id="dropdown" class="dropdown">
				<a href="javascript:;">More<i class="icon-down si si-arrow-drop-down"></i><i class="icon-up si si-arrow-drop-up"></i></a>
				<ul class="hidden" >
					<li><a href="/programs">Programs</a></li>
					<li><a href="/updates">Updates</a></li>
					<li><a href="/sites">Sites</a></li>
					<li><a href="/help">Help</a></li>
				</ul>
			</li>
		</ul>
		<div class="dropdown-menu">
			<i class="icon si si-menu"></i>
			<i class="icon si si-close" style="display: none;"></i>
			<div class="header_pro">
				<ul>
					<li class="curr"><a href="/">Online</a></li>
					<li><a href="https://pro.keepvid.com/">Desktop</a></li>
					<li ><a href="/keepvid-android">Mobile</a></li>
					<li ><a href="/extensions">Extensions</a></li>
					<li ><a href="/webmasters_test">Webmasters</a></li>
					<li id="dropdown-pro" class="dropdown">
						<a href="javascript:;">More<i class="icon-down si si-arrow-drop-down"></i><i class="icon-up si si-arrow-drop-up"></i></a>
						<ul class="hidden">
							<li><a href="/programs">Programs</a></li>
							<li><a href="/updates">Updates</a></li>
							<li><a href="/sites">Sites</a></li>
							<li><a href="/help">Help</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
<script type="text/javascript">
	$(document).ready(function(){
		$(\'.dropdown-menu > .icon\').click(function(){
			$(this).hide().siblings(\'.icon\').show();
			$(\'.header_pro\').slideToggle(\'fast\');
			$(\'html\').toggleClass(\'alpha\');
		});
		$(\'#dropdown a\').click(function(e){
			var theEvent = window.event || e;
			if(document.all){  //ie识别
		        theEvent.cancelBubble=true;
		    }else{
		        theEvent.stopPropagation();
		    }
			$(\'#dropdown\').toggleClass(\'actived\');
			$(\'#dropdown\').find(\'ul\').slideToggle(\'fast\');
		});
		$(\'#dropdown-pro a\').click(function(){
			$(\'#dropdown-pro\').toggleClass(\'actived\');
			$(\'#dropdown-pro\').find(\'ul\').slideToggle(\'fast\');
		});
		$(\'body\').click(function(){
			if($(\'#dropdown\').hasClass(\'actived\')){
				$(\'#dropdown\').removeClass(\'actived\');
				$(\'#dropdown\').find(\'ul\').slideUp(\'fast\');
			}
		});
	});
	function submitFrom(){
		var value = document.getElementById("url").value;
		value = value.replace(/(^\s+)|(\s+$)/g, "");
		if(value!="")
			document.getElementById("url").value = value
		else
			document.getElementById("url").value = "http://www.youtube.com/watch?v=6XUnG0OfEgE"
		document.getElementById(\'download-form\').submit();
	}
</script>

<div class="banner">
	<div class="container">
	  <form method="get" id="download-form" action="/">
		<h2 class="title">Free Download Videos by Pasting Video URL</h2>
		<div class="form-group clear-both">
			<input class="fl" type="text" id="url" name="url" value="" placeholder="http://www.youtube.com/watch?v=6XUnG0OfEgE">
			<a class="fl" href="javascript:void(0);" onclick="submitFrom();">Download</a>
		</div>
	  </form>
	</div>
</div>
<a name="adContent"></a>
<div class="ad-content">
  <div class="add" >
	<div id="advert">
	
	</div>
  </div>
</div>
<script type="text/javascript">
	var isAndroid = navigator.userAgent.toLowerCase().match(/android/i) == "android";
	var isIphone = navigator.userAgent.toLowerCase().match(/iphone os/i) == "iphone os";
	var isIpad = navigator.userAgent.toLowerCase().match(/ipad/i) == "ipad";
	var isWinPhone = navigator.userAgent.toLowerCase().match(/windows phone/i) == "windows phone";
	var isMac = (navigator.platform == "Mac68K") || (navigator.platform == "MacPPC") || (navigator.platform == "Macintosh") || (navigator.platform == "MacIntel"); 
	$(function(){
		if(isMac){
			$(".if_btnMac").show();
			$(".if_btnWin").hide();
			$(".if_gotoMac").addClass("curr");
			$(".if_gotoWin").removeClass("curr");
		}else{
			$(".if_btnWin").show();
			$(".if_btnMac").hide();
			$(".if_gotoWin").addClass("curr");
			$(".if_gotoMac").removeClass("curr");
		}
		$(".if_gotoWin").click(function(){
			$(\'.if_btnMac\').hide();
			$(\'.if_btnWin\').show();
			$(".if_gotoWin").addClass("curr");
			$(".if_gotoMac").removeClass("curr");
		});
		$(".if_gotoMac").click(function(){
			$(\'.if_btnMac\').show();
			$(\'.if_btnWin\').hide();
			$(".if_gotoMac").addClass("curr");
			$(".if_gotoWin").removeClass("curr");
		});
	})
</script>
<div class="search-result-content">
	<div class="container-sm">
			<div class="row">
			<div class="item-3">
				<div class="heightDiv"></div>
				<img class="result-img" width="120px;" src="http://i.ytimg.com/vi/6XUnG0OfEgE/default.jpg">
				<p>Improve Your Grammar: 4 ways to use -ING words in English</p>
				<p>youtube.com 8:27</p>
			</div>
			<div class="item-9">
				<table class="result-table" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th class="al" width="25%">Quality</th>
							<th width="20%">Format</th>
							<th width="25%">Size</th>
							<th width="30%">Downloads</th>
						</tr>
					</thead>
					<tbody>
												<tr>
							<td class="al" width="25%">1080P/4K (Pro Version)</td>
							<td width="20%">MP4</td>
							<td width="25%">Unknown</td>
							<td><a href="javascript:;" onclick="downloadConfirm(event,\'video\')" class="btn btn-outline btn-sm curr" >Download Pro</a></td>
						</tr>
																		<tr>
							<td class="al" width="25%">(Max 720p)</td>
							<td width="20%">MP4</td>
							<td width="25%">Unknown</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?mt=1497368621&ratebypass=yes&signature=A68575BE6A2C23B94E18B1DA54460705A18EF7D9.0F26273AEEC9FB6C49A0AD17A594C6E2D2BDB317&expire=1497390354&id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&ipbits=0&mm=31&itag=22&ms=au&lmt=1492659266267711&mv=m&dur=506.705&source=youtube&key=yt6&ei=sQhAWb-hOsK71gKhn6CgCA&pl=24&mime=video%2Fmp4&requiressl=yes&sparams=dur%2Cei%2Cid%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ip=159.253.144.86&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  
												class="btn btn-outline btn-sm" onclick="openPopUp();" 
											download >Download</a></td>
						</tr>
												<tr>
							<td class="al" width="25%">480p</td>
							<td width="20%">MP4</td>
							<td width="25%">26.7M</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&ei=sQhAWb-hOsK71gKhn6CgCA&mv=m&mt=1497368621&pl=24&ip=159.253.144.86&ipbits=0&mime=video%2Fmp4&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&signature=B633D82D21A890569E93F9F78A7A5FE05D0E0A91.A20A6CE345426F7CA0CBC77E592D5DBA3CB677E2&key=yt6&gir=yes&lmt=1492659284905531&dur=506.705&source=youtube&requiressl=yes&clen=27976568&ratebypass=yes&itag=18&expire=1497390354&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 480p.mp4\');" 
												class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' 
											 >Download</a></td>
						</tr>
												<tr>
							<td class="al" width="25%">240p</td>
							<td width="20%">3GP</td>
							<td width="25%">13.2M</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?mt=1497368621&signature=30F0BD7EB8358E8687A689223FEBCD84C97E74FA.E2984BAC3E29C8AFCB669C4AE8CB94BFD51AB5F2&key=yt6&id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&lmt=1488316538265001&mv=m&dur=506.752&source=youtube&gir=yes&pl=24&requiressl=yes&ip=159.253.144.86&clen=13856745&ipbits=0&itag=36&ei=sQhAWb-hOsK71gKhn6CgCA&mime=video%2F3gpp&expire=1497390354&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Crequiressl%2Csource%2Cexpire&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 240p.3gp\');" 
												class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' 
											 >Download</a></td>
						</tr>
												<tr>
							<td class="al" width="25%">144p</td>
							<td width="20%">3GP</td>
							<td width="25%">4.9M</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?mt=1497368621&signature=C109BB9DB6B9BF72F491D8898B1F8DEED773118E.8DA193C6E659630C186ECCE2CDD4B2BC09803B6C&key=yt6&id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&lmt=1488316536464595&mv=m&dur=506.752&source=youtube&gir=yes&pl=24&requiressl=yes&ip=159.253.144.86&clen=5139476&ipbits=0&itag=17&ei=sQhAWb-hOsK71gKhn6CgCA&mime=video%2F3gpp&expire=1497390354&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Crequiressl%2Csource%2Cexpire&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 144p.3gp\');" 
												class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' 
											 >Download</a></td>
						</tr>
												<tr>
							<td class="al" width="25%">360p</td>
							<td width="20%">WEBM</td>
							<td width="25%">38.4M</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&ei=sQhAWb-hOsK71gKhn6CgCA&mv=m&mt=1497368621&pl=24&ip=159.253.144.86&ipbits=0&mime=video%2Fwebm&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&signature=C5905CF16CAD5A4802FA2DEA916BF377F630ACE3.1CD62FD7773CCECA966AA4744E1083F3D1F24BBB&key=yt6&gir=yes&lmt=1488318563384686&dur=0.000&source=youtube&requiressl=yes&clen=40295109&ratebypass=yes&itag=43&expire=1497390354&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 360p.webm\');" 
												class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' 
											 >Download</a></td>
						</tr>
											</tbody>
				</table>
								<table class="result-table video-only" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th class="al" colspan="4" width="100%">
								Video-only								<a href="/merge" class="guideA" target="_blank;">How to merge video and audio?</a>
							</th>
						</tr>
					</thead>
					<tbody>
												<tr>
							<td class="al" width="25%">1080p </td>
							<td width="20%">MP4</td>
							<td width="25%">117.1M</td>
							<td><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?keepalive=yes&id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&ei=sQhAWb-hOsK71gKhn6CgCA&mv=m&mt=1497368621&pl=24&ip=159.253.144.86&ipbits=0&mime=video%2Fmp4&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Crequiressl%2Csource%2Cexpire&signature=59048FC928CCB620EF8BF6CB58C06927AB97DE1F.0666D098A9AA21F90DE36E3E226004A365CB7FB0&key=yt6&gir=yes&lmt=1492659138710725&dur=506.639&source=youtube&requiressl=yes&clen=122768399&itag=137&expire=1497390354&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 1080p .mp4\');" class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' >
																																	Download</a></td>
						</tr>
											</tbody>
				</table>
				<!--  <a href="javascript:;" class="more-resolutions" id="more-resolutions"><span id="more">More resolutions</span><i class="icon-down si si-angle-down"></i><i class="icon-up si si-angle-up"></i></a> -->
							</div>
		</div>
								<div class="row mt40">
			<div class="item-3">
				<img class="result-img" src="http://images.keepvid.com/images/Audio2.png">
				<p>Audio</p>
			</div>
						<div class="item-9">
				<table class="result-table" border="0" cellspacing="0" cellpadding="0">
										<tbody>
												<tr>
							<td class="al" width="25%">256Kbps (Pro Version)</td>
							<td width="20%">MP3</td>
							<td width="25%">Unknown</td>
							<td><a href="javascript:;" onclick="downloadConfirm(event,\'music\')" class="btn btn-outline btn-sm curr">KeepVid Music</a></td>
						</tr>
																		<tr>
							<td class="al" width="25%">128 kbps </td>
							<td width="20%">M4A</td>
							<td width="25%">7.7M</td>
							<td width="30%"><a href="https://r1---sn-n4v7sn7l.googlevideo.com/videoplayback?keepalive=yes&id=o-AOJoPLE6tpw5Xz1vPtYXOBNImxj9MirZgQTynZrcMFH_&mn=sn-n4v7sn7l&mm=31&ms=au&ei=sQhAWb-hOsK71gKhn6CgCA&mv=m&mt=1497368621&pl=24&ip=159.253.144.86&ipbits=0&mime=audio%2Fmp4&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Crequiressl%2Csource%2Cexpire&signature=9221542291C55ADC5809B0CB3D0150F99FD23386.B1BEAA104E5C377F15853DEAF3E49C2427D0A726&key=yt6&gir=yes&lmt=1492659146046056&dur=506.705&source=youtube&requiressl=yes&clen=8048367&itag=140&expire=1497390354&title=Improve+Your+Grammar%3A+4+ways+to+use+-ING+words+in+English"  onclick="return rs(this,\'Improve Your Grammar: 4 ways to use -ING words in English 128 kbps .m4a\');" class="btn btn-outline btn-sm" new_title=\'Right-click and "Save Link As..."\' >
																																				Download</a></td>
						</tr>
											</tbody>
				</table>
			</div>
		</div>
						<div class="row mt40">
			<div class="item-3">
				<img class="result-img" src="http://images.keepvid.com/images/subtitle2.png">
				<p>Subtitle</p>
			</div>
			<div class="item-9">
				<table class="result-table" border="0" cellspacing="0" cellpadding="0">
					<tbody>
												<tr>
							<td class="al" width="25%">Subtitles</td>
							<td width="20%">SRT</td>
							<td width="25%">Unknown</td>
							<td width="30%"><a href="http://keepvid.com/?url=http%3A%2F%2Fyoutube.com%2Fwatch%3Fv%3D6XUnG0OfEgE&mode=subs"  class="btn btn-outline btn-sm"  >Download</a></td>
						</tr>
											</tbody>
				</table>
			</div>
		</div>
			</div>
  </div>
<script>
	var isMobile = "";
	if(isMobile)
		location.hash="#adContent";  
</script>
<!-- shadow-confirm -->
<div id="shadow-confirm-video" class="shadow-confirm">
	<div class="content">
		<div class="row">
			<div class="item-7">
				<p class="dd1">Install KeepVid Pro on Computer to Download HD Videos</p>
				<p class="dd2">Download this desktop program and save videos for offline enjoyment fast and easy.</p>
				<ul class="clear-both">
					<li>Download 1080P to 4K Videos</li>
					<li>YouTube to MP3 directly</li>
					<li>Download YouTube/Lynda playlist</li>
					<li>Support 10000+ video sites</li>
				</ul>
				<div class="btn-content">
					<a href="http://download.keepvid.com/keepvid-pro-desktop_full2957.exe" onclick="javascript:ga(\'send\', {hitType: \'event\',eventCategory:this.href,eventAction: \'Download-inpage-win-pro\',eventLabel: document.location.pathname});" class="btn-win" style="margin-right:20px;">Try It Free</a>
					<a href="http://download.keepvid.com/keepvid-pro-mac-desktop_full2958.dmg" onclick="javascript:ga(\'send\', {hitType: \'event\',eventCategory:this.href,eventAction: \'Download-inpage-mac-pro\',eventLabel: document.location.pathname});" class="btn-mac">Try It Free</a>
				</div>	
			</div>
		</div>
		<i class="icon si si-close"></i>
	</div>
</div>
<div id="shadow-confirm-music" class="shadow-confirm">
	<div class="content">
		<p class="dd1">Install KeepVid Music on Computer to Get Music Easily</p>
		<p class="dd2">Discover, record and download music from the music sites to your computer with high quality</p>
		<ul class="clear-both">
			<li>Download MP3 audio up to 320Kbps</li>
			<li>Download entire music playlist</li>
			<li>Record streaming music in high quality</li>
			<li>Discover music with built-in platform</li>
		</ul>
		<div class="btn-content">
			<a href="http://download.keepvid.com/keepvid-music-desktop_full2959.exe" onclick="javascript:ga(\'send\', {hitType: \'event\',eventCategory:this.href,eventAction: \'Download-inpage-win-music\',eventLabel: document.location.pathname});" class="btn-win" style="margin-right:20px;">Try It Free</a>
			<a href="http://download.keepvid.com/keepvid-music-mac-desktop_full2960.dmg" onclick="javascript:ga(\'send\', {hitType: \'event\',eventCategory:this.href,eventAction: \'Download-inpage-mac-music\',eventLabel: document.location.pathname});" class="btn-mac">Try It Free</a>
		</div>
		<i class="icon si si-close"></i>
	</div>
</div>
<!-- shadow-confirm end -->
<script type="text/javascript">
	$(\'.more-resolutions\').click(function(){
		$(this).toggleClass(\'curr\');
		$(\'.video-only\').slideToggle(\'fast\');
		if($("#more-resolutions").attr("class")=="more-resolutions"){
			$("#more").html("More resolutions");
		}
		else{
			$("#more").html("Less resolutions");
		}
	});
	
	$(\'.result-table\').on({
		\'mouseover\':function(e){
			var theEvent = window.event || e;
			var xx = theEvent.clientX;
			var yy = theEvent.clientY;
			var str = \'<div class="new_title_text" style="position:absolute;background-color:#fff;padding:4px 10px;box-shadow: 10px 10px 5px #888888;left:\'+parseFloat(xx+10)+\'px;top:\'+ parseFloat(yy+$(window).scrollTop()+10)+\'px">\'+$(this).attr(\'new_title\')+\'</div>\';
			if($(\'.new_title_text\').length == 0 && $(window).width()>979){
				$(\'body\').append(str);
			}
		},
		\'mouseout\':function(){
			$(\'.new_title_text\').remove();
		}
	},\'a[new_title]\');

	function downloadConfirm(e,obj){
		var e = window.event || e;
		if(document.all){  //ie识别
	        e.cancelBubble=true;
	    }else{
	        e.stopPropagation();
	    }
		if(obj == \'video\'){
			$(\'#shadow-confirm-video\').show();
			setTimeout(function(){
				$(\'#shadow-confirm-video\').addClass(\'curr\');
			},100);
		}else if(obj == \'music\'){
			$(\'#shadow-confirm-music\').show();
			setTimeout(function(){
				$(\'#shadow-confirm-music\').addClass(\'curr\');
			},100);
		}
	}
	$(\'.shadow-confirm .content\').click(function(e){
		var e = window.event || e;
		if(document.all){  //ie识别
	        e.cancelBubble=true;
	    }else{
	        e.stopPropagation();
	    }
	});
	$(\'.shadow-confirm .content .icon\').click(function(){
		$(\'.shadow-confirm\').removeClass(\'curr\');
			setTimeout(function(){
				$(\'.shadow-confirm\').css(\'display\',\'none\');
			},200);
	})
	$(\'body\').click(function(){
		if($(\'.shadow-confirm\').hasClass(\'curr\')){
			$(\'.shadow-confirm\').removeClass(\'curr\');
			setTimeout(function(){
				$(\'.shadow-confirm\').css(\'display\',\'none\');
			},200);
		}
	});
	function openPopUp(){
		if(navigator.userAgent.match(/Chrome/i)==null)
		    window.open(\'/pop-up\');
		else
			setTimeout("window.location.href=\'/pop-up\';",1000);
		return true;
	}
</script>
<!-- search success end -->
<div class="expecting-content">
	<div class="container-sm">
		<div class="row">
			<div class="item-7">
				<a name="feedback"></a>
				<p class="title">What you\'re expecting about KeepVid:</p>
				<div class="form-group">
					<form>
						<label style="width: 100%;">
							<textarea type="textarea" rows="4" name="download_from" placeholder="Write down your feedback here:"></textarea>
						</label>
						<label>
							<input type="text" name="name" placeholder="What your name is:">
						</label>
						<label>
							<input type="text" name="email" placeholder="Your Email Address:">
						</label>
						<a href="javascript:;" class="btn" id="expecting-submit">Submit</a>
					</form>
					<p class="share">Share with Friends: 
						<a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fkeepvid.com%2F" target="_blank"><i class="icon si si-facebook"></i></a>
						<a href="https://twitter.com/intent/tweet?text=[OFFICIAL]%20KeepVid:%20Download%20YouTube%20Videos,%20Facebook,%20Vimeo,%20Twitch.Tv,%20Dailymotion,%20Youku,%20Tudou,%20Metacafe%20and%20more!%20-%20http://keepvid.com&related=AddToAny,micropat" target="_blank"><i class="icon si si-twitter"></i></a>
						<!-- <a href=""><i class="icon si si-google-plus"></i></a> -->
					</p>
				</div>
			</div>
			<div class="item-1"></div>
			<div class="item-4">
						<div>
		<script src=\'//t.mdn2015x4.com/build/fd5cd5ab/v1/\'></script>
						<script src=\'//t.mdn2015x4.com/build/9cda5bcf/v1/\'></script>
			</div>
				</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(\'#expecting-submit\').click(function(){
			var site = $(\'.form-group textarea[name="download_from"]\');
			var name= $(\'.form-group input[name="name"]\');
			var email = $(\'.form-group input[name="email"]\');
			if(site.val() == \'\'){
				alertError(site,\'Write down your feedback here:\');
				return false;
			}else if (name.val() == \'\'){
				alertError(name,\'What your name is:\');
				return false;
			}else if(email.val() == \'\' || !isEmail(email.val())){
				alertError(email,\'Your Email Address:\');
				return false;
			}
			$.ajax({
				async:false,
				url: \'https://pro.keepvid.com/servers/public/\',
				type: "GET",
					data: { c: "savedata", a: "keepvid", site: site.val(), name: name.val(), email: email.val() },
					dataType: "jsonp",
					//jsonp: "callback",
					jsonpCallback: "OnPostMessSuccessByjsonp"
			});
		});
		$(\'.form-group label textarea,.form-group label input\').focus(function(){
			$(this).next(\'.error-tips\').remove();
		});
	});
	function alertError(ele,obj){
		var str = \'<div class="error-tips">\'+obj+\'</div>\';
		ele.after(str);
		setTimeout(function(){
			$(\'.error-tips\').css({\'opacity\':1,\'top\':\'-24px\'});
		},100);
	}
	function isEmail(str){
		var reg = /^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+.[a-zA-Z]{2,3}$/;
		return reg.test(str);
	}
	function OnPostMessSuccessByjsonp($m) {
		if($m.stauts == \'y\'){
			alert(\'Your request has been submitted successfully. Thanks!\');
			window.location.reload();
		}else{
			alert(\'Your request wasn’t submitted successfully. Please try again.\');
		}
	}
</script>
<div class="safe-content">
	<div class="container-sm">
		<p class="title">100% Safe &amp; Privacy Protected</p>
		<div class="row">
			<div class="item-6 al border-right">
				<img class="mb20" src="http://images.keepvid.com/images/webhorde.png">
				<br/>
				<img src="http://images.keepvid.com/images/icon3.png">
			</div>
			<div class="item-6">
				<img class="mt30" src="http://images.keepvid.com/images/mc-afee.png">
			</div>
		</div>
	</div>
</div><script>
	(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');

	ga(\'create\', \'UA-80717639-3\', \'auto\');
	ga(\'send\', \'pageview\');

</script>
<div class="expand-content">
	<div class="container-sm">
		<p class="title">Expand Your Video Collection</p>
		<div class="row">
		  <a href="/">
			<div class="item-4">
				<img src="http://images.keepvid.com/images/online.png">
				<p class="dd">Online</p>
			</div>
		  </a>
		  <a href="https://pro.keepvid.com/" target="_blank;">
			<div class="item-4">
				<img src="http://images.keepvid.com/images/keep-vid-pro.png">
				<p class="dd">KeepVid Pro</p>
			</div>
		  </a>
		  <a href="https://pro.keepvid.com/keepvid-music/" target="_blank;">
			<div class="item-4">
				<img src="http://images.keepvid.com/images/keep-vid-music.png">
				<p class="dd">KeepVid Music</p>
			</div>
		  </a>
		</div>
	</div>
</div>
<div class="about-content">
  <div class="container">
	<h3 class="title">About</h3>
	<p style="padding:20px 0;">KeepVid Video Downloader is a free web application that allows you to download videos from sites like YouTube, Facebook, Twitch.Tv, Vimeo, Dailymotion and many more. All you need is the URL of the page that has the video you want to download. Enter it in the textbox above and simply click \'Download\'. KeepVid will then fetch download links in all possible formats that the particular site provides.</p>
  </div>
</div>
<div class="footer1">
  <div class="container">
    <div class="row">
      <div class="item-6">
        <div class="footer-left">
          <h3 class="dd">The Best Free Online Video Downloader</h3>
          <div class="language-list fl">
            <ul class="hidden">
              				<li><a href="http://keepvid.com/">English</a></li>
			  				<li><a href="http://zh.keepvid.com/">简体中文</a></li>
			  				<li><a href="https://ar.keepvid.com/">العربية</a></li>
			  				<li><a href="https://br.keepvid.com/">Português</a></li>
			  				<li><a href="https://cs.keepvid.com/">Czech</a></li>
			  				<li><a href="https://da.keepvid.com/">Dansk</a></li>
			  				<li><a href="https://de.keepvid.com/">Deutsch</a></li>
			  				<li><a href="https://el.keepvid.com/">Ελληνικά</a></li>
			  				<li><a href="https://es.keepvid.com/">Español</a></li>
			  				<li><a href="https://fi.keepvid.com/">Suomi</a></li>
			  				<li><a href="https://fr.keepvid.com/">Français</a></li>
			  				<li><a href="https://ga.keepvid.com/">Gaeilge</a></li>
			  				<li><a href="https://hu.keepvid.com/">Magyar</a></li>
			  				<li><a href="https://it.keepvid.com/">Italiano</a></li>
			  				<li><a href="https://ms.keepvid.com/">Bahasa Melayu</a></li>
			  				<li><a href="https://nl.keepvid.com/">Nederlands</a></li>
			  				<li><a href="https://no.keepvid.com/">Norway</a></li>
			  				<li><a href="https://pl.keepvid.com/">Polski</a></li>
			  				<li><a href="https://sv.keepvid.com/">Svenska</a></li>
			  				<li><a href="https://th.keepvid.com/">ไทย</a></li>
			  				<li><a href="https://tr.keepvid.com/">Türkçe</a></li>
			  				<li><a href="https://vi.keepvid.com/">Tiếng Việt</a></li>
			  				<li><a href="https://www.keepvid.jp/">日本語</a></li>
			              </ul>
            <p class="language-btn">EN<i class="icon-up si si-angle-up"></i><i class="icon-down si si-angle-down"></i></p>
          </div>
          <p class="follow fl">Follow us :
          	<a href="https://www.facebook.com/KeepVidOfficial" target="_blank"><i class="icon si si-facebook"></i></a>
          	<a href="https://twitter.com/KeepVidOfficial" target="_blank"><i class="icon si si-twitter"></i></a>
          </p>
        </div>
      </div>
      <div class="item-6">
        <ul class="footer-navs">
          <li class="curr"><a href="/">KeepVid</a></li>
		  <li><a href="https://pro.keepvid.com">KeepVid Pro</a></li>
		  <li><a href="https://pro.keepvid.com/keepvid-music/">KeepVid Music</a></li>
		  <li><a href="/sites/download-youtube-video.html">Download YouTube Videos</a></li>
		  <li><a href="/sites/download-facebook-video.html">Download Facebook Videos</a></li>
		  <li><a href="/sites/download-dailymotion-video.html">Download Dailymotion Videos</a></li>
        </ul>
        <ul class="footer-navs">
          <li class="curr"><span>Support</span></li>
          <li><a href="http://support.keepvid.com/">FAQs</a></li>
          <li><a href="https://pro.keepvid.com/guide.html">Guide</a></li>
          <li><a href="https://pro.keepvid.com/reviews/index.html">Reviews</a></li>
          <li><a href="/contact">Contact us</a></li>
          <li><a href="https://pro.keepvid.com/resource/">Tips &amp; Tricks</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(\'.language-btn\').click(function(){
		$(this).toggleClass(\'curr\');
		$(\'.language-list ul\').slideToggle(\'fast\');
	});
</script>
<div class="footer2">
  <div class="container">
    <p>Copyright©2017 KeepVid. All Rights Reserved. <a href="/contact" style="color: #f8f8f8;">Contact Us</a> | <a href="/terms" style="color: #f8f8f8;">Privacy & Terms</a> | <a href="/official-keepvid-domains.html" style="color: #f8f8f8;">Official Domains</a></p>
  </div>
</div>
<div style="display:none;" id="advert-load">
				<script src=\'//t.mdn2015x4.com/build/7fcf959c/v1/\'></script>
	</div>
<script>
	$(function(){ 
		var html = $("#advert-load").html();
		$("#advert-load").html("");
		$("#advert").html(html);
	});  
</script>
</body>
		</html>';
}
