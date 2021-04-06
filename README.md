# think-editor
thinkphp 集成富文本编辑器服务端处理模块

#### 安装
composer require hahadu/think-editor

## wangEditor编辑器上传服务端配置:

### 引入文件:
```php
use Hahadu\ThinkEditor\Client\WangEditor;
$wE = new WangEditor();
```
### 上传文件：
在wangEditor配置
```javascript
const E = window.wangEditor
const editor = new E('#div1')

// 配置 server 接口地址
editor.config.uploadImgServer = '';
//设置上传表单名称 以files为例
editor.config.uploadFileName = "files" 

editor.create()

```
文件上传处理方法,返回json
```php
//服务端配置
$config = [
        'disk' => 'public', //filesystem 中的disk配置 默认public
     //   'putFilePath' => 'files', //disk/url目录的下级目录，不设置则根据文件类型自动选择
        'upValidate'=> [ //上传文件验证规则
            'fileSize:10240000', //默认上传限制10M php.ini调整上传大小
            //'fileExt:jpg,png,gif,mp4', //允许上传的文件格式
        ],

        'water' => [ //水印设置
            'add_water_type' => 1, //添加水印 0不添加水印，1添加文字水印，2添加图片水印
        ],
        'base64' => [ //base64文件上传设置
            'extName' => 'jpg', //缓存名字后缀
        ],

];

$fileFiled = 'files'; // 文件上传表单名称
return $wE->uploader($fileFiled);
```
### 转换base64方法上传文件的base64文件为本地文件
表单提交地址（非文件上传的server地址）主处理方法，返回转换后的文本内容
```php
//假设提交文本内容的表单名称为content
$content = request()->post('content');

echo $wE->base64Img($content);
```
## 百度UEditor编辑器上传服务端配置:

### 引入文件:
```php
use Hahadu\ThinkEditor\Client\UEditor;
$UE = new UEditor();
return $UE->ueditor();
```
然后模板文件：
```html
<script type="text/javascript" src="/static/plugins/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/plugins/ueditor/ueditor.all.js"></script>
<textarea id="container" name=content></textarea>
<script>var ue = UE.getEditor("container",{
initialFrameHeight:500,
allowDivTransToP: false,
serverUrl : "{:url('ueditor')}",//路由地址
});
</script>
<script>
</script>
```
## 配置：

在系统config配置文件夹中配置ueditor编辑器和水印文件
>config
>>editor.php //编辑器配置文件
>>water.php //水印配置文件

详情参考config目录下ueditor.php和water.php


#### 鸣谢
> [hahadu/image-factory](https://github.com/hahadu/image-factory) 提供的图像处理模块
>
> [hahadu/helper-function](https://github.com/hahadu/helper-function) 一些助手函数
>
>[hahadu/think-helper](https://github.com/hahadu/think-helper) 另外一些助手函数
> 
>[hahadu/wangEditor-editor-code](https://github.com/hahadu/wangEditor-editor-code) wangEditor源代码编辑插件
> 
>   交流qq群 [(点击链接加入群聊【thinkphp6开发交流群】：839695142]https://jq.qq.com/?_wv=1027&k=FxgUKLhJ)

### 其它项目
>
>[hahadu/im-admin-think](https://github.com/hahadu/im-admin-think) admin模块
> 
>[hahadu/im-blog-think](https://github.com/hahadu/im-blog-think) think-php博客/文章管理、发布模块
> 
