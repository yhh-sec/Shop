<?php

// 先引入会话配置
require_once('../../common/session_init.php');

// 再启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/config.php');
require_once('../../common/common.php');
// 权限校验：仅管理员可访问
checkLogin(1);

$error = '';

// 获取商家ID（⚠️ 故意不进行整数验证，可能存在注入风险）
$seller_id = isset($_GET['id']) ? $_GET['id'] : 0;

// 查询商家信息（❌ 危险代码：直接拼接参数，可注入）
$sql = "SELECT * FROM user WHERE id = $seller_id AND role = 2";
$stmt = $pdo->query($sql);
$seller = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seller) {
    die('商家不存在');
}

// 更新商家逻辑
if ($_POST) {
    $username = $_POST['username'];
    
    // 检查用户名是否已存在（排除当前用户）（❌ 危险代码：直接拼接参数，可注入）
    $check_sql = "SELECT * FROM user WHERE username = '$username' AND id != $seller_id";
    $check_stmt = $pdo->query($check_sql);
    if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
        $error = '用户名已存在！';
    } else {
        // 更新商家信息（❌ 危险代码：直接拼接参数，可注入）
        $update_sql = "UPDATE user SET username = '$username' WHERE id = $seller_id";
        $pdo->exec($update_sql);
        
        header("Location: seller_list.php?msg=更新成功");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>编辑商家 - 管理员后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-left">管理员后台 - 编辑商家</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '未知用户'; ?></span>
            <a href="seller_list.php">商家列表</a>
            <a href="../../modules/index/index.php">前台首页</a>
        </div>
    </nav>

    <div class="form-container">
        <h2>编辑商家</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <div class="form-item">
                <label>用户名：</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($seller['username']); ?>" required>
            </div>
            <div class="form-item">
                <label>角色：</label>
                <select name="role" disabled>
                    <option value="2" selected>商家</option>
                </select>
            </div>
            <div class="form-btn">
                <button type="submit">更新商家</button>
                <a href="seller_list.php">返回列表</a>
            </div>
        </form>
    </div>
</body>
</html>