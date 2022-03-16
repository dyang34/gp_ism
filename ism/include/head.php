<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

error_reporting(E_ALL ^ E_NOTICE);

@session_start();
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>통합 매출 관리 시스템(ISM)</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="robots" content="noindex">  <!-- 검색엔진로봇 수집 차단. -->

        <link rel="shortcut icon" href="http://assets.tumblr.com/images/favicons/favicon.ico?" />
        <link rel="apple-touch-icon-precomposed" href="http://assets.tumblr.com/images/apple_touch_icon.png?"/>
        
        <link type="text/css" rel="stylesheet" href="/ism/css/table_style.css?t=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="/ism/css/write_style.css?t=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="/ism/css/category_style.css?t=<?php echo time(); ?>" />
        
        <script type="text/javascript" src="/ism/js/jquery-3.4.1.min.js"></script>
    </head>