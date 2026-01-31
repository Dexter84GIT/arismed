const accountDeleteAdress = () => {
    const list = document.querySelector('#adress .content');

    list.addEventListener('click', async (e) => {
        const btn = e.target.closest('button.delete');
        if (!btn) return;

        const row = btn.closest('[data-address-id]');
        const addressId = row?.dataset.addressId || '';
        if (!addressId) return;

        btn.disabled = true;

        const fd = new FormData();
        fd.append('action', 'arismed_delete_address');
        fd.append('address_id', addressId);

        let json;
        try {
            const res = await fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                credentials: 'same-origin',
                body: fd,
            });
            json = await res.json();
        } catch (err) {
            btn.disabled = false;
            return;
        }

        if (!json?.success) {
            btn.disabled = false;
            return;
        }

        row.remove();
    });

}

export default accountDeleteAdress