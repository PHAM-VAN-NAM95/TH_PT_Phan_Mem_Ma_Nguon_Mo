document.addEventListener('click', function (event) {
    if (!event.target.classList.contains('qty-btn')) return;
    const control = event.target.closest('.qty-control');
    if (!control) return;
    const input = control.querySelector('input[type="number"]');
    const current = parseInt(input.value || '1', 10);
    const next = event.target.dataset.action === 'plus' ? current + 1 : Math.max(1, current - 1);
    input.value = next;
});

document.addEventListener('change', function (event) {
    if (!event.target.classList.contains('image-input')) return;
    const previewSelector = event.target.dataset.preview;
    const preview = document.querySelector(previewSelector);
    const file = event.target.files && event.target.files[0];
    if (!preview || !file) return;
    preview.src = URL.createObjectURL(file);
});
