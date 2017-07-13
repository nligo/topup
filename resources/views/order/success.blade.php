
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/x-icon" href="/ico.png" />

    <title> 充值 | 游狗通行证</title>
    <link rel="stylesheet" href="{{asset('Frontend')}}/Bootstrap/css/bootstrap.css" />
    <link href="{{asset('Frontend')}}/css/base.css" rel="stylesheet">
    <link href="{{asset('Frontend')}}/css/loing.css" rel="stylesheet">

    <!--表单验证-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/Bootstrap/css/bootstrapValidator.css" />
    <!--图标-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome-ie7.min.css" />
    <link href="{{asset('Frontend')}}/css/index.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
    <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrap">

    <div class="head">

        <div class="nav">
            <div class="n-container c">
                <a href="javascript:void(0)" class="logo"><img src="{{asset('Frontend')}}/images/logo.png" width="151" height="41" /></a>
                <div class="nav-right">
                    <a href="http://www.xytwz.com.cn" target="_blank">新倚天</a>
                    <a href="/user/center">用户中心</a>
                    <a href="javascript:void(0)" onclick="alertFun()">充值中心</a>
                    <a href="javascript:void(0)" onclick="alertFun()">客服中心</a>

                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="n-container">
            <div class="mpd100 min-height500">
                <!--设置成功公用代码-->
                <div class="mt25" id="question_msg">
                    <p class="sp msuccess"></p>
                    <p class="text-center mgreen mft16">订单操作成功</p>
                    <div class="text-center mt20">
                        <a href="{{ config('app.passport_url') }}" class="mbtn-l mbtn-cyan-bd">正在自动跳转到上一页，如没有跳转请点击此链接</a>
                    </div>
                </div>
                <!--设置成功公用代码 end-->

            </div>
        </div>
    </div>
    <div class="footer">
        <div class="n-container c">
            <img src="{{asset('Frontend')}}/images/ft-logo.png" class="fl ft-logo" width="311" height="59" />
            <div class="ft-right fr">
                <p>抵制不良游戏 拒绝盗版游戏 谨防上当受骗 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</p>
                <p>西安游狗网络科技有限公司   |  <a href="/agreement" target="_blank">用户协议</a>  |  <a href="javascript:void(0)">商务联系</a>  |  <a href="javascript:void(0)">客户服务</a></p>
                <p>Copyright © 2012-2017 西安游狗网络科技有限公司</p >
                <p><a href="http://www.miibeian.gov.cn/" target="_blank">陕ICP备12003563号-1</a > <a  href="http://www.ugogame.com.cn/images/icp.jpg" target="_blank">增值电信业务许可证:陕B-20170022</a ><a  href="http://www.ugogame.com.cn/images/www.jpg" target="_blank"> 陕网文(2016)6655-027号</a>
                    <a  href="http://sq.ccm.gov.cn/ccnt/sczr/service/business/emark/toDetail/0fb8fd3125c848d4aa5a5018b52cb598" target="_blank" class="sww-ico"></a>
                </p >
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('Frontend')}}/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
    function countDown(secs,surl){
        secs = secs-1;
//        var jumpTo = document.getElementById('jumpTo');
        innerHTML=secs+'s';
        if(secs>0){
            setTimeout("countDown("+secs+",'"+surl+"')",1000);
        }
        else{
            location.href=surl;
        }
    }

    countDown(3,'{{ route('order_request') }}');

</script>
</body>
</html>
