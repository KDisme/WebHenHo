<?php
require_once '../../models/mSession.php';
require_once '../../models/mVIP.php';
require_once '../../models/mProfile.php';
require_once '../../models/mNotification.php';
require_once '../../models/mMessage.php';

Session::start();

// Kiểm tra đăng nhập
if (!Session::get('user_id')) {
    header('Location: ../dangnhap/login.php');
    exit;
}

$userId = Session::get('user_id');
$vipModel = new VIP();
$isVIP = $vipModel->isVIP($userId);
$currentPackage = $vipModel->getCurrentVIPPackage($userId);
$daysRemaining = $vipModel->getDaysRemaining($userId);

// Lấy profile để hiển thị avatar
$profileModel = new Profile();
$profile = $profileModel->getProfile($userId);
$avatarPath = !empty($profile['avt']) ? $profile['avt'] : 'public/img/default-avatar.jpg';

// Đếm số ghép đôi mới và tin nhắn chưa đọc
$notificationModel = new Notification();
$newMatchesCount = $notificationModel->getNewMatchesCount($userId);
$messageModel = new Message();
$unreadMessagesCount = $messageModel->getTotalUnreadCount($userId);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nâng cấp VIP - DuyenHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/goivip.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <div class="header-left">
                <a href="../trangchu/index.php" class="logo">
                    <img src="../../public/img/logo.jpg" alt="DuyenHub Logo">
                    <span class="logo-text">DuyenHub</span>
                </a>
                <nav class="header-menu">
                    <a href="../trangchu/index.php" class="menu-item active">
                        <i class="fas fa-home"></i>
                        <span>Trang chủ</span>
                    </a>
                    <a href="../nhantin/message.php" class="menu-item" style="position: relative;">
                        <i class="fas fa-comments"></i>
                        <span>Tin nhắn</span>
                        <?php if ($unreadMessagesCount > 0): ?>
                        <span class="notification-badge" id="messagesBadge" style="position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;"><?php echo $unreadMessagesCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Trợ giúp</span>
                    </a>
                </nav>
            </div>
            <div class="header-actions">
                <!-- Đã xóa nút đăng xuất để đồng bộ với yêu cầu -->
            </div>
        </div>
    </header>

    <!-- VIP Wrapper -->
    <div class="vip-wrapper">
        <!-- VIP Container -->
        <div class="vip-container" style="position:relative;">
            <!-- Back Button -->
            <button class="back-btn" onclick="window.location.href='../trangchu/index.php'">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <?php if ($isVIP): ?>
            <!-- VIP Status Section -->
            <div class="vip-status-card">
                <div class="vip-badge">
                    <i class="fas fa-crown"></i>
                    <span>Tài khoản VIP</span>
                </div>
                <h2 class="status-title">Bạn đang là thành viên VIP</h2>
                <div class="status-info">
                    <p class="days-remaining">Còn lại: <strong><?php echo $daysRemaining; ?> ngày</strong></p>
                    <p class="expiry-date">Hết hạn: <?php echo date('d/m/Y', strtotime($currentPackage['ngayHetHan'])); ?></p>
                </div>
            </div>
            <?php else: ?>
            <!-- Hero Section -->
            <div class="vip-hero">
                <div class="hero-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h1 class="hero-title">Nâng cấp VIP</h1>
                <p class="hero-subtitle">Mở khóa tất cả tính năng đặc biệt và trải nghiệm hẹn hò tốt nhất</p>
            </div>
            <?php endif; ?>
            
            <!-- Features Grid -->
            <div class="features-section">
                <h2 class="section-title">Đặc quyền thành viên VIP</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heart-pulse"></i>
                        </div>
                        <h3>Ghép đôi thông minh</h3>
                        <p>Thuật toán AI tìm kiếm người phù hợp nhất với bạn dựa trên sở thích và tính cách</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-infinity"></i>
                        </div>
                        <h3>Thích không giới hạn</h3>
                        <p>Không giới hạn số lượng like mỗi ngày, tự do khám phá nhiều người hơn</p>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Section -->
            <div class="pricing-section">
                <h2 class="section-title">Chọn gói phù hợp với bạn</h2>
                
                <div class="pricing-grid">
                    <!-- 1 Month Package -->
                    <div class="price-card">
                        <div class="price-header">
                            <div class="price-duration">1 Tháng</div>
                        </div>
                        <div class="price-body">
                            <div class="price-amount">
                                <span class="amount">99.000đ</span>
                                <span class="period">/tháng</span>
                            </div>
                            <a href="../goivip/thanhtoan.php?package=1" class="btn-select-package btn-select-package-one">
                                <i class="fas fa-crown"></i> Nâng cấp ngay
                            </a>
                        </div>
                    </div>
                    
                    <!-- 3 Months Package (Popular) -->
                    <div class="price-card popular">
                        <span class="popular-badge">
                            <i class="fas fa-fire"></i> Phổ biến nhất
                        </span>
                        <div class="price-header">
                            <div class="price-duration">3 Tháng</div>
                        </div>
                        <div class="price-body">
                            <div class="price-amount">
                                <span class="amount">249.000đ</span>
                                <span class="period">/3 tháng</span>
                            </div>
                            <div class="price-save">
                                <i class="fas fa-tag"></i> Tiết kiệm 16%
                            </div>
                            <a href="thanhtoan.php?months=3" class="btn-upgrade">
                                <i class="fas fa-crown"></i> Nâng cấp ngay
                            </a>
                        </div>
                    </div>
                    
                    <!-- 6 Months Package -->
                    <div class="price-card">
                        <div class="price-header">
                            <div class="price-duration">6 Tháng</div>
                        </div>
                        <div class="price-body">
                            <div class="price-amount">
                                <span class="amount">449.000đ</span>
                                <span class="period">/6 tháng</span>
                            </div>
                            <div class="price-save">
                                <i class="fas fa-tag"></i> Tiết kiệm 24%
                            </div>
                            <a href="thanhtoan.php?months=6" class="btn-upgrade">
                                <i class="fas fa-crown"></i> Nâng cấp ngay
                            </a>
                        </div>
                    </div>
                    
                    <!-- 12 Months Package -->
                    <div class="price-card best-value">
                        <span class="value-badge">
                            <i class="fas fa-star"></i> Giá trị nhất
                        </span>
                        <div class="price-header">
                            <div class="price-duration">12 Tháng</div>
                        </div>
                        <div class="price-body">
                            <div class="price-amount">
                                <span class="amount">799.000đ</span>
                                <span class="period">/năm</span>
                            </div>
                            <div class="price-save">
                                <i class="fas fa-tag"></i> Tiết kiệm 33%
                            </div>
                            <a href="thanhtoan.php?months=12" class="btn-upgrade">
                                <i class="fas fa-crown"></i> Nâng cấp ngay
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if ($isVIP): ?>
                <div class="renewal-notice">
                    <i class="fas fa-info-circle"></i>
                    <p>Gia hạn gói VIP sẽ cộng dồn thêm thời gian vào gói hiện tại của bạn</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-links">
                    <a href="#">Về chúng tôi</a>
                    <a href="#">Hỗ trợ</a>
                    <a href="#">Pháp lý</a>
                </div>
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=61583156011828" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; Kết Nối Yêu Thương. Mọi quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Script cập nhật trạng thái online -->
    <script>
        // Cập nhật trạng thái online mỗi 2 phút
        function updateOnlineStatus() {
            fetch('../../controller/cUpdateOnlineStatus.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Online status updated');
                }
            })
            .catch(error => {
                console.error('Error updating online status:', error);
            });
        }

        // Cập nhật ngay khi trang load
        updateOnlineStatus();

        // Cập nhật mỗi 2 phút (120000ms)
        setInterval(updateOnlineStatus, 120000);

        // Cập nhật khi user tương tác
        let activityTimeout;
        function resetActivityTimer() {
            clearTimeout(activityTimeout);
            activityTimeout = setTimeout(updateOnlineStatus, 5000);
        }

        // Lắng nghe các sự kiện tương tác
        ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetActivityTimer, true);
        });
    </script>

    <!-- Script check thông báo real-time -->
    <script>
        let lastNotificationCount = <?php echo ($newMatchesCount + $unreadMessagesCount); ?>;
        
        // Check và cập nhật số thông báo mới
        function checkNotifications() {
            fetch('../../controller/cCheckNotifications.php', {
                method: 'GET',
                cache: 'no-cache'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật badge tin nhắn
                    const messagesBadge = document.getElementById('messagesBadge');
                    if (data.unreadMessages > 0) {
                        if (messagesBadge) {
                            messagesBadge.textContent = data.unreadMessages;
                        } else {
                            // Tạo badge mới nếu chưa có
                            const messagesLink = document.querySelector('a[href="../nhantin/message.php"]');
                            if (messagesLink && !messagesLink.querySelector('.notification-badge')) {
                                const badge = document.createElement('span');
                                badge.id = 'messagesBadge';
                                badge.className = 'notification-badge';
                                badge.textContent = data.unreadMessages;
                                badge.style.cssText = 'position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;';
                                messagesLink.appendChild(badge);
                            }
                        }
                    } else if (messagesBadge) {
                        messagesBadge.remove();
                    }
                    
                    // Cập nhật badge ghép đôi
                    const matchesBadge = document.getElementById('matchesBadge');
                    if (data.newMatches > 0) {
                        if (matchesBadge) {
                            matchesBadge.textContent = data.newMatches;
                        } else {
                            // Tạo badge mới nếu chưa có
                        }
                    } else if (matchesBadge) {
                        matchesBadge.remove();
                    }
                        // Đã xóa chức năng tìm kiếm và badge
                    
                    // Hiển thị thông báo popup nếu có thay đổi
                    const currentTotal = data.unreadMessages + data.newMatches;
                    if (currentTotal > lastNotificationCount) {
                        showNewNotificationAlert(data);
                    }
                    lastNotificationCount = currentTotal;
                }
            })
            .catch(error => {
                console.error('Error checking notifications:', error);
            });
        }
        
        // Hiển thị thông báo popup khi có tin nhắn/match mới
        function showNewNotificationAlert(data) {
            let message = '';
            if (data.unreadMessages > 0 && data.newMatches > 0) {
                message = `💬 ${data.unreadMessages} tin nhắn mới và 💕 ${data.newMatches} ghép đôi mới!`;
            } else if (data.unreadMessages > 0) {
                message = `💬 Bạn có ${data.unreadMessages} tin nhắn mới!`;
            } else if (data.newMatches > 0) {
                message = `💕 Bạn có ${data.newMatches} ghép đôi mới!`;
            }
            
            if (message) {
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 18px 25px;
                    border-radius: 15px;
                    font-size: 15px;
                    font-weight: 600;
                    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                    z-index: 10000;
                    cursor: pointer;
                    animation: slideInRight 0.5s ease;
                    max-width: 350px;
                `;
                notification.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-bell" style="font-size: 24px;"></i>
                        <div>
                            <div style="font-size: 16px; margin-bottom: 4px;">${message}</div>
                            <div style="font-size: 12px; opacity: 0.9;">Click để xem ngay →</div>
                        </div>
                    </div>
                `;
                notification.onclick = function() {
                    window.location.href = '../nhantin/message.php';
                };
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 2000);
            }
        }

        // Check ngay khi trang load (sau 2 giây)
        setTimeout(checkNotifications, 2000);

        // Check mỗi 0.5 giây (500ms) - REAL-TIME TỨC THÌ!
        setInterval(checkNotifications, 500);

        // Check khi user quay lại tab
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                checkNotifications();
            }
        });

        // Check khi user focus vào window
        window.addEventListener('focus', checkNotifications);
    </script>

    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
