const invoiceSubmit = (container) => {
    if (!container) return;

    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const orgInput = form.querySelector('input[name="organization"]');
    const innInput = form.querySelector('input[name="inn"]');
    const kppInput = form.querySelector('input[name="kpp"]');
    const addrInput = form.querySelector('input[name="organization_address"]');
    const errorNotice = form.querySelector('#errorNotice');

    let errorTimer = null;

    const setError = (el, state) => {
        if (!el) return;
        el.style.borderColor = state ? 'red' : '';
    };

    const showError = (msg) => {
        if (!errorNotice) return;

        errorNotice.textContent = msg;
        errorNotice.classList.add('active');

        if (errorTimer) clearTimeout(errorTimer);
        errorTimer = setTimeout(hideError, 5000);
    };

    const hideError = () => {
        if (!errorNotice) return;

        errorNotice.textContent = '';
        errorNotice.classList.remove('active');

        if (errorTimer) {
            clearTimeout(errorTimer);
            errorTimer = null;
        }
    };

    const digitsOnly = (input, maxLen) => {
        input.addEventListener('input', () => {
            const cleaned = input.value.replace(/\D+/g, '');
            input.value = maxLen ? cleaned.slice(0, maxLen) : cleaned;
        });
    };

    digitsOnly(innInput, 10);
    digitsOnly(kppInput, 9);

    form.addEventListener('change', hideError);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const organization = orgInput?.value.trim();
        const inn = innInput?.value.trim();
        const kpp = kppInput?.value.trim();
        const address = addrInput?.value.trim();

        let invalid = false;

        if (!organization) {
            setError(orgInput, true);
            invalid = true;
        } else {
            setError(orgInput, false);
        }

        if (!/^\d{10}$/.test(inn)) {
            setError(innInput, true);
            invalid = true;
        } else {
            setError(innInput, false);
        }

        if (!/^\d{9}$/.test(kpp)) {
            setError(kppInput, true);
            invalid = true;
        } else {
            setError(kppInput, false);
        }

        if (!address) {
            setError(addrInput, true);
            invalid = true;
        } else {
            setError(addrInput, false);
        }

        if (invalid) {
            showError('Проверьте реквизиты организации');
            return;
        }

        const fd = new FormData(form);

        try {
            const res = await fetch('/wp-admin/admin-ajax.php?action=arismed_invoice_submit', {
                method: 'POST',
                body: fd,
            });

            if (!res.ok) {
                showError('Ошибка сервера');
                return;
            }

            const data = await res.json();

            if (data.success && data.data?.redirect) {
                window.location.href = data.data.redirect;
                return;
            }

            showError(data.data?.message || 'Ошибка обработки');
        } catch {
            showError('Ошибка сети');
        }
    });
};

export default invoiceSubmit;
