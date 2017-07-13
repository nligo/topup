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

    <!--表单验证-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/Bootstrap/css/bootstrapValidator.css"/>
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
                <a href="javascript:void(0)" class="logo"><img src="{{asset('Frontend')}}/images/logo.png" width="151"
                                                               height="41"/></a>
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
        <aside class="sidebar pa">
            <nav class="subnav" id="subnav">
                <ul>
                    <li class="item active" data-value="WeChat" data-text="微信支付"><a href="javascript:;"><i
                                    class="icon icon-weixin"></i>微信支付</a></li>
                    <li class="item" data-value="Alipay" data-text="支付宝支付"><a href="javascript:;"><i
                                    class="icon icon-alipay"></i>支付宝支付</a></li>
                    <li class="item" data-value="Yeepay" data-text="网银支付（易宝）"><a href="javascript:;"><i
                                    class="icon icon-yeepay"></i>网银支付（易宝）</a></li>
                </ul>
            </nav>
        </aside>
        <div class="pay-wrap pr" id="pay_wrap">
                    <h3 class="pay-title">当前的充值方式为&nbsp;<strong class="pay-mode">微信支付</strong>
                    </h3>
                    <table class="table-form">
                        <tbody>
                        <tr>
                            <td class="field">充值帐号：</td>
                            <td class="recharge-account"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="game-name pr tac" id="game_name"><span class="text">游戏名称</span><span
                                class="arrow pa"><div class="triangle bottom"></div></span></div>
                    <div class="server-name pr tac" id="server_name"><span class="text">游戏服务器</span><span
                                class="arrow pa"><div class="triangle bottom"></div></span></div>
                    <div class="game-list hide pa" id="game_list">
                        <div class="triangle top pa"></div>
                        <div class="close pa">×</div>
                        <ul class="cf">
                            <li><label class="lbl-rdo"><input class="rdo-game" type="radio" name="game" value="2"
                                                              data-text="风云无双" data-ratio="10">风云无双</label></li>
                            <li><label class="lbl-rdo"><input class="rdo-game" type="radio" name="game" value="5"
                                                              data-text="长城" data-ratio="100">长城</label></li>
                            <li><label class="lbl-rdo"><input class="rdo-game" type="radio" name="game" value="6"
                                                              data-text="铁骑冲锋" data-ratio="10">铁骑冲锋</label></li>
                        </ul>
                    </div>
                    <div class="server-list hide pa" id="server_list">
                        <div class="triangle top pa"></div>
                        <div class="close pa">×</div>
                        <ul class="cf">
                            <div class="tac"><img src="http://static.mlyx.com/pay/201607/images/loading.gif"></div>
                        </ul>
                    </div>
                    <div class="pw-title">角色名称：</div>
                    <ul class="role-list cf" id="role_list"></ul>
                    <div class="pw-title">充值金额：</div>
                    <ul class="amount-list cf" id="amount_list">
                        <li data-value="10">10元</li>
                        <li data-value="20">20元</li>
                        <li data-value="50">50元</li>
                        <li class="active" data-value="100">100元</li>
                        <li class="nrm" data-value="200">200元</li>
                        <li data-value="500">500元</li>
                        <li data-value="1000">1000元</li>
                        <li data-value="2000">2000元</li>
                        <li data-value="5000">5000元</li>
                        <li data-value="10000">10000元</li>
                    </ul>
                    <div class="pw-title ntm">其他金额：<input class="input other-amount" id="other_amount" type="text">元（最低充值金额10元）
                    </div>
                    <div class="pw-title ntm">对应元宝数量：<strong class="ingot" id="ingot">0</strong>[兑换比例1:<span
                                class="ratio" id="ratio"></span>]
                    </div>
                    <div class="btn-wrap tac">
                        <button class="btn btn-orange" id="btn_recharge" type="button">立即充值</button>
                        <div class="error-msg tac" id="error_msg"></div>
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

        <script type="text/javascript" src="{{asset('Frontend')}}/js/jquery-1.10.1.min.js"></script>
        <!--支持ie8-->
        <script type="text/javascript" src="{{asset('Frontend')}}/js/html5shiv.js"></script>
        <script type="text/javascript" src="{{asset('Frontend')}}/js/respond.js"></script>
        <!--表单验证-->
        <script type="text/javascript"
                src="{{asset('Frontend')}}/Bootstrap/js/bootstrap-datetimepicker.zh-CN.js"></script>
        <!--本页面js-->
        <script type="text/javascript" src="{{asset('Frontend')}}/js/publicFun.js"></script>

        <script type="text/javascript" src="{{asset('Frontend')}}/js/layer/layer.js"></script>
</body>
</html>
