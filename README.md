# 🌟 Trình quản lý Addons Minecraft Bedrock
## 🔹 Chức năng nổi bật
Quản lý nhanh các **addons Minecraft Bedrock Server** với các tính năng cơ bản như:
- Xem thông tin addon
- Thêm hoặc xóa addon
- Điều chỉnh thứ tự addon
- Xem thông tin gói tài nguyên

<br/><br/>


## 🔹 Cài đặt

### 1️⃣ Môi trường
- Yêu cầu **PHP** đã được cài sẵn
- Terminal có quyền trong thư mục lưu trữ

### 2️⃣ Thiết lập
1. Kéo kho về bằng lệnh:
   git clone <link-repo>
2. Mở thư mục vừa kéo về, chỉnh sửa file `configs.json` theo ý muốn.
3. Mở Terminal tại thư mục dự án và khởi chạy server:
   `php -S 0.0.0.0:8080`
4. Truy cập vào trình duyệt tại `http://localhost:8080`
5. Đặt các addon vào:
   - `./behavior_packs`
   - `./resource_packs`
6. Xuất gói addon (nếu muốn):
   `php package_export.php`
- Kết quả xuất ra sẽ nằm trong `exports/`:
  - Thư mục `behavior_packs` và `resource_packs`
  - File `world_behavior_packs.json` & `world_resource_packs.json`
7. Lấy các thư mục và file này để tải lên thế giới của bạn:
   - Thường tại `/container/worlds/Bedrock Dedicated/`
   - Hoặc theo đường dẫn tùy chỉnh của bạn

> ⚠️ Lưu ý: Giao diện hoạt động tốt nhất trên **máy tính**. Trên thiết bị di động có thể gặp khó khăn.

<br/><br/>
## 🔹 Phiên bản hiện tại
**Index:** 1.0.0 (beta)



<br/><br/>

## 🔹 Tài nguyên
- Biểu tượng: [Flaticon Interface](https://www.flaticon.com/free-icon-font/), [Google Material Icon](https://fonts.google.com/icons)  
- Thông báo: [Notyf](https://www.jsdelivr.com/package/npm/notyf)  
- Giao diện: [TailwindCSS](https://tailwindcss.com)

