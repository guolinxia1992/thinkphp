<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\phpStudy\WWW\twothink\public/../application/home/view/default/service\index.html";i:1552813961;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="/homecss/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/homecss/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main{margin-bottom: 60px;}
        .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
    </style>
</head>
<body>
<div class="main">
    <!--导航部分-->
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container-fluid text-center">
            <div class="col-xs-3">
                <p class="navbar-text"><a href="index.html" class="navbar-link">首页</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">服务</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">发现</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">我的</a></p>
            </div>
        </div>
    </nav>
    <!--导航结束-->

    <div class="container-fluid">
        <?php if(!(empty($services) || (($services instanceof \think\Collection || $services instanceof \think\Paginator ) && $services->isEmpty()))): if(is_array($services) || $services instanceof \think\Collection || $services instanceof \think\Paginator): $i = 0; $__LIST__ = $services;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$services): $mod = ($i % 2 );++$i;?>
        <div class="row noticeList">
           <!--<a href="<?php echo url('Notice/notice_detail?id='.$services['id']); ?>">-->
            <a href="<?php echo url('Service/service_detail?id='.$services['id']); ?>">
                <div class="col-xs-2">
                    <img class="noticeImg" src="/homecss/image/1.png" />
                </div>
                <div class="col-xs-10">
                    <p class="title"> <?php echo $services['title']; ?></p>
                    <p class="intro"><?php echo $services['description']; ?></p>
                    <p class="info">浏览: <?php echo $services['view']; ?> <span class="pull-right"><?php echo time_format($services['update_time']); ?></span> </p>
                </div>
            </a>
        </div>

        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td>
        <?php endif; ?>
    </div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="../jquery-1.11.2.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>