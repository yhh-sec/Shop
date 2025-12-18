<?php
/*
 * @Author: hzxOnlineOk
 * @Date: 2025-12-15 13:03:58
 * @LastEditors: 
 * @LastEditTime: 2025-12-15 13:04:09
 * @Description: 请填写简介
 */

// 先引入会话配置
require_once('../../common/session_init.php');

// 再启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/config.php');
require_once('../../common/common.php');
// 权限校验：仅最高管理员可访问
checkLogin(1);

// 查询所有商家（role=2）（❌ 危险代码：虽然没有直接参数，但在某些情况下可能被注入）
$sql = "SELECT * FROM user WHERE role = 2";
$stmt = $pdo->query($sql);
$seller_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 处理消息显示
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>商家管理 - 管理员后台</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-left">管理员后台 - 商家管理</div>
        <div class="nav-right">
            <span>欢迎您：<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '未知用户'; ?></span>
            <a href="seller_add.php">添加商家</a>
            <a href="../../modules/index/index.php">前台首页</a>
        </div>
    </nav>

    <div class="table-container">
        <h2>商家列表</h2>
        <?php if ($msg) echo "<p class='success'>$msg</p>"; ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>角色</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <?php if (empty($seller_list)): ?>
                <tr>
                    <td colspan="5">暂无商家</td>
                </tr>
            <?php else: ?>
                <?php foreach ($seller_list as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['username']); ?></td>
                        <td><?php echo $item['role'] == 2 ? '商家' : '未知'; ?></td>
                        <td><?php echo $item['create_time']; ?></td>
                        <td>
                            <a href="seller_edit.php?id=<?php echo $item['id']; ?>">编辑</a>
                            <a href="seller_del.php?id=<?php echo $item['id']; ?>" onclick="return confirm('确定要删除吗？')">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>