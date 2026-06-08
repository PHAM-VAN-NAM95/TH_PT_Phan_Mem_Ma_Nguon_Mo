SWEETCAKE SHOP - Website bán bánh ngọt PHP MVC
================================================

Chức năng đã làm theo Bài 2, 3, 4, 5 COS340:
- Bài 2: Kết nối MySQL, CRUD sản phẩm, CRUD danh mục, upload/hiển thị hình ảnh sản phẩm.
- Bài 3: Giỏ hàng, tăng/giảm/cập nhật số lượng, kiểm tra số lượng nguyên > 0, đặt hàng, thanh toán COD, danh sách đơn hàng, chi tiết đơn hàng.
- Bài 4: Đăng ký, đăng nhập, đăng xuất, phân quyền admin/user.
- Bài 5: RESTful API cho sản phẩm + trang front-end API dùng jQuery.
- Yêu cầu thêm trong ảnh: giỏ hàng có ô nhập số lượng, báo lỗi khi số lượng không hợp lệ, tính lại tổng tiền, thêm ghi chú và phone2 khi đặt hàng, hiển thị danh sách đơn hàng gồm mã đơn, tên khách, số điện thoại, địa chỉ, tổng tiền, ngày đặt.

Tài khoản mẫu:
- Admin: admin / admin123
- User: user / user123

Cách chạy bằng Laragon:
1. Giải nén thư mục banhngot vào: C:\laragon\www\banhngot
2. Mở Laragon, bấm Start All để chạy Apache và MySQL.
3. Truy cập: http://localhost/banhngot/setup_database.php
   File này sẽ tự tạo database sweetcake_shop, bảng dữ liệu và dữ liệu mẫu.
4. Truy cập trang web: http://localhost/banhngot/

Đường dẫn quan trọng:
- Trang chủ: http://localhost/banhngot/
- Sản phẩm: http://localhost/banhngot/Product
- Giỏ hàng: http://localhost/banhngot/Cart
- Đăng nhập: http://localhost/banhngot/Auth/login
- Quản lý sản phẩm admin: http://localhost/banhngot/Product/manage
- Quản lý danh mục admin: http://localhost/banhngot/Category
- Danh sách đơn hàng: http://localhost/banhngot/Order/list
- Front-end API jQuery: http://localhost/banhngot/ApiClient

RESTful API:
- GET    /banhngot/api/products       : lấy danh sách sản phẩm
- GET    /banhngot/api/products/{id}  : lấy 1 sản phẩm
- POST   /banhngot/api/products       : thêm sản phẩm JSON
- PUT    /banhngot/api/products/{id}  : sửa sản phẩm JSON
- DELETE /banhngot/api/products/{id}  : xóa sản phẩm

Ví dụ JSON thêm/sửa sản phẩm:
{
  "name": "Bánh kem dâu tây",
  "description": "Bánh kem mềm mịn, vị dâu tươi.",
  "price": 250000,
  "image": "public/images/products/strawberry-cake.svg",
  "category_id": 1
}

Lưu ý:
- Nếu đổi tên thư mục banhngot, website vẫn tự nhận BASE_URL theo thư mục đang chạy.
- Cấu hình database nằm ở app/config/database.php.
