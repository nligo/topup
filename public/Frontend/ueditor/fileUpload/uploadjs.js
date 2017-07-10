

function GUID() {
    this.date = new Date();
    /* 判断是否初始化过，如果初始化过以下代码，则以下代码将不再执行，实际中只执行一次 */
    if (typeof this.newGUID != 'function') {

        /* 生成GUID码 */
        GUID.prototype.newGUID = function () {
            this.date = new Date();
            var guidStr = '';
            sexadecimalDate = this.hexadecimal(this.getGUIDDate(), 16);
            sexadecimalTime = this.hexadecimal(this.getGUIDTime(), 16);
            for (var i = 0; i < 9; i++) {
                guidStr += Math.floor(Math.random() * 16).toString(16);
            }
            guidStr += sexadecimalDate;
            guidStr += sexadecimalTime;
            while (guidStr.length < 32) {
                guidStr += Math.floor(Math.random() * 16).toString(16);
            }
            return this.formatGUID(guidStr);
        }

        /*
        * 功能：获取当前日期的GUID格式，即8位数的日期：19700101
        * 返回值：返回GUID日期格式的字条串
        */
        GUID.prototype.getGUIDDate = function () {
            return this.date.getFullYear() + this.addZero(this.date.getMonth() + 1) + this.addZero(this.date.getDay());
        }

        /*
        * 功能：获取当前时间的GUID格式，即8位数的时间，包括毫秒，毫秒为2位数：12300933
        * 返回值：返回GUID日期格式的字条串
        */
        GUID.prototype.getGUIDTime = function () {
            return this.addZero(this.date.getHours()) + this.addZero(this.date.getMinutes()) + this.addZero(this.date.getSeconds()) + this.addZero(parseInt(this.date.getMilliseconds() / 10));
        }

        /*
        * 功能: 为一位数的正整数前面添加0，如果是可以转成非NaN数字的字符串也可以实现
        * 参数: 参数表示准备再前面添加0的数字或可以转换成数字的字符串
        * 返回值: 如果符合条件，返回添加0后的字条串类型，否则返回自身的字符串
        */
        GUID.prototype.addZero = function (num) {
            if (Number(num).toString() != 'NaN' && num >= 0 && num < 10) {
                return '0' + Math.floor(num);
            } else {
                return num.toString();
            }
        }

        /* 
        * 功能：将y进制的数值，转换为x进制的数值
        * 参数：第1个参数表示欲转换的数值；第2个参数表示欲转换的进制；第3个参数可选，表示当前的进制数，如不写则为10
        * 返回值：返回转换后的字符串
        */
        GUID.prototype.hexadecimal = function (num, x, y) {
            if (y != undefined) {
                return parseInt(num.toString(), y).toString(x);
            } else {
                return parseInt(num.toString()).toString(x);
            }
        }

        /*
        * 功能：格式化32位的字符串为GUID模式的字符串
        * 参数：第1个参数表示32位的字符串
        * 返回值：标准GUID格式的字符串
        */
        GUID.prototype.formatGUID = function (guidStr) {
            var str1 = guidStr.slice(0, 8) + '-',
            str2 = guidStr.slice(8, 12) + '-',
            str3 = guidStr.slice(12, 16) + '-',
            str4 = guidStr.slice(16, 20) + '-',
            str5 = guidStr.slice(20);
            return str1 + str2 + str3 + str4 + str5;
        }
    }
}
/**
 * 以百度上传组件WebUploader为基础的简单二次封装插件
 * 
 * 2016-05-26   wmg
 * 
 * 默认参数：
 *      @multiple：是否开启多附件上传，默认不开启
 *      @uploadUrl：指定上传的地址
 *      @fileTypes：指定允许上传的附件类型，默认的情况下只能是图片类型。如果uploadType = 1的话，也只能上传图片类型。
 *      @btnText：按钮显示名称
 *      @fileSize：单个文件上传大小，默认最大为1024KB
 *      @fileNumLimit：文件上传个数，如果为开启多附件上传，fileNumLimit会重新设置为1
 *      @uploadType：上传类型，如果为1的话，认为上传的都是图片，会创建缩略图，其他值都不会创建缩略图
 *      @isUploadToServe:是否上传至服务器，默认不上传
 *      @data
 *      @thumbnailWidth：缩略图宽度，只有上传图片的时候有效
 *      @thumbnailHeight：缩略图高度，只有上传图片的时候有效
 * 
 * 使用方法：
 *      初始化：$('#xxx').InitUploader();
 *      获取上传附件路径：$('#xxx').InitUploader('getData');返回数据格式：{ id: '', name: '', url: '', src: '' }
 *      赋值：$('#xxx').InitUploader('setData', [])，需要的数据格式：数组json对象
 * 
 */
; (function ($, window, document, undefined) {
    var methods = {
        init: function (options) {
            return this.each(function () {
                if (!WebUploader.Uploader.support()) {
                    alert('Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器');
                    throw new Error('WebUploader does not support the browser you are using.');
                };

                var $this = $(this),
                    settings = $this.data('InitUploader');

                if (typeof (settings) == 'undefined') {
                    settings = $.extend({}, $.fn.InitUploader.defaultOptions, options || {});
                    $this.data('InitUploader', settings);
                } else {
                    settings = $.extend({}, settings, options);
                }
                if (typeof (settings.fileNumLimit) == "undefined") {
                    settings.fileNumLimit = 1;
                }
                if (settings.uploadType === 1) {
                    settings.fileTypes = 'jpeg,jpg,png,bmp';
                }
                //else {
                //    settings.fileTypes = undefined;
                //}

                // 如果只允许上传单个附件，则禁止同时选择多个文件
                if (settings.fileNumLimit === 1) {
                    settings.multiple = false;
                }
                if (typeof (settings.data) === "undefined") {
                    settings.data = new Array();
                }
                var $btnUpload = $('<div></div>').html(settings.btnText).appendTo($this),
                    $fileList = $('<ul></ul>').appendTo($this),
                    $webUploader = WebUploader.create({
                        auto: true,
                        pick: {
                            id: $btnUpload,
                            multiple: settings.multiple
                        },
                        accept: {
                            extensions: settings.fileTypes
                        },
                        formData: {
                            isUploadToServe: settings.isUploadToServe,
                            IsChunk: settings.IsChunk,
                            guid: (new GUID()).newGUID()
                        },
                        fileSingleSizeLimit: settings.fileSize,
                        swf: 'Uploader.swf',
                        server: settings.uploadUrl,
                        fileNumLimit: settings.fileNumLimit,
                        timeout: settings.timeOut,
                        chunked: settings.IsChunk
                    });

                // 错误捕捉
                $webUploader.on('error', function (type) {
                    switch (type) {
                        case 'Q_EXCEED_NUM_LIMIT':
                            alert('错误：上传文件数量已超过最大限制数量:' + settings.fileNumLimit + '个！');
                            break;
                        case 'Q_EXCEED_SIZE_LIMIT':
                            alert('错误：上传文件总大小超出限制:' + settings.fileSize * settings.fileNumLimit / 1024 / 1024 + 'M！');
                            break;
                        case 'F_EXCEED_SIZE':
                            alert('错误：文件大小超出限制:' + settings.fileSize / 1024 / 1024 + 'M！');
                            break;
                        case 'Q_TYPE_DENIED':
                            alert('错误：该类型的文件禁止上传,可上传的文件类型包括:' + settings.fileTypes + '！');
                            break;
                        case 'F_DUPLICATE':
                            alert("错误：请勿重复上传该文件！");
                            break;
                        case 'abort':
                            alert("错误:网络中断,请重新上传!");
                            break;
                        case 'timeout':
                            alert("错误:网络中断,请重新上传!");
                            break;
                        default:
                            alert('错误代码：' + type);
                            break;

                    }
                });

                // 加入队列之前，判断是否已超过允许上传文件数量
                $webUploader.on('beforeFileQueued', function (file) {
                    var count = 0,
                        data = settings.data,
                        fileNumLimit = settings.fileNumLimit;
                    for (var item in data) {
                        count++;
                    }
                    if (count >= fileNumLimit) {
                        alert('错误：上传文件数量已超过最大限制' + fileNumLimit + '个！');
                        return false;
                    }
                    return true;
                });

                // 加入队列
                $webUploader.on('fileQueued', function (file) {
                    //创建进度条
                    var fileProgressObj = $('<div class="upload-progress"></div>').appendTo($this);
                    $('<span class="txt">正在上传，请稍候...</span>').appendTo(fileProgressObj);
                    $('<span class="bar"><b></b></span>').appendTo(fileProgressObj);
                    var $li = $('<li id="' + file.id + '"></li>');

                    if (settings.uploadType === 1) {
                        $li.addClass('img-file-list clearfix').append($('<img class="thumbnail" /><div class="file-title">' + file.name + '</div>'));
                    } else {
                        $li.addClass('file-list clearfix').append($('<a href="javascript:;" class="file-title">' + file.name + '</a>'));
                    }

                    if (settings.fileNumLimit) {
                        $fileList.append($li);
                    } else {
                        //var files = $webUploader.getFiles();
                        //if (files.length > 0) {
                        //    $.each(files, function (index, item) {
                        //        $webUploader.removeFile(item, true);
                        //    });
                        //}
                        $fileList.empty().append($li);
                    }
                    if (settings.uploadType === 1) {
                        var $img = $li.find('img');

                        if (typeof (settings.thumbnailWidth) === "undefined") {
                            settings.thumbnailWidth = 280;
                        }
                        if (typeof (settings.thumbnailHeight) === "undefined") {
                            settings.thumbnailHeight = 200;
                        }

                        // 创建缩略图
                        $webUploader.makeThumb(file, function (error, src) {
                            if (error) {
                                $img.replaceWith('<span>不能预览</span>');
                                return;
                            }
                            $img.attr('src', src);
                        }, settings.thumbnailWidth, settings.thumbnailWidth);
                    }
                });

                //文件上传过程中创建进度条实时显示
                $webUploader.on('uploadProgress', function (file, percentage) {
                    var progressObj = $this.children(".upload-progress");
                    progressObj.children(".txt").html(file.name);
                    progressObj.find(".bar b").width(percentage * 100 + "%");
                });

                // 文件上传成功。
                $webUploader.on('uploadSuccess', function (file, response) {
                    //var files = $webUploader.getFiles();
                    //if (files.length > 0) {
                    //    $.each(files, function (index, item) {
                    //        $webUploader.removeFile(item, true);
                    //    });
                    //}
                    var $file = $('#' + file.id);
                    if (response.ErrCode === 0) {
                        //if ($file.length > 0) {
                        settings.data[file.id] = { id: file.id, name: file.name, url: '', src: response.ErrMessage };
                        $file.append($('<a href="javascript:;" class="file-delete" title="删除">×</a>').bind('click', function () {
                            $webUploader.removeFile(file, true);
                            $file.remove();
                            delete settings.data[file.id];
                        }));
                    } else {
                        alert(file.name + "上传失败，错误原因：" + response.ErrMessage);
                        $webUploader.removeFile(file, true);
                        $file.remove();
                    }

                    var progressObj = $this.children(".upload-progress");
                    progressObj.children(".txt").html("上传成功：" + file.name);
                });

                //$webUploader.on('fileDequeued', function (file) {

                //});
                //当文件上传出错时触发
                $webUploader.on('uploadError', function (file, reason) {
                    $webUploader.removeFile(file, true); //从队列中移除
                    alert(file.name + "上传失败，错误代码：" + reason);
                });

                //不管成功或者失败，文件上传完成时触发
                $webUploader.on('uploadComplete', function (file) {
                    var progressObj = $this.children(".upload-progress");
                    progressObj.children(".txt").html("上传完成");

                    //如果队列为空，则移除进度条
                    if ($webUploader.getStats().queueNum === 0) {
                        progressObj.remove();
                    }
                });
            });
        },
        getData: function () {
            var $this = $(this),
                settings = $this.data('InitUploader'),
                data = settings.data,
                returnData = [];
            if (typeof (data) === "object") {
                for (var key in data) {
                    returnData.push(data[key]);
                }
            }
            return returnData;
        },
        setData: function (data) {
            return this.each(function () {
                var $this = $(this),
                    $fileList = $this.find('ul'),
                    settings = $this.data('InitUploader');
                if (typeof (data) === "object") {
                    $.each(data, function (index, item) {
                        settings.data[item.id] = { id: item.id, name: item.name, url: item.url, src: item.src };
                        var $li = $('<li id="' + item.id + '"></li>');

                        if (settings.uploadType === 1) {
                            if (typeof (settings.thumbnailWidth) === "undefined") {
                                settings.thumbnailWidth = 300;
                            }
                            if (typeof (settings.thumbnailHeight) === "undefined") {
                                settings.thumbnailHeight = 200;
                            }
                            $li.addClass('img-file-list clearfix').append($('<img class="thumbnail" /><div class="file-title">' + (item.name || '') + '</div>'));
                            var $img = $li.find('img');
                            $img.attr('src', item.url).css({ width: settings.thumbnailWidth, height: settings.thumbnailHeight });
                        } else {
                            $li.addClass('file-list clearfix').append($('<a href="javascript:;" class="file-title" onclick="$.downloadFile(\'' + item.id + '\', \'' + item.name + '\')">' + (item.name || '') + '</a>'));
                        }

                        $fileList.append($li.append($('<a href="javascript:;" class="file-delete" title="删除">×</a>').bind('click', function () {
                            $li.remove();
                            delete settings.data[item.id];
                        })));
                    });
                }
            });
        }
    };

    $.fn.InitUploader = function () {
        var method = arguments[0];
        if (methods[method]) {
            method = methods[method];
            arguments = Array.prototype.slice.call(arguments, 1);
        } else if (typeof method === "object" || !method) {
            method = methods.init;
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.InitUploader');
            return this;
        }
        return method.apply(this, arguments);
    }

    /**
     * @multiple：是否开启多附件上传，默认不开启
     * @uploadUrl：指定上传的地址
     * @fileTypes：指定允许上传的附件类型，默认的情况下只能是图片类型。如果uploadType = 1的话，也只能上传图片类型。
     * @btnText：按钮显示名称
     * @fileSize：单个文件上传大小，默认最大为1024KB
     * @fileNumLimit：文件上传个数，如果为开启多附件上传，fileNumLimit会重新设置为1
     * @uploadType：上传类型，如果为1的话，认为上传的都是图片，会创建缩略图，其他值都不会创建缩略图
     * @isUploadToServe:是否上传至服务器，默认不上传
     * @data
     * @thumbnailWidth：缩略图宽度，只有上传图片的时候有效
     * @thumbnailHeight：缩略图高度，只有上传图片的时候有效
     * @timeOut 超时时间(秒)
     * @IsChunk 是否分片上传
     */
    $.fn.InitUploader.defaultOptions = {
        multiple: false,
        uploadUrl: '../../Handler/FileUploadHandler.ashx',
        fileTypes: 'jpeg,jpg,png,bmp',
        btnText: '请选择图片',
        fileSize: 1024 * 1024 * 5,
        fileNumLimit: 1,
        uploadType: 1,
        isUploadToServe: true,
        data: undefined,
        thumbnailWidth: undefined,
        thumbnailHeight: undefined,
        timeOut: 0,
        IsChunk: false
    }
})(jQuery, window, document);