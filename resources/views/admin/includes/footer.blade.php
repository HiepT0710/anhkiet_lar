<script>
document.addEventListener('DOMContentLoaded', function () {
    const tokenTag = document.querySelector('meta[name="csrf-token"]');
    if (!tokenTag) return;
    const csrfToken = tokenTag.getAttribute('content');
    document.body.addEventListener('change', async function (e) {
        const target = e.target;
        if (target && target.classList.contains('toggle-status')) {
            const url = target.getAttribute('data-url');
            const previous = !target.checked;
            try {
                const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                if (!res.ok) throw new Error();
            } catch (err) {
                target.checked = previous;
                alert('Cập nhật trạng thái thất bại.');
            }
        }
    });
});
</script>
















