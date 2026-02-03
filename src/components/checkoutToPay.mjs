import { validatePhone } from './checkoutValidate.mjs';

const checkoutToPay = () => {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const phoneInput = form.querySelector('#phone');
    const postInput = form.querySelector('input[name="post"]');
    if (!phoneInput || !postInput) return;

    const setError = (el, state) => {
        el.style.borderColor = state ? 'red' : '';
    };

    const notVal = (msg) => {
        showError(msg);
    };

    const errorNotice = form.querySelector('#errorNotice');
    let errorTimer = null;

    const showError = (msg) => {
        if (!errorNotice) return;

        errorNotice.textContent = msg;
        errorNotice.classList.add('active');

        if (errorTimer) clearTimeout(errorTimer);

        errorTimer = setTimeout(() => {
            hideError();
        }, 5000);
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

    postInput.addEventListener('input', () => {
        const value = postInput.value;
        const isDigitsOnly = /^\d*$/.test(value);

        if (!isDigitsOnly || value.length < 6) {
            setError(postInput, true);
        } else {
            setError(postInput, false);
        }
    });

    form.addEventListener('change', hideError);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const phoneRaw = phoneInput.value;
        const phoneNormalized = validatePhone(phoneRaw);

        if (!phoneNormalized) {
            setError(phoneInput, true);
            notVal('Некорректный телефон');
            return;
        } else {
            setError(phoneInput, false);
        }

        const postValue = postInput.value;
        if (!/^\d{6}$/.test(postValue)) {
            setError(postInput, true);
            notVal('Некорректный индекс');
            return;
        }

        const email = form.querySelector('input[name="email"]')?.value.trim();
        const name = form.querySelector('input[name="name"]')?.value.trim();
        const surname = form.querySelector('input[name="surname"]')?.value.trim();
        const address = form.querySelector('input[name="adress"]')?.value.trim();
        const city = form.querySelector('input[name="city"]')?.value.trim();
        const paymentMethod = form.querySelector('input[name="payment"]:checked');

        if (!email || !name || !surname || !address || !city) {
            notVal('Не заполнены обязательные поля');
            return;
        }

        if (!paymentMethod) {
            notVal('Не выбран метод оплаты');
            return;
        }

        const fd = new FormData(form);
        fd.set('phone', phoneNormalized);

        try {
            const res = await fetch('/wp-admin/admin-ajax.php?action=arismed_checkout_prepare', {
                method: 'POST',
                body: fd,
            });

            if (!res.ok) {
                notVal('Ошибка сервера');
                return;
            }

            const data = await res.json();

            if (!data.success && data.data?.code === 'login_required') {
                showError('Для выставления счёта нужно авторизоваться');
                return;
            }

            if (data.success && data.data?.redirect) {
                window.location.href = data.data.redirect;
                return;
            }

            notVal(data.data?.message || data.message || 'Ошибка обработки');
        } catch (err) {
            notVal('Ошибка сети');
        }
    });
};

export default checkoutToPay;
