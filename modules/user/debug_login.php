<?php
/*
 * 调试脚本：用于诊断登录和跳转问题
 */

// 引入 session 初始化配置（必须在 session_start 之前）
require_once('../../common/session_init.php');

// 确保session已启动
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../../config/config.php');
require_once('../../common/common.php');

// 显示当前会话状态
echo "<h2>会话调试信息</h2>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Name: " . session_name() . "</p>";

// 显示会话变量
echo "<h3>当前会话变量:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 显示BASE_URL
echo "<h3>配置信息:</h3>";
echo "<p>BASE_URL: " . BASE_URL . "</p>";

// 如果是POST请求，模拟登录过程
if ($_POST) {
    echo "<h2>登录处理过程</h2>";
    
    $username = $_POST['username'];
    $password = encryptPwd($_POST['password']);
    
    echo "<p>尝试登录用户: " . htmlspecialchars($username) . "</p>";
    echo "<p>密码哈希: " . $password . "</p>";
    
    // 查询用户
    try {
        global $pdo;
        $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p>查询结果:</p>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        if ($user) {
            // 存入session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            echo "<p>会话已设置</p>";
            echo "<p>用户角色: " . $user['role'] . "</p>";
            
            // 根据角色准备跳转URL
            $redirect_url = "";
            if ($user['role'] == 1) {
                $redirect_url = BASE_URL . "/modules/admin/seller_list.php";
                echo "<p>准备跳转到管理员页面: " . $redirect_url . "</p>";
            } elseif ($user['role'] == 2) {
                $redirect_url = BASE_URL . "/modules/seller/goods_list.php";
                echo "<p>准备跳转到商家页面: " . $redirect_url . "</p>";
            } else {
                $redirect_url = BASE_URL . "/modules/index/index.php";
                echo "<p>准备跳转到首页: " . $redirect_url . "</p>";
            }
            
            // 显示跳转链接（不自动跳转，方便调试）
            echo "<p><a href='" . $redirect_url . "'>点击这里手动跳转</a></p>";
        } else {
            echo "<p style='color:red;'>用户名或密码错误</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red;'>数据库查询错误: " . $e->getMessage() . "</p>";
    }
} else {
    // 显示登录表单
    echo "<h2>登录测试表单</h2>";
    echo "<form method='post'>";
    echo "<p>用户名: <input type='text' name='username' value='admin'></p>";
    echo "<p>密码: <input type='password' name='password' value='admin'></p>";
    echo "<p><input type='submit' value='登录'></p>";
    echo "</form>";
    
    echo "<h2>可用测试账户</h2>";
    echo "<ul>";
    echo "<li>管理员: admin / admin</li>";
    echo "<li>商家: seller / seller</li>";
    echo "</ul>";
}
?>