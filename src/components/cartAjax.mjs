const enc = (obj) => new URLSearchParams(obj).toString();

const ajaxBase = () =>
  window.ARISMED && ARISMED.wc_ajax ? ARISMED.wc_ajax : "/?wc-ajax=";

export default function cartAjax() {
  const root = document.querySelector(".cart.section");
  if (!root) return;

  root.addEventListener("click", async (e) => {
    const inc = e.target.closest(".quantity .inc");
    const dec = e.target.closest(".quantity .dec");
    if (!inc && !dec) return;

    const wrap = e.target.closest(".quantity");
    if (!wrap) return;

    const key = wrap.dataset.key;
    const countEl = wrap.querySelector(".count");
    if (!key || !countEl) return;

    let qty = parseInt(countEl.textContent, 10) || 1;
    qty = inc ? qty + 1 : Math.max(1, qty - 1);

    root.classList.add("loading");

    try {
      const res = await fetch(ajaxBase() + "arismed_update_cart", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: enc({ key, qty }),
        credentials: "same-origin",
      });

      const data = await res.json();
      if (data?.success && data.data?.html) {
        root.outerHTML = data.data.html;
      }
    } finally {
      root.classList.remove("loading");
    }
  });
}
