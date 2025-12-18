<?php
// 引入 session 初始化配置（必须在 session_start 之前）
require_once('../../common/session_init.php');

require_once('../../config/config.php');
require_once('../../common/common.php');
require_once('../../common/auth.php');

// 权限校验：仅商家可访问
if (!isLoggedIn() || !checkUserRole(2)) {
    redirectToLogin();
}

// 获取当前用户信息
$current_user = getCurrentUser();
$seller_id = $current_user['id'];

// 查询当前商家的商品（❌ 危险代码：直接拼接参数，可注入）
$sql = "SELECT * FROM goods WHERE seller_id = $seller_id ORDER BY id DESC";
$stmt = $pdo->query($sql);
$goods_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>商品管理 - 商家后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-left">商家后台 - 商品管理</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo htmlspecialchars($current_user['username']); ?></span>
            <a href="goods_add.php">添加商品</a>
            <a href="../../modules/index/index.php">前台首页</a>
            <a href="../../modules/user/login.php?logout=1">退出</a>
        </div>
    </nav>

    <div class="table-container">
        <h2>商品列表</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>商品名称</th>
                <th>价格</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <?php if (empty($goods_list)): ?>
                <tr>
                    <td colspan="5">暂无商品</td>
                </tr>
            <?php else: ?>
                <?php foreach ($goods_list as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['goods_name']); ?></td>
                        <td>¥<?php echo $item['goods_price']; ?></td>
                        <td><?php echo $item['create_time']; ?></td>
                        <td>
                            <a href="goods_edit.php?id=<?php echo $item['id']; ?>">编辑</a>
                            <a href="goods_del.php?id=<?php echo $item['id']; ?>" onclick="return confirm('确定要删除吗？')">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>