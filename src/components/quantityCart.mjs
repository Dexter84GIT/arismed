export default function quantityCart() {
    const root = document.querySelector(".woocommerce-cart-form");
    if (!root) return;

    const submitUpdate = () => {
        const btn = root.querySelector('button[name="update_cart"]');
        if (btn) btn.click();
    };

    root.addEventListener("click", (e) => {
        const dec = e.target.closest(".quantity .dec");
        const inc = e.target.closest(".quantity .inc");
        if (!dec && !inc) return;

        const wrap = e.target.closest(".quantity");
        const input = wrap ? wrap.querySelector(".qtyInput") : null;
        const count = wrap ? wrap.querySelector(".count") : null;
        if (!input || !count) return;

        const min = Number(input.getAttribute("min") || "1");
        const max = Number(input.getAttribute("max") || "9999");
        const cur = Number(input.value || "1");
        const next = dec ? Math.max(min, cur - 1) : Math.min(max, cur + 1);

        if (next === cur) return;

        input.value = String(next);
        count.textContent = String(next);

        submitUpdate();
    });
}
