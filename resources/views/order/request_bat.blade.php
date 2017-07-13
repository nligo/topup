<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="icon" type="image/x-icon" href="/ico.png"/>

    <title> 充值 | 游狗通行证</title>
    <link rel="stylesheet" href="{{asset('Frontend')}}/Bootstrap/css/bootstrap.css"/>
    <link href="{{asset('Frontend')}}/css/base.css" rel="stylesheet">

    <link href="{{asset('Frontend')}}/molin/css/main.css" rel="stylesheet">
    <link href="{{asset('Frontend')}}/molin/css/mlyx.css" rel="stylesheet">
    <link href="{{asset('Frontend')}}/css/loing.css" rel="stylesheet">
    <link href="{{asset('Frontend')}}/css/loing.css" rel="stylesheet">
    <!--表单验证-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/Bootstrap/css/bootstrapValidator.css"/>
    <link rel="stylesheet" href="{{asset('Frontend')}}/lib/skin/minimal/color-scheme.css"/>
    <!--图标-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome-ie7.min.css"/>
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
                    <a href="{{ config('app.passport_url') }}">用户中心</a>
                    <a href="{{route("order_request")}}" class="nav-on">充值中心</a>
                    <a href="javascript:void(0)" onclick="alertFun()">客服中心</a>

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-1 column"></div>
            <div class="col-md-2 column">
                <div></div>
                <aside class="sidebar pa" style="width:100%;height:620px;border-left:1px solid #ff8e32;">
                    <nav class="subnav">
                        <ul>
                            <li class="item active" data-value="WeChat" data-text="支付宝支付"><a href="javascript:;"><i
                                            class="icon icon-weixin"></i>支付宝支付</a></li>
                        </ul>
                    </nav>
                </aside>
            </div>
            <div class="col-md-8 column" style="background-color: #fff">
                <div class="pay-wrap" style="margin-top: 5%;width: 100%">
                    <h3 class="pay-title">当前的充值方式为&nbsp;<strong class="pay-mode">支付宝支付</strong>
                    </h3>
                    <form class="form-horizontal mt20" action="{{ route('order_store') }}" id="topup_form" method="post">
                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">游戏账号： </label>
                            <div class="col-lg-5 col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="text" id="account" name="account" class="input"
                                           required
                                           datatype="/^[a-zA-z]\w{3,15}$/" errormsg="请输入合法账号" ajaxurl="{{ route("user_checkuser") }}" nullmsg="游戏账号"/>
                                </label>
                            </div>
                        </div>
                        <input name="form_token" value="{{ Session::get('form_token')}}" type="hidden"/>
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">确认账号： </label>
                            <div class="col-lg-5 col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="text" class="input" id="rUsername" name="rUsername" datatype="*6-18"
                                           required recheck="account" errormsg="两次账号不一致！"/>
                                </label>
                            </div>
                        </div>

                        <div  class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">游戏区服： </label>
                            <div class="col-lg-5 col-sm-6">
                                <label class="checkbox-inline">
                                    <select class="input" name="areaClothingId">
                                        <option value="0">请选择</option>
                                        <option value="1">线路一</option>
                                        <option value="2">线路二</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div  class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">充值金额： </label>
                            <div class="col-lg-9 col-sm-6">
                                <label class="checkbox-inline">
                                    <ul class="amount-list cf" id="amount_list">
                                        <li>
                                            <input type="radio" name="topupAmount" value="1"> 1元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="10"> 10元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="20"> 20元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="50"> 50元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="100"> 100元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="200"> 200元
                                        </li>

                                        <li>
                                            <input type="radio" name="topupAmount" value="500" checked> 500元
                                        </li>
                                    </ul>
                                </label>
                            </div>
                        </div>
                        <div  class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">充值数量： </label>
                            <div class="col-lg-9 col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="number" class="input" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width: 120px" value="10"
                                           datatype="/^\+?[1-9]\d*$/" errormsg="请输入正确的数量" nullmsg="请输入数量" name="topupNum">
                                </label>
                            </div>
                        </div>
                        <div  class="form-group">
                            <label class="col-lg-2 col-sm-2 control-label">联系方式： </label>
                            <div class="col-lg-9 col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="text" class="input" id="phone" name="phone" required
                                           errormsg="手机号码格式不对"
                                           nullmsg="手机号码不能为空" datatype="m"/>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="payType" id="payType" value="alipay">
                        <div class="btn-wrap tac">
                            <button type="submit" name="btn" class="btn btn-orange">立即充值</button>
                            <div class="error-msg tac" id="error_msg"></div>
                        </div>
                    </form>





                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="n-container c">
            <img src="{{asset('Frontend')}}/images/ft-logo.png" class="fl ft-logo" width="311" height="59"/>
            <div class="ft-right fr">
                <p>抵制不良游戏 拒绝盗版游戏 谨防上当受骗 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</p>
                <p>西安游狗网络科技有限公司 | <a href="/agreement" target="_blank">用户协议</a> | <a href="javascript:void(0)">商务联系</a>
                    | <a href="javascript:void(0)">客户服务</a></p>
                <p>Copyright © 2012-2017 西安游狗网络科技有限公司</p>
                <p><a href="http://www.miibeian.gov.cn/" target="_blank">陕ICP备12003563号-1</a> <a
                            href="http://www.ugogame.com.cn/images/icp.jpg" target="_blank">增值电信业务许可证:陕B-20170022</a><a
                            href="http://www.ugogame.com.cn/images/www.jpg" target="_blank">
                        陕网文(2016)6655-027号</a>
                    <a href="http://sq.ccm.gov.cn/ccnt/sczr/service/business/emark/toDetail/0fb8fd3125c848d4aa5a5018b52cb598"
                       target="_blank" class="sww-ico"></a>
                </p>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('Frontend')}}/lib/icheck.js"></script>

<script type="text/javascript" src="{{asset('Frontend')}}/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="{{asset('Frontend')}}/js/validform.min.js"></script>
<script type="text/javascript" src="{{asset('Frontend')}}/js/layer/layer.js" ></script>

<script type="text/javascript">
    function alertFun() {
        //询问框
        layer.confirm('此功能暂未开启!', {
            btn: ['关闭'] //按钮
        })
    }
    $("#topup_form").Validform({
        tiptype: 3,
        postonce: true,
    });
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>