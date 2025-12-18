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

// 获取商品ID（⚠️ 故意不进行整数验证，可能存在注入风险）
$goods_id = isset($_GET['id']) ? $_GET['id'] : 0;

// 验证商品是否属于当前商家（❌ 危险代码：直接拼接参数，可注入）
$check_sql = "SELECT * FROM goods WHERE id = $goods_id AND seller_id = $seller_id";
$check_stmt = $pdo->query($check_sql);
$goods = $check_stmt->fetch(PDO::FETCH_ASSOC);

if (!$goods) {
    die('商品不存在或无权访问');
}

// 更新商品逻辑
if ($_POST) {
    $goods_name = $_POST['goods_name'];
    $goods_price = $_POST['goods_price'];
    $goods_desc = $_POST['goods_desc'];
    $goods_img = $_POST['goods_img'];

    // 更新商品数据（❌ 危险代码：直接拼接参数，可注入）
    $update_sql = "UPDATE goods SET goods_name = '$goods_name', goods_price = $goods_price, goods_desc = '$goods_desc', goods_img = '$goods_img' WHERE id = $goods_id AND seller_id = $seller_id";
    $pdo->exec($update_sql);

    header("Location: goods_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>编辑商品 - 商家后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <nav>
        <div class="nav-left">商家后台 - 编辑商品</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo $_SESSION['username']; ?></span>
            <a href="goods_list.php">商品列表</a>
            <a href="../../modules/index/index.php">前台首页</a>
        </div>
    </nav>

    <div class="form-container">
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <div class="form-item">
                <label>商品名称：</label>
                <input type="text" name="goods_name" value="<?php echo htmlspecialchars($goods['goods_name']); ?>" required>
            </div>
            <div class="form-item">
                <label>商品价格：</label>
                <input type="number" step="0.01" name="goods_price" value="<?php echo htmlspecialchars($goods['goods_price']); ?>" required>
            </div>
            <div class="form-item">
                <label>商品简介：</label>
                <textarea name="goods_desc" rows="5"><?php echo htmlspecialchars($goods['goods_desc']); ?></textarea>
            </div>
            <div class="form-item">
                <label>商品图片：</label>
                <input type="file" id="goods_img" accept="image/*">
                <input type="hidden" name="goods_img" id="img_path" value="<?php echo htmlspecialchars($goods['goods_img']); ?>">
                <div id="img_preview">
                    <?php if ($goods['goods_img']): ?>
                        <img src="/<?php echo $goods['goods_img']; ?>" width="200">
                    <?php endif; ?>
                </div>
                <p id="upload_msg"></p>
            </div>
            <div class="form-btn">
                <button type="submit">更新商品</button>
                <a href="goods_list.php">返回列表</a>
            </div>
        </form>
    </div>

    <!-- 文件上传JS（调用upload.php接口） -->
    <script>
        $('#goods_img').change(function () {
            let formData = new FormData();
            formData.append('goods_img', this.files[0]);

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    res = JSON.parse(res);
                    $('#upload_msg').text(res.msg);
                    if (res.code == 200) {
                        $('#img_path').val(res.data);
                        $('#img_preview').html(`<img src="/${res.data}" width="200">`);
                    }
                }
            });
        });
    </script>
</body>
</html>