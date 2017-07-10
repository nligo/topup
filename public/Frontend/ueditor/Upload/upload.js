(function ($) {
    // 当domReady的时候开始初始化
    $(function () {
        var $wrap = $('#uploader'),

        // 图片容器
            $queue = $('<ul class="filelist"></ul>')
                .appendTo($wrap.find('.queueList')),

        // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find('.statusBar'),

        // 文件总体选择信息。
            $info = $statusBar.find('.info'),

        // 上传按钮
            $upload = $wrap.find('.uploadBtn'),

        // 没选择文件之前的内容。
            $placeHolder = $wrap.find('.placeholder'),

            $progress = $statusBar.find('.progress').hide(),

        // 添加的文件数量
            fileCount = 0,

        // 添加的文件总大小
            fileSize = 0,

        // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

        // 缩略图大小
            thumbnailWidth = 110 * ratio,
            thumbnailHeight = 110 * ratio,

        // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

        // 所有文件的进度信息，key为file id
            percentages = {},
        // 判断浏览器是否支持图片的base64
            isSupportBase64 = (function () {
                var data = new Image();
                var support = true;
                data.onload = data.onerror = function () {
                    if (this.width != 1 || this.height != 1) {
                        support = false;
                    }
                }
                data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                return support;
            })(),

        // 检测是否已经安装flash，检测flash的版本
            flashVersion = (function () {
                var version;

                try {
                    version = navigator.plugins['Shockwave Flash'];
                    version = version.description;
                } catch (ex) {
                    try {
                        version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                                .GetVariable('$version');
                    } catch (ex2) {
                        version = '0.0';
                    }
                }
                version = version.match(/\d+/g);
                return parseFloat(version[0] + '.' + version[1], 10);
            })(),

            supportTransition = (function () {
                var s = document.createElement('p').style,
                    r = 'transition' in s ||
                            'WebkitTransition' in s ||
                            'MozTransition' in s ||
                            'msTransition' in s ||
                            'OTransition' in s;
                s = null;
                return r;
            })(),

        // WebUploader实例
            uploader,
            GUID = WebUploader.Base.guid(); //当前页面是生成的GUID作为标示

        if (!WebUploader.Uploader.support('flash') && WebUploader.browser.ie) {

            // flash 安装了但是版本过低。
            if (flashVersion) {
                (function (container) {
                    window['expressinstallcallback'] = function (state) {
                        switch (state) {
                            case 'Download.Cancelled':
                                alert('您取消了更新！')
                                break;

                            case 'Download.Failed':
                                alert('安装失败')
                                break;

                            default:
                                alert('安装已成功，请刷新！');
                                break;
                        }
                        delete window['expressinstallcallback'];
                    };

                    var swf = 'expressInstall.swf';
                    // insert flash object
                    var html = '<object type="application/' +
                            'x-shockwave-flash" data="' + swf + '" ';

                    if (WebUploader.browser.ie) {
                        html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
                    }

                    html += 'width="100%" height="100%" style="outline:0">' +
                        '<param name="movie" value="' + swf + '" />' +
                        '<param name="wmode" value="transparent" />' +
                        '<param name="allowscriptaccess" value="always" />' +
                    '</object>';

                    container.html(html);

                })($wrap);

                // 压根就没有安转。
            } else {
                $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
            }

            return;
        } else if (!WebUploader.Uploader.support()) {
            alert('Web Uploader 不支持您的浏览器！');
            return;
        }

        // 实例化
        uploader = WebUploader.create({
            pick: {
                id: '#filePicker',
                label: '点击选择文件'
            },
            formData: {
                uid: 123
            },
            dnd: '#dndArea',
            paste: '#uploader',
            swf: 'Uploader.swf',
            chunked: true, //分片处理大文件
            chunkSize: 2 * 1024 * 1024,
            server: '../../Handler/ProjectAddHandler.ashx',
            disableGlobalDnd: true,
            threads: 1, //上传并发数
            //由于Http的无状态特征，在往服务器发送数据过程传递一个进入当前页面是生成的GUID作为标示
            formData: { guid: GUID },
            fileNumLimit: 300,
            compress: false, //图片在上传前不进行压缩
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
        });

        // 拖拽时不接受 js, txt 文件。
        uploader.on('dndAccept', function (items) {
            var denied = false,
                len = items.length,
                i = 0,
            // 修改js类型
                unAllowed = 'text/plain;application/javascript ';

            for (; i < len; i++) {
                // 如果在列表里面
                if (~unAllowed.indexOf(items[i].type)) {
                    denied = true;
                    break;
                }
            }

            return !denied;
        });

        uploader.on('dialogOpen', function () {
            console.log('here');
        });

        // uploader.on('filesQueued', function() {
        //     uploader.sort(function( a, b ) {
        //         if ( a.name < b.name )
        //           return -1;
        //         if ( a.name > b.name )
        //           return 1;
        //         return 0;
        //     });
        // });

        // 添加“添加文件”的按钮，
        uploader.addButton({
            id: '#filePicker2',
            label: '继续添加'
        });

        uploader.on('ready', function () {
            window.uploader = uploader;
        });

        // 当有文件添加进来时执行，负责view的创建
        function addFile(file) {
            var $li = $('<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>' +
                    '<p class="progress"><span></span></p>' +
                    '</li>'),

                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span>' +
                    '<span class="rotateRight">向右旋转</span>' +
                    '<span class="rotateLeft">向左旋转</span></div>').appendTo($li),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find('p.imgWrap'),
                $info = $('<p class="error"></p>'),

                showError = function (code) {
                    switch (code) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;

                        case 'interrupt':
                            text = '上传暂停';
                            break;

                        default:
                            text = '上传失败，请重试';
                            break;
                    }

                    $info.text(text).appendTo($li);
                };

            if (file.getStatus() === 'invalid') {
                showError(file.statusText);
            } else {
                // @todo lazyload
                $wrap.text('预览中');
                uploader.makeThumb(file, function (error, src) {
                    var img;

                    if (error) {
                        $wrap.text('不能预览');
                        return;
                    }

                    if (isSupportBase64) {
                        img = $('<img src="' + src + '">');
                        $wrap.empty().append(img);
                    } else {
                        $.ajax('preview.ashx', {
                            method: 'POST',
                            data: src,
                            dataType: 'json'
                        }).done(function (response) {
                            if (response.result) {
                                img = $('<img src="' + response.result + '">');
                                $wrap.empty().append(img);
                            } else {
                                $wrap.text("预览出错");
                            }
                        });
                    }
                }, thumbnailWidth, thumbnailHeight);

                percentages[file.id] = [file.size, 0];
                file.rotation = 0;
            }

            file.on('statuschange', function (cur, prev) {
                if (prev === 'progress') {
                    $prgress.hide().width(0);
                } else if (prev === 'queued') {
                    $li.off('mouseenter mouseleave');
                    $btns.remove();
                }

                // 成功
                if (cur === 'error' || cur === 'invalid') {
                    console.log(file.statusText);
                    showError(file.statusText);
                    percentages[file.id][1] = 1;
                } else if (cur === 'interrupt') {
                    showError('interrupt');
                } else if (cur === 'queued') {
                    $info.remove();
                    $prgress.css('display', 'block');
                    percentages[file.id][1] = 0;
                } else if (cur === 'progress') {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if (cur === 'complete') {
                    $prgress.hide().width(0);
                    $li.append('<span class="success"></span>');
                }

                $li.removeClass('state-' + prev).addClass('state-' + cur);
            });

            $li.on('mouseenter', function () {
                $btns.stop().animate({ height: 30 });
            });

            $li.on('mouseleave', function () {
                $btns.stop().animate({ height: 0 });
            });

            $btns.on('click', 'span', function () {
                var index = $(this).index(),
                    deg;

                switch (index) {
                    case 0:
                        uploader.removeFile(file);
                        return;

                    case 1:
                        file.rotation += 90;
                        break;

                    case 2:
                        file.rotation -= 90;
                        break;
                }

                if (supportTransition) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css('filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation=' + (~ ~((file.rotation / 90) % 4 + 4) % 4) + ')');
                    // use jquery animate to rotation
                    // $({
                    //     rotation: rotation
                    // }).animate({
                    //     rotation: file.rotation
                    // }, {
                    //     easing: 'linear',
                    //     step: function( now ) {
                    //         now = now * Math.PI / 180;

                    //         var cos = Math.cos( now ),
                    //             sin = Math.sin( now );

                    //         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                    //     }
                    // });
                }


            });

            $li.appendTo($queue);
        }

        // 负责view的销毁
        function removeFile(file) {
            var $li = $('#' + file.id);

            delete percentages[file.id];
            updateTotalProgress();
            $li.off().find('.file-panel').off().end().remove();
        }

        function updateTotalProgress() {
            var loaded = 0,
                total = 0,
                spans = $progress.children(),
                percent;

            $.each(percentages, function (k, v) {
                total += v[0];
                loaded += v[0] * v[1];
            });

            percent = total ? loaded / total : 0;


            spans.eq(0).text(Math.round(percent * 100) + '%');
            spans.eq(1).css('width', Math.round(percent * 100) + '%');
            updateStatus();
        }

        function updateStatus() {
            var text = '', stats;

            if (state === 'ready') {
                text = '选中' + fileCount + '张图片，共' +
                        WebUploader.formatSize(fileSize) + '。';
            } else if (state === 'confirm') {
                stats = uploader.getStats();
                if (stats.uploadFailNum) {
                    text = '已成功上传' + stats.successNum + '个文件，' +
                        stats.uploadFailNum + '个文件上传失败，<a class="retry" href="#">重新上传</a>失败文件或<a class="ignore" href="#">忽略</a>'
                }

            } else {
                stats = uploader.getStats();
                text = '共' + fileCount + '个（' +
                        WebUploader.formatSize(fileSize) +
                        '），已上传' + stats.successNum + '个';

                if (stats.uploadFailNum) {
                    text += '，失败' + stats.uploadFailNum + '个';
                }
            }

            $info.html(text);
        }

        function setState(val) {
            var file, stats;

            if (val === state) {
                return;
            }

            $upload.removeClass('state-' + state);
            $upload.addClass('state-' + val);
            state = val;

            switch (state) {
                case 'pedding':
                    $placeHolder.removeClass('element-invisible');
                    $queue.hide();
                    $statusBar.addClass('element-invisible');
                    uploader.refresh();
                    break;

                case 'ready':
                    $placeHolder.addClass('element-invisible');
                    $('#filePicker2').removeClass('element-invisible');
                    $queue.show();
                    $statusBar.removeClass('element-invisible');
                    uploader.refresh();
                    break;

                case 'uploading':
                    $('#filePicker2').addClass('element-invisible');
                    $progress.show();
                    $upload.text('暂停上传');
                    break;

                case 'paused':
                    $progress.show();
                    $upload.text('继续上传');
                    break;

                case 'confirm':
                    $progress.hide();
                    $('#filePicker2').removeClass('element-invisible');
                    $upload.text('开始上传');

                    stats = uploader.getStats();
                    if (stats.successNum && !stats.uploadFailNum) {
                        setState('finish');
                        return;
                    }
                    break;
                case 'finish':
                    stats = uploader.getStats();
                    if (stats.successNum) {
                        alert('上传完成');
                    } else {
                        // 没有成功的图片，重设
                        state = 'done';
                        location.reload();
                    }
                    break;
            }

            updateStatus();
        }

        uploader.onUploadProgress = function (file, percentage) {
            var $li = $('#' + file.id),
                $percent = $li.find('.progress span');

            $percent.css('width', percentage * 100 + '%');
            percentages[file.id][1] = percentage;
            updateTotalProgress();
        };

        uploader.onFileQueued = function (file) {
            fileCount++;
            fileSize += file.size;

            if (fileCount === 1) {
                $placeHolder.addClass('element-invisible');
                $statusBar.show();
            }

            addFile(file);
            setState('ready');
            updateTotalProgress();
        };

        uploader.onFileDequeued = function (file) {
            fileCount--;
            fileSize -= file.size;

            if (!fileCount) {
                setState('pedding');
            }

            removeFile(file);
            updateTotalProgress();

        };

        //all算是一个总监听器
        uploader.on('all', function (type, arg1, arg2) {
            console.log("all监听：", type, arg1, arg2);
            var stats;
            switch (type) {
                case 'uploadFinished':
                    setState('confirm');
                    break;

                case 'startUpload':
                    setState('uploading');
                    break;

                case 'stopUpload':
                    setState('paused');
                    break;

            }
        });

        // 文件上传成功,合并文件。
        uploader.on('uploadSuccess', function (file, response) {
            if (response.chunked) {
                $.post("MergeFiles.ashx", { guid: GUID, fileExt: response.f_ext },
                function (data) {
                    data = $.parseJSON(data);
                    if (data.hasError) {
                        alert('文件合并失败！');
                    } else {
                        alert(decodeURIComponent(data.savePath));
                    }
                });
            }
        });

        uploader.onError = function (code) {
            alert('Eroor: ' + code);
        };

        $upload.on('click', function () {
            if ($(this).hasClass('disabled')) {
                return false;
            }

            if (state === 'ready') {
                uploader.upload();
            } else if (state === 'paused') {
                uploader.upload();
            } else if (state === 'uploading') {
                uploader.stop();
            }
        });

        $info.on('click', '.retry', function () {
            uploader.retry();
        });

        $info.on('click', '.ignore', function () {
            alert('todo');
        });

        $upload.addClass('state-' + state);
        updateTotalProgress();
    });

})(jQuery);


(function (factory) {
    if (!window.jQuery) {
        alert('jQuery is required.')
    }

    jQuery(function () {
        factory.call(null, jQuery);
    });
})(function ($) {
    // -----------------------------------------------------
    // ------------ START ----------------------------------
    // -----------------------------------------------------

    // ---------------------------------
    // ---------  Uploader -------------
    // ---------------------------------
    var Uploader = (function () {

        // -------setting-------
        // 如果使用原始大小，超大的图片可能会出现 Croper UI 卡顿，所以这里建议先缩小后再crop.
        var FRAME_WIDTH = 1600;


        var _ = WebUploader;
        var Uploader = _.Uploader;
        var uploaderContainer = $('.uploader-container');
        var uploader, file;

        if (!Uploader.support()) {
            alert('Web Uploader 不支持您的浏览器！');
            throw new Error('WebUploader does not support the browser you are using.');
        }

        // hook,
        // 在文件开始上传前进行裁剪。
        Uploader.register({
            'before-send-file': 'cropImage'
        }, {

            cropImage: function (file) {

                var data = file._cropData,
                    image, deferred;

                file = this.request('get-file', file);
                deferred = _.Deferred();

                image = new _.Lib.Image();

                deferred.always(function () {
                    image.destroy();
                    image = null;
                });
                image.once('error', deferred.reject);
                image.once('load', function () {
                    image.crop(data.x, data.y, data.width, data.height, data.scale);
                });

                image.once('complete', function () {
                    var blob, size;

                    // 移动端 UC / qq 浏览器的无图模式下
                    // ctx.getImageData 处理大图的时候会报 Exception
                    // INDEX_SIZE_ERR: DOM Exception 1
                    try {
                        blob = image.getAsBlob();
                        size = file.size;
                        file.source = blob;
                        file.size = blob.size;

                        file.trigger('resize', blob.size, size);

                        deferred.resolve();
                    } catch (e) {
                        console.log(e);
                        // 出错了直接继续，让其上传原始图片
                        deferred.resolve();
                    }
                });

                file._info && image.info(file._info);
                file._meta && image.meta(file._meta);
                image.loadFromBlob(file.source);
                return deferred.promise();
            }
        });

        return {
            init: function (selectCb) {
                uploader = new Uploader({
                    pick: {
                        id: '#filePicker',
                        multiple: false
                    },

                    // 设置用什么方式去生成缩略图。
                    thumb: {
                        quality: 70,

                        // 不允许放大
                        allowMagnify: false,

                        // 是否采用裁剪模式。如果采用这样可以避免空白内容。
                        crop: false
                    },

                    // 禁掉分块传输，默认是开起的。
                    chunked: false,

                    // 禁掉上传前压缩功能，因为会手动裁剪。
                    compress: false,

                    // fileSingleSizeLimit: 2 * 1024 * 1024,

                    server: '../../server/fileupload.php',
                    swf: '../../dist/Uploader.swf',
                    fileNumLimit: 1,
                    onError: function () {
                        var args = [].slice.call(arguments, 0);
                        alert(args.join('\n'));
                    }
                });

                uploader.on('fileQueued', function (_file) {
                    file = _file;

                    uploader.makeThumb(file, function (error, src) {

                        if (error) {
                            alert('不能预览');
                            return;
                        }

                        selectCb(src);

                    }, FRAME_WIDTH, 1);   // 注意这里的 height 值是 1，被当成了 100% 使用。
                });
            },

            crop: function (data) {

                var scale = Croper.getImageSize().width / file._info.width;
                data.scale = scale;

                file._cropData = {
                    x: data.x1,
                    y: data.y1,
                    width: data.width,
                    height: data.height,
                    scale: data.scale
                };
            },

            upload: function () {
                uploader.upload();
            }
        }
    })();

    // ---------------------------------
    // ---------  Crpper ---------------
    // ---------------------------------
    var Croper = (function () {
        var container = $('.cropper-wraper');
        var $image = container.find('.img-container img');
        var btn = $('.upload-btn');
        var isBase64Supported, callback;

        $image.cropper({
            aspectRatio: 16 / 9,
            preview: ".img-preview",
            done: function (data) {
                // console.log(data);
            }
        });

        function srcWrap(src, cb) {

            // we need to check this at the first time.
            if (typeof isBase64Supported === 'undefined') {
                (function () {
                    var data = new Image();
                    var support = true;
                    data.onload = data.onerror = function () {
                        if (this.width != 1 || this.height != 1) {
                            support = false;
                        }
                    }
                    data.src = src;
                    isBase64Supported = support;
                })();
            }

            if (isBase64Supported) {
                cb(src);
            } else {
                // otherwise we need server support.
                // convert base64 to a file.
                $.ajax('../../server/preview.php', {
                    method: 'POST',
                    data: src,
                    dataType: 'json'
                }).done(function (response) {
                    if (response.result) {
                        cb(response.result);
                    } else {
                        alert("预览出错");
                    }
                });
            }
        }

        btn.on('click', function () {
            callback && callback($image.cropper("getData"));
            return false;
        });

        return {
            setSource: function (src) {

                // 处理 base64 不支持的情况。
                // 一般出现在 ie6-ie8
                srcWrap(src, function (src) {
                    $image.cropper("setImgSrc", src);
                });

                container.removeClass('webuploader-element-invisible');

                return this;
            },

            getImageSize: function () {
                var img = $image.get(0);
                return {
                    width: img.naturalWidth,
                    height: img.naturalHeight
                }
            },

            setCallback: function (cb) {
                callback = cb;
                return this;
            },

            disable: function () {
                $image.cropper("disable");
                return this;
            },

            enable: function () {
                $image.cropper("enable");
                return this;
            }
        }

    })();


    // ------------------------------
    // -----------logic--------------
    // ------------------------------
    var container = $('.uploader-container');

    Uploader.init(function (src) {

        Croper.setSource(src);

        // 隐藏选择按钮。
        container.addClass('webuploader-element-invisible');

        // 当用户选择上传的时候，开始上传。
        Croper.setCallback(function (data) {
            Uploader.crop(data);
            Uploader.upload();
        });
    });



    // -----------------------------------------------------
    // ------------ END ------------------------------------
    // -----------------------------------------------------
});
