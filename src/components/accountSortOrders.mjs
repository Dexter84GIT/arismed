const accountSortOrders = () => {
    const root = document.getElementById('orders');
    if (!root) return;

    const input = root.querySelector('.searchInput');
    const badges = root.querySelectorAll('.statusBar .statusBadge');
    const resetBtn = root.querySelector('.statusBar .statusBadge.reset');
    const items = Array.from(root.querySelectorAll('.content .item'));

    let statusFilter = null;
    let paidFilter = null;

    const norm = (s) => (s || '').toString().trim();

    const apply = () => {
        const q = norm(input?.value).replace(/[^\d]/g, '');
        items.forEach(el => {
            const id = norm(el.getAttribute('data-order-id'));
            const st = norm(el.getAttribute('data-status'));
            const paid = norm(el.getAttribute('data-paid'));

            const okSearch = !q || id.includes(q);
            const okStatus = !statusFilter || st === statusFilter;
            const okPaid = paidFilter === null || paid === paidFilter;

            el.style.display = (okSearch && okStatus && okPaid) ? '' : 'none';
        });

        badges.forEach(b => b.classList.toggle('active', false));
        if (statusFilter) root.querySelector(`.statusBar .statusBadge.${statusFilter}`)?.classList.add('active');
        if (paidFilter === '1') root.querySelector('.statusBar .statusBadge.hasPayment')?.classList.add('active');
        if (paidFilter === '0') root.querySelector('.statusBar .statusBadge.hasntPayment')?.classList.add('active');
    };

    const resetAll = () => {
        statusFilter = null;
        paidFilter = null;
        if (input) input.value = '';
        apply();
    };

    input?.addEventListener('input', apply);

    badges.forEach(badge => {
        if (badge.classList.contains('reset')) return;

        badge.addEventListener('click', () => {
            if (badge.classList.contains('pending') || badge.classList.contains('complete') || badge.classList.contains('canceled')) {
                const v = badge.classList.contains('pending') ? 'pending'
                    : badge.classList.contains('complete') ? 'complete'
                        : 'canceled';
                statusFilter = (statusFilter === v) ? null : v;
            } else if (badge.classList.contains('hasPayment')) {
                paidFilter = (paidFilter === '1') ? null : '1';
            } else if (badge.classList.contains('hasntPayment')) {
                paidFilter = (paidFilter === '0') ? null : '0';
            }
            apply();
        });
    });

    resetBtn?.addEventListener('click', resetAll);

    apply();
}

export default accountSortOrders