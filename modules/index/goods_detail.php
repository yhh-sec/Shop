<?php
require_once('../../config/config.php');
require_once('../../common/common.php');

// 获取商品ID
$goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($goods_id <= 0) {
    header("Location: index.php");
    exit;
}

// 安全的查询方式（已注释）
// $sql = "SELECT g.*, u.username as seller_name FROM goods g LEFT JOIN user u ON g.seller_id = u.id WHERE g.id = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$goods_id]);
// $goods = $stmt->fetch(PDO::FETCH_ASSOC);

// 存在SQL注入漏洞的查询方式（用于教学演示）
$sql = "SELECT g.*, u.username as seller_name FROM goods g LEFT JOIN user u ON g.seller_id = u.id WHERE g.id = " . $goods_id;
$stmt = $pdo->query($sql);
$goods = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$goods) {
    header("Location: index.php");
    exit;
}

// 处理多张图片路径
$image_paths = [];
if (!empty($goods['goods_img'])) {
    $image_paths = explode(',', $goods['goods_img']);
    // 过滤掉空路径
    $image_paths = array_filter($image_paths);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($goods['goods_name']); ?> - 商品详情</title>
    <link rel="stylesheet" href="../../static/css/style.css">
    <style>
        /* 商品图片展示区域 */
        .product-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        
        /* 主图展示 */
        .main-image-container {
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .main-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 10px;
            background: #fff;
        }
        
        /* 缩略图 gallery */
        .thumbnail-gallery {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            width: 100%;
            max-width: 600px;
        }
        
        .thumbnail-item {
            width: 80px;
            height: 80px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .thumbnail-item:hover {
            border-color: #007bff;
            transform: translateY(-3px);
        }
        
        .thumbnail-item.active {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.5);
        }
        
        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* 商品信息区域 */
        .goods-meta {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        
        .price {
            font-size: 24px;
            color: #e74c3c;
            font-weight: bold;
        }
        
        .price span {
            font-size: 32px;
        }
        
        .actions button {
            padding: 12px 25px;
            font-size: 16px;
            margin-right: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .actions button:first-child {
            background: #e74c3c;
            color: white;
        }
        
        .actions button:last-child {
            background: #3498db;
            color: white;
        }
        
        .actions button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- 顶部导航 -->
    <nav>
        <div class="nav-left">游戏外挂商城</div>
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>欢迎：<?php echo $_SESSION['username']; ?></span>
                <a href="../../modules/user/login.php">退出</a>
            <?php else: ?>
                <a href="../../modules/user/login.php">登录</a>
                <a href="../../modules/user/register.php">注册</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                <a href="../../modules/seller/goods_list.php">商家后台</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                <a href="../../modules/admin/seller_list.php">管理员后台</a>
            <?php endif; ?>
            <a href="index.php">首页</a>
        </div>
    </nav>

    <!-- 商品详情区 -->
    <div class="goods-container">
        <div class="goods-detail">
            <h2><?php echo htmlspecialchars($goods['goods_name']); ?></h2>
            <div class="goods-info">
                <div class="product-image-section">
                    <?php if (!empty($image_paths)): ?>
                        <!-- 主图显示第一张图片 -->
                        <div class="main-image-container">
                            <img id="mainImage" class="main-image" src="../../<?php echo trim($image_paths[0]); ?>" alt="<?php echo htmlspecialchars($goods['goods_name']); ?>">
                        </div>
                        
                        <!-- 缩略图 gallery -->
                        <?php if (count($image_paths) > 1): ?>
                        <div class="thumbnail-gallery">
                            <?php foreach ($image_paths as $index => $img_path): ?>
                                <?php if (file_exists('../../'.trim($img_path))): ?>
                                    <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeMainImage(this, '<?php echo trim($img_path); ?>')">
                                        <img src="../../<?php echo trim($img_path); ?>" alt="<?php echo htmlspecialchars($goods['goods_name']); ?>">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="main-image-container">
                            <img class="main-image" src="../../static/img/default.png" alt="<?php echo htmlspecialchars($goods['goods_name']); ?>">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="goods-meta">
                    <p class="price">价格：<span>¥<?php echo $goods['goods_price']; ?></span></p>
                    <p>商家：<?php echo htmlspecialchars($goods['seller_name']); ?></p>
                    <p>上架时间：<?php echo $goods['create_time']; ?></p>
                    <div class="actions">
                        <button onclick="alert('购买功能需要集成支付接口')">立即购买</button>
                        <button onclick="alert('加入购物车功能待开发')">加入购物车</button>
                    </div>
                </div>
            </div>
            <div class="goods-description">
                <h3>商品简介</h3>
                <p><?php echo nl2br(htmlspecialchars($goods['goods_desc'])); ?></p>
            </div>
        </div>
    </div>
    
    <script>
        function changeMainImage(element, imagePath) {
            // 更改主图
            document.getElementById('mainImage').src = '../../' + imagePath;
            
            // 更新激活状态
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            thumbnails.forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        }
    </script>
</body>
</html>