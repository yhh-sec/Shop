<?php
// 先引入会话配置
require_once('../../common/session_init.php');

// 再启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/config.php');
require_once('../../common/common.php');
// 权限校验：仅商家可访问
checkLogin(2);

$seller_id = $_SESSION['user_id'];
$error = '';

// 新增商品逻辑
if ($_POST) {
    $goods_name = $_POST['goods_name'];
    $goods_price = $_POST['goods_price'];
    $goods_desc = $_POST['goods_desc'];
    // 获取所有图片路径，用逗号分隔存储
    $goods_imgs = implode(',', $_POST['goods_imgs']);

    // 插入商品数据（❌ 危险代码：直接拼接参数，可注入）
    $sql = "INSERT INTO goods (goods_name, goods_price, goods_desc, goods_img, seller_id) 
            VALUES ('$goods_name', $goods_price, '$goods_desc', '$goods_imgs', $seller_id)";
    $pdo->exec($sql);

    header("Location: goods_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>添加商品 - 商家后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <nav>
        <div class="nav-left">商家后台 - 添加商品</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo $_SESSION['username']; ?></span>
            <a href="goods_list.php">商品列表</a>
            <a href="../../modules/index/index.php">前台首页</a>
        </div>
    </nav>

    <div class="form-container">
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-item">
                <label>商品名称：</label>
                <input type="text" name="goods_name" required>
            </div>
            <div class="form-item">
                <label>商品价格：</label>
                <input type="number" step="0.01" name="goods_price" required>
            </div>
            <div class="form-item">
                <label>商品简介：</label>
                <textarea name="goods_desc" rows="5"></textarea>
            </div>
            <div class="form-item">
                <label>商品图片：</label>
                <input type="file" id="goods_img" accept="image/*" multiple>
                <div id="img_preview"></div>
                <p id="upload_msg"></p>
                <!-- 用于存储所有图片路径的隐藏字段 -->
                <input type="hidden" name="goods_imgs[]" id="img_paths">
            </div>
            <div class="form-btn">
                <button type="submit">提交商品</button>
                <a href="goods_list.php">返回列表</a>
            </div>
        </form>
    </div>

    <!-- 文件上传JS（调用upload.php接口） -->
    <script>
        let uploadedImages = []; // 存储已上传的图片路径
        
        $('#goods_img').change(function () {
            const files = this.files;
            
            // 清空之前的上传消息
            $('#upload_msg').text('');
            
            // 遍历每个选中的文件
            for (let i = 0; i < files.length; i++) {
                let formData = new FormData();
                formData.append('goods_img', files[i]);

                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        res = JSON.parse(res);
                        if (res.code == 200) {
                            // 将新上传的图片路径添加到数组
                            uploadedImages.push(res.data);
                            
                            // 更新隐藏字段
                            $('#img_paths').val(uploadedImages.join(','));
                            
                            // 在预览区域添加缩略图
                            $('#img_preview').append(`
                                <div class="image-preview-item" style="display: inline-block; margin: 5px;">
                                    <img src="/${res.data}" width="100" style="border: 1px solid #ddd; padding: 2px;">
                                </div>
                            `);
                            
                            $('#upload_msg').append(`<p style="color: green;">${files[i].name} 上传成功！</p>`);
                        } else {
                            $('#upload_msg').append(`<p style="color: red;">${files[i].name} 上传失败：${res.msg}</p>`);
                        }
                    }
                });
            }
        });
    </script>
    
    <style>
        .image-preview-item {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
    </style>
</body>
</html>