
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

    <!--图标-->
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{asset('Frontend')}}/css/font-awesome-ie7.min.css" />
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
        </div></div>
    <div class="content">
        <div class="n-container">
            <div class="pd">
                <h2 class="re-title">在线充值</h2>
                <div class="re-box">
                    <form class="form-horizontal mt40" method="post" action="{{ route('order_store') }}"id="topup_form">
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"></label>
                            <div class="col-lg-8 col-sm-6">
                                <p class="mcyan"><em class="fa fa-exclamation-circle"></em>请仔细核对账号与联系方式
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"><span class="mred mr5">&nbsp;*</span>游戏账号：
                            </label>
                            <div class="col-lg-5 col-sm-6">
                                <input type="text" id="account" name="account" class="form-control"
                                       required
                                       datatype="/^[a-zA-z]\w{3,15}$/" errormsg="请输入合法账号" ajaxurl="{{ route("user_checkuser") }}" nullmsg="游戏账号"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"><span class="mred mr5">&nbsp;*</span>确认账号：
                            </label>
                            <div class="col-lg-5 col-sm-6">
                                <input type="text" class="form-control" id="rUsername" name="rUsername" datatype="*6-18"
                                       required recheck="account" errormsg="两次账号不一致！"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label">充值金额： </label>
                            <div class="col-lg-5 col-sm-6">
                                <select class="form-control" name="topupAmount" id="topupAmount" required>
                                    <option value="">请选择充值金额</option>
                                    <option value="0.01">0.01</option>
                                    <option value="10">10</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <input name="form_token" value="{{ Session::get('form_token')}}" type="hidden"/>
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"><span class="mred mr5">&nbsp;*</span>购买数量：
                            </label>
                            <div class="col-lg-5 col-sm-6">
                                <input type="number" class="form-control" value="10" style="width: 100px"
                                       datatype="/^\+?[1-9]\d*$/" errormsg="请输入正确的数量" nullmsg="请输入数量" name="topupNum">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"><span class="mred mr5">&nbsp;*</span>联系方式：
                            </label>
                            <div class="col-lg-5 col-sm-6">
                                <input type="text" class="form-control" id="phone" name="phone" required
                                       errormsg="手机号码格式不对"
                                       nullmsg="手机号码不能为空" datatype="m"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label"><span class="mred mr5">&nbsp;*</span>支付方式：
                            </label>
                            <div class="col-lg-5 col-sm-6">
                                <input type="radio" value="alipay" checked name="payType">支付宝
                            </div>
                        </div>

                        <button type="submit" class="btn-cyan mbtn-lg mt20 mauto" name="btn" id="test_but">确认充值</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('Frontend')}}/js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="{{asset('Frontend')}}/js/validform.min.js"></script>
    <script type="text/javascript">

        $("#topup_form").Validform({
            tiptype: 4,
            postonce: true,
        });
        var InterValObj; //timer变量，控制时间
        var count = 120; //间隔函数，1秒执行
        var curCount;//当前剩余秒数

        function sendMessage() {
            //验证手机号格式
            var tel = $('#mobile').val();
            var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[0135678]|18[0-9]|14[579])[0-9]{8}$/;
            if (tel == '') {
                alert('手机号码不能为空');
                return false;
            }
            if (reg.test(tel) == false) {
                alert('手机号码格式不对');
                return false;
            }
            $.ajax({
                type: "post", //以post方式与后台沟通
                url: "/send-sms", //与此php页面沟通
                dataType: 'json',//从php返回的值以 JSON方式 解释
                async: false,
                data: {'mobile': tel},
                success: function (data) {//如果调用php成功
                    res = data;

                }
            })
            if (res.code != 0) {
                alert(res.msg);
                return false;
            }
            curCount = count;
            //设置button效果，开始计时
            $("#btnSendCode").attr("disabled", "true");
            $("#btnSendCode").val(curCount + "秒后重新发送");
            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
            //向后台发送处理数据

        }

        //timer处理函数
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $("#btnSendCode").removeAttr("disabled");//启用按钮
                $("#btnSendCode").val("发送短信验证码");
            }
            else {
                curCount--;
                $("#btnSendCode").val(curCount + "秒后重新发送");
            }
        }
    </script>


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
</body>
</html>
