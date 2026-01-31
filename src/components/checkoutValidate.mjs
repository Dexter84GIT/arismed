const checkout = () => {
    const form = document.getElementById("checkoutForm");
    if (!form) return;

    const phone = form.querySelector("#phone");
    if (!phone) return;

    const notVal = (error) => {
        console.log(error);
    }

    const normalize = (raw) => {
        if (/[^\d+()\s-]/.test(raw)) notVal();

        let digits = raw.replace(/\D/g, "");
        if (!digits) return "";

        if (digits[0] === "8") {
            digits = "7" + digits.slice(1);
        } else if (digits[0] === "9") {
            digits = "7" + digits;
        } else if (digits[0] !== "7") {
            notVal('Ввод должен начинаться с 7 или 8');
            digits = digits.slice(1);
            if (!digits) return "";
            if (digits[0] === "8") digits = "7" + digits.slice(1);
            else if (digits[0] === "9") digits = "7" + digits;
            else if (digits[0] !== "7") return "";
        }

        if (digits.length > 11) {
            digits = digits.slice(0, 11);
            notVal('Не более 10 знаков');
        }

        return `+${digits}`;
    };

    phone.addEventListener("input", (e) => {
        const before = e.target.value;
        const after = normalize(before);
        e.target.value = after;
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const v = normalize(phone.value);
        phone.value = v;

        if (!/^\+7\d{10}$/.test(v)) {
            notVal('Неправильный ввод');
            return;
        }

        const fd = new FormData(form);
    });
};

export default checkout;
