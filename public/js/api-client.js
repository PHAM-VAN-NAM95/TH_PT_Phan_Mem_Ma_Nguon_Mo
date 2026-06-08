(function () {
    const API = window.SWEETCAKE_API_URL;
    const BASE = (window.SWEETCAKE_BASE_URL || '').replace(/\/$/, '');
    const $message = document.getElementById('apiMessage');
    const tbody = document.querySelector('#apiProductTable tbody');
    const fileInput = document.getElementById('apiImageFile');
    const preview = document.getElementById('apiImagePreview');

    function showMessage(message, isError) {
        $message.textContent = message || '';
        $message.classList.toggle('is-error', !!isError);
    }

    function formDataPayload() {
        const data = new FormData();
        data.append('name', document.getElementById('apiName').value.trim());
        data.append('description', document.getElementById('apiDescription').value.trim());
        data.append('price', document.getElementById('apiPrice').value);
        data.append('image', document.getElementById('apiImage').value.trim() || 'public/images/products/default.svg');
        data.append('category_id', document.getElementById('apiCategoryId').value || '');
        if (fileInput.files && fileInput.files[0]) {
            data.append('image', fileInput.files[0]);
        }
        return data;
    }

    function resetForm() {
        document.getElementById('apiProductId').value = '';
        document.getElementById('apiForm').reset();
        document.getElementById('apiImage').value = 'public/images/products/default.svg';
        preview.src = imageUrl('public/images/products/default.svg');
        showMessage('');
    }

    function renderRows(products) {
        tbody.innerHTML = '';
        products.forEach(function (product) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>#${product.id}</td>
                <td>
                    <div class="api-product-cell">
                        <img src="${imageUrl(product.image || 'public/images/products/default.svg')}" alt="${escapeHtml(product.name)}">
                        <div>
                            <strong>${escapeHtml(product.name)}</strong><br>
                            <small>${escapeHtml((product.description || '').slice(0, 70))}${(product.description || '').length > 70 ? '...' : ''}</small>
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(product.category_name || 'Chưa phân loại')}</td>
                <td>${formatMoney(product.price)}</td>
                <td class="actions">
                    <button class="btn btn-sm btn-outline" data-edit="${product.id}">Sửa</button>
                    <button class="btn btn-sm btn-danger" data-delete="${product.id}">Xóa</button>
                </td>`;
            tbody.appendChild(tr);
        });
    }

    function loadProducts() {
        if (window.jQuery) {
            jQuery.getJSON(API, function (response) {
                renderRows(response.data || []);
            });
            return;
        }
        fetch(API).then(res => res.json()).then(response => renderRows(response.data || []));
    }

    function saveProduct(event) {
        event.preventDefault();
        const id = document.getElementById('apiProductId').value;
        const endpoint = id ? API + '/' + id : API;
        const payload = formDataPayload();

        const done = function (response) {
            if (response.errors) {
                showMessage(response.errors.join(' | '), true);
                return;
            }
            showMessage(response.message || 'Đã lưu sản phẩm thành công.', false);
            resetForm();
            loadProducts();
        };

        if (window.jQuery) {
            jQuery.ajax({
                url: endpoint,
                method: 'POST',
                data: payload,
                processData: false,
                contentType: false,
                success: done,
                error: function (xhr) {
                    const res = xhr.responseJSON || {};
                    showMessage((res.errors || [res.message || 'Có lỗi xảy ra']).join(' | '), true);
                }
            });
            return;
        }
        fetch(endpoint, {method: 'POST', body: payload}).then(res => res.json()).then(done);
    }

    function fillEdit(id) {
        fetch(API + '/' + id).then(res => res.json()).then(function (response) {
            const p = response.data;
            if (!p) return;
            document.getElementById('apiProductId').value = p.id;
            document.getElementById('apiName').value = p.name;
            document.getElementById('apiDescription').value = p.description || '';
            document.getElementById('apiPrice').value = p.price;
            document.getElementById('apiImage').value = p.image || 'public/images/products/default.svg';
            document.getElementById('apiCategoryId').value = p.category_id || '';
            fileInput.value = '';
            preview.src = imageUrl(p.image || 'public/images/products/default.svg');
            showMessage('Bạn đang chỉnh sửa sản phẩm #' + p.id, false);
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    }

    function deleteProduct(id) {
        if (!confirm('Xóa sản phẩm qua API?')) return;
        fetch(API + '/' + id, {method: 'DELETE'})
            .then(res => res.json())
            .then(function (response) {
                showMessage(response.message || 'Đã xóa sản phẩm.', false);
                loadProducts();
            });
    }

    function imageUrl(value) {
        const path = String(value || '').trim();
        if (/^https?:\/\//i.test(path)) return path;
        if (!path) return BASE + '/public/images/products/default.svg';
        return BASE + '/' + path.replace(/^\/+/, '');
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"]/g, function (s) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;'}[s];
        });
    }

    function formatMoney(value) {
        return new Intl.NumberFormat('vi-VN').format(Number(value || 0)) + ' đ';
    }

    fileInput.addEventListener('change', function () {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
    });
    document.getElementById('apiForm').addEventListener('submit', saveProduct);
    document.getElementById('resetApiForm').addEventListener('click', resetForm);
    tbody.addEventListener('click', function (event) {
        if (event.target.dataset.edit) fillEdit(event.target.dataset.edit);
        if (event.target.dataset.delete) deleteProduct(event.target.dataset.delete);
    });
    loadProducts();
})();
