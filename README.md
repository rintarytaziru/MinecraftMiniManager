# 🌟 Trình Quản Lý Addons Minecraft Bedrock

## 🔹 Tính Năng Nổi Bật

Quản lý hiệu quả các **addons cho Minecraft Bedrock Server** với các chức năng chính:
- Xem thông tin chi tiết addon
- Thêm hoặc xóa addon dễ dàng
- Sắp xếp thứ tự addon linh hoạt
- Xem thông tin gói tài nguyên

<br/>

## 🔹 Hướng Dẫn Cài Đặt

### 1️⃣ Yêu Cầu Môi Trường
- Đã cài đặt **PHP**
- Có quyền truy cập Terminal trong thư mục dự án

### 2️⃣ Thiết Lập & Sử Dụng

1. Tải mã nguồn về:
   ```bash
   git clone <link-repo>
   ```
2. Truy cập thư mục dự án, chỉnh sửa file `configs.json` theo nhu cầu.
3. Khởi động server từ thư mục dự án:
   ```bash
   php -S 0.0.0.0:8080 -t ./src/
   ```
4. Mở trình duyệt và truy cập `http://localhost:8080`
5. Thêm các addon vào:
   - `./behavior_packs`
   - `./resource_packs`
6. Để xuất gói addon, sử dụng:
   ```bash
   php ./src/package_export.php
   ```
   - Kết quả sẽ nằm trong thư mục `exports/` gồm:
     - `behavior_packs/` và `resource_packs/`
     - `world_behavior_packs.json` & `world_resource_packs.json`
7. Sử dụng các thư mục và file này để tải lên thế giới Minecraft của bạn:
   - Thông thường tại `/container/worlds/Bedrock Dedicated/`
   - Hoặc theo đường dẫn bạn tùy chỉnh

> ⚠️ **Lưu ý:** Giao diện tối ưu cho máy tính. Trên thiết bị di động có thể gặp hạn chế.

<br/>

## 🔹 Phiên Bản

**Index:** 1.0.0 (beta)

<br/>

## 🔹 Tài Nguyên Sử Dụng

- Biểu tượng: [Flaticon Interface](https://www.flaticon.com/free-icon-font/), [Google Material Icon](https://fonts.google.com/icons)
- Thông báo: [Notyf](https://www.jsdelivr.com/package/npm/notyf)
- Giao diện: [TailwindCSS](https://tailwindcss.com)
