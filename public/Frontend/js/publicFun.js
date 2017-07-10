jQuery.extend({
/**
     * 不带确定按钮的消息提示
     * @param {} msg 消息内容
     * @param {} type 提示类型 1：成功，2：错误，3：询问，0：警告
     * @param {} callBack 信息提示关闭后的回调函数 
     * @returns {} 
     */
    showInfoMessage: function (msg, type, callBack) {
        layer.msg(msg, { icon: type || 0, shade: 0.4, time: 3000, title: '系统提示' }, function () {
            if (typeof (callBack) == "function") {
                callBack();
            }
        });
    },
    showInfoMessages: function (msg, type, callBack) {
        layer.msg(msg, { icon: type || 0, shade: 0.4, time: 4000, title: '系统提示' }, function () {
            if (typeof (callBack) == "function") {
                callBack();
            }
        });
    },
    /**
     * 带确定按钮的消息提示
     * @param {} msg 消息内容
     * @param {} type 提示类型 1：成功，2：错误，3：询问，0：警告
     * @param {} callBack 点击确定按钮后的回调函数
     * @returns {} 
     */
    showAlertMessage: function (msg, type, callBack) {
        layer.alert(msg, { icon: type || 0, title: '系统提示' }, function () {
            if (typeof (callBack) == "function") {
                callBack();
            }
        });
    },
    /**
     * 带确定、取消按钮的消息提示
     * @param {} msg 消息内容
     * @param {} yesCallBack 点击确定按钮后的回调函数
     * @param {} noCallBack 点击取消按钮后的回调函数
     * @returns {} 
     */
    showConfirmMessage: function (msg, yesCallBack, noCallBack) {
        layer.confirm(msg, { icon: 3, title: '系统提示' }, function (index) {
            if (typeof (yesCallBack) == "function") {
                yesCallBack(index);
            }
        }, function () {
            if (typeof (noCallBack) == "function") {
                noCallBack();
            }
        });
    },
});