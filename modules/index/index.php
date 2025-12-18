<?php
require_once('../../config/config.php');
require_once('../../common/common.php');
require_once('../../common/auth.php');

// 处理退出请求
if (isset($_GET['logout'])) {
    logout();
    header("Location: index.php");
    exit;
}

// 检查登录状态
$is_logged_in = isLoggedIn();
$current_user = null;
if ($is_logged_in) {
    $current_user = getCurrentUser();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>商品展示 - 游戏外挂商城</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <!-- 顶部导航（登录/注册/后台入口） -->
    <nav>
        <div class="nav-left">游戏外挂商城</div>
        <div class="nav-right">
            <?php if ($is_logged_in): ?>
                <span>欢迎：<?php echo htmlspecialchars($current_user['username']); ?></span>
                <?php if ($current_user['role'] == 2): ?>
                    <a href="../../modules/seller/goods_list.php">商家后台</a>
                <?php elseif ($current_user['role'] == 1): ?>
                    <a href="../../modules/admin/seller_list.php">管理员后台</a>
                <?php endif; ?>
                <a href="?logout=1">退出</a>
            <?php else: ?>
                <a href="../../modules/user/login.php">登录</a>
                <a href="../../modules/user/register.php">注册</a>
            <?php endif; ?>
            <a href="index.php">首页</a>
        </div>
    </nav>

    <!-- 商品展示区 -->
    <div class="goods-container">
        <h2>全部商品</h2>
        <div class="goods-list">
            <?php
            // 查询所有商品（关联商家ID，展示商品信息）
            $sql = "SELECT g.*, u.username as seller_name FROM goods g LEFT JOIN user u ON g.seller_id = u.id ORDER BY g.id DESC";
            $stmt = $pdo->query($sql);
            $goods = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($goods)) {
                echo "<p>暂无商品上架~</p>";
            } else {
                foreach ($goods as $item) {
                    echo '<div class="goods-item">';
                    echo '<img src="' . (!empty($item['goods_img']) && file_exists('../../'.$item['goods_img']) ? '../../'.$item['goods_img'] : '../../static/img/default.png') . '" alt="' . htmlspecialchars($item['goods_name']) . '">';
                    echo '<h3><a href="goods_detail.php?id='.$item['id'].'">' . htmlspecialchars($item['goods_name']) . '</a></h3>';
                    echo '<p class="price">¥' . $item['goods_price'] . '</p>';
                    echo '<p>商家：' . htmlspecialchars($item['seller_name']) . '</p>';
                    echo '<p>' . nl2br(htmlspecialchars(mb_substr($item['goods_desc'], 0, 50))) . '...</p>';
                    echo '<a href="goods_detail.php?id='.$item['id'].'" class="btn">查看详情</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>