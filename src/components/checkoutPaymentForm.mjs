const checkoutPaymentForm = (container) => {
    if (!container) return;

    if (!window.ARISMED_CONFIRMATION_TOKEN) {
        console.error('Отсутствует токен подтверждения');
        return;
    }

    if (!window.ARISMED_ORDER_ID) {
        console.error('Отсутствует ID заказа');
        return;
    }

    if (!window.ARISMED_ORDER_KEY) {
        console.error('Отсутствует KEY заказа');
        return;
    }

    if (!window.ARISMED_SECRET_TOKEN) {
        console.error('Нет secret token');
        return;
    }

    const checkout = new window.YooMoneyCheckoutWidget({
        confirmation_token: window.ARISMED_CONFIRMATION_TOKEN,
        return_url:
            `https://arismed.ru/thankyou` +
            `?order_id=${window.ARISMED_ORDER_ID}` +
            `&key=${window.ARISMED_ORDER_KEY}` +
            `&token=${window.ARISMED_SECRET_TOKEN}`,
             error_callback: (e) => console.error(e),
    });

    checkout.render('payment-form');
}

export default checkoutPaymentForm