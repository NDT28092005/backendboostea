# GiftMaster Database - Cấu Trúc Hoàn Chỉnh

## Tổng Quan
Database đã được tối ưu hóa hoàn toàn cho dự án GiftMaster - dịch vụ gói quà cho sinh viên. Tất cả các bảng không cần thiết đã được loại bỏ và chỉ giữ lại những gì cần thiết.

## Cấu Trúc Database

### 1. **Users** (2 records)
- **Admin**: admin@giftmaster.com / admin123
- **Student**: student@example.com / student123
- **Các trường**: id, name, email, password, phone, role, is_active, google_id, student_id, university, address, date_of_birth, is_student_verified, total_spent, total_orders, loyalty_points, membership_level, last_order_at

### 2. **Gift Categories** (8 records)
- Quà Sinh Nhật, Valentine, Tốt Nghiệp, Noel, 20/11, Tết, 8/3, Halloween
- **Các trường**: id, name, code, description, icon_url, is_active, sort_order

### 3. **Gift Packages** (8 records)
- 8 gói quà mẫu với đầy đủ thông tin
- **Các trường**: id, name, slug, description, price, original_price, discount_percentage, image_url, banner_url, status, is_featured, is_student_discount, category_id, target_audience, delivery_time, warranty_period, rating, review_count, meta_title, meta_description, tags, stock_quantity, sold_count, weight, dimensions, is_digital, gallery

### 4. **Orders** (0 records)
- Quản lý đơn hàng
- **Các trường**: id, order_code, user_id, total_amount, discount_amount, final_amount, status, payment_status, delivery_status, payment_method, gift_message, coupon_id, coupon_code, coupon_discount, order_notes, delivered_at

### 5. **Order Items** (0 records)
- Chi tiết sản phẩm trong đơn hàng
- **Các trường**: id, order_id, gift_package_id, quantity, unit_price, total_price

### 6. **Deliveries** (0 records)
- Thông tin giao hàng
- **Các trường**: id, order_id, delivery_address, delivery_phone, delivery_notes, delivery_date, delivery_time, delivery_status

### 7. **Payments** (0 records)
- Thông tin thanh toán
- **Các trường**: id, order_id, payment_method, payment_status, amount, transaction_id, payment_data, payment_date

### 8. **Reviews** (0 records)
- Đánh giá sản phẩm
- **Các trường**: id, user_id, gift_package_id, rating, title, comment, is_verified, is_approved, helpful_count

### 9. **Coupons** (4 records)
- WELCOME10, STUDENT20, FREESHIP, BIRTHDAY50K
- **Các trường**: id, code, name, description, type, value, minimum_amount, maximum_discount, usage_limit, used_count, user_limit, starts_at, expires_at, is_active, applicable_categories

### 10. **User Coupons** (0 records)
- Coupon của user
- **Các trường**: id, user_id, coupon_id, used_at, discount_amount

### 11. **Wishlists** (0 records)
- Danh sách yêu thích
- **Các trường**: id, user_id, gift_package_id

### 12. **Notifications** (0 records)
- Hệ thống thông báo
- **Các trường**: id, user_id, type, title, message, data, is_read, read_at

### 13. **System Settings** (15 records)
- Cài đặt hệ thống
- **Các trường**: id, setting_key, setting_value, data_type, description, is_public

## Tài Khoản Mặc Định

### Admin
- **Email**: admin@giftmaster.com
- **Password**: admin123
- **Role**: admin

### Student
- **Email**: student@example.com
- **Password**: student123
- **Role**: user
- **Student ID**: SV2024001
- **University**: Đại học ABC

## Cài Đặt Hệ Thống

### Cài Đặt Cơ Bản
- **Site Name**: GiftMaster
- **Site Description**: Dịch vụ gói quà cho sinh viên
- **Student Discount**: 10%
- **Free Shipping Threshold**: 200,000 VND
- **Default Delivery Time**: 1-2 ngày
- **Warranty Period**: 7 ngày

### Cài Đặt Liên Hệ
- **Contact Email**: support@giftmaster.com
- **Contact Phone**: 0123456789
- **Facebook**: https://facebook.com/giftmaster
- **Zalo**: https://zalo.me/giftmaster

### Hệ Thống Thành Viên
- **Bronze**: 0 VND
- **Silver**: 1,000,000 VND
- **Gold**: 3,000,000 VND
- **Platinum**: 5,000,000 VND

### Coupon Mẫu
1. **WELCOME10**: Giảm 10% cho đơn hàng đầu tiên
2. **STUDENT20**: Giảm 20% cho sinh viên
3. **FREESHIP**: Miễn phí giao hàng
4. **BIRTHDAY50K**: Giảm 50k cho đơn hàng sinh nhật

## Tính Năng Đặc Biệt

### 1. Hệ Thống Thành Viên
- Tự động nâng cấp dựa trên tổng chi tiêu
- Ưu đãi khác nhau cho từng cấp độ

### 2. Hệ Thống Coupon
- **Percentage**: Giảm theo phần trăm
- **Fixed Amount**: Giảm số tiền cố định
- **Free Shipping**: Miễn phí ship

### 3. Hệ Thống Đánh Giá
- Rating từ 1-5 sao
- Bình luận chi tiết
- Xác thực đánh giá

### 4. Hệ Thống Thông Báo
- Thông báo đơn hàng
- Thông báo khuyến mãi
- Thông báo hệ thống

### 5. Hệ Thống Tích Điểm
- 1 điểm/1000 VND
- Đổi điểm lấy ưu đãi

## Migration Files

### Chỉ Còn Lại:
1. `2019_12_14_000001_create_personal_access_tokens_table.php` (Laravel default)
2. `2025_10_25_160000_create_giftmaster_database.php` (Migration chính)

### Đã Xóa:
- Tất cả migration cũ không cần thiết
- Các bảng liên quan đến rạp chiếu phim
- Các bảng không sử dụng

## Kết Luận

Database hiện tại đã được tối ưu hóa hoàn toàn cho dự án GiftMaster với:
- ✅ **13 bảng** được thiết kế chuyên biệt cho dịch vụ gói quà
- ✅ **2 tài khoản mẫu** (admin + student)
- ✅ **8 danh mục gói quà** với đầy đủ thông tin
- ✅ **8 gói quà mẫu** đa dạng
- ✅ **15 cài đặt hệ thống** cần thiết
- ✅ **4 coupon mẫu** cho marketing
- ✅ **Foreign key constraints** đầy đủ
- ✅ **Indexes** tối ưu cho performance

Database sẵn sàng cho việc phát triển và triển khai!
