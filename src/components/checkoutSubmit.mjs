export default function initDevPay() {
  const form = document.getElementById("checkoutForm");
  if (!form) return;

  const btn = document.getElementById("devPayBtn");
  if (!btn) return;

  btn.addEventListener("click", async () => {
    const fd = new FormData(form);
    fd.append("action", "arismed_dev_pay");
    fd.append("nonce", btn.dataset.nonce || "");

    try {
      btn.disabled = true;

      const res = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
        method: "POST",
        body: fd,
        credentials: "same-origin",
      });

      const json = await res.json();

      if (!json?.success) {
        console.log("NO");
        console.error(json?.data?.message || "DEV pay error");
        btn.disabled = false;
        return;
      }

      window.location.href = json.data.redirect;
    } catch (e) {
      console.log("NO");
      console.error(e);
      btn.disabled = false;
    }
  });
}
