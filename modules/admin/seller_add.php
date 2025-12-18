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
$success = '';

// 添加商家逻辑（❌ 危险代码：直接拼接参数，可注入）
if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // 检查用户名是否已存在（❌ 危险代码：直接拼接参数，可注入）
    $check_sql = "SELECT * FROM user WHERE username = '$username'";
    $check_stmt = $pdo->query($check_sql);
    if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
        $error = '用户名已存在！';
    } else {
        // 插入新商家（❌ 危险代码：直接拼接参数，可注入）
        $insert_sql = "INSERT INTO user (username, password, role) VALUES ('$username', '".md5($password)."', 2)";
        $pdo->exec($insert_sql);
        
        $success = '商家添加成功！';
        // 清空表单数据
        $_POST = array();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>添加商家 - 管理员后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-left">管理员后台 - 添加商家</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '未知用户'; ?></span>
            <a href="seller_list.php">商家列表</a>
            <a href="../../modules/index/index.php">前台首页</a>
        </div>
    </nav>

    <div class="form-container">
        <h2>添加商家</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <form method="post" action="">
            <div class="form-item">
                <label>用户名：</label>
                <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-item">
                <label>密码：</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-item">
                <label>角色：</label>
                <select name="role" disabled>
                    <option value="2" selected>商家</option>
                </select>
            </div>
            <div class="form-btn">
                <button type="submit">添加商家</button>
                <a href="seller_list.php">返回列表</a>
            </div>
        </form>
    </div>
</body>
</html>