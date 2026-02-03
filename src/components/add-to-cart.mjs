const getWcAjaxBase = () =>
  window.ARISMED && window.ARISMED.wc_ajax ? window.ARISMED.wc_ajax : "/?wc-ajax=";

const enc = (obj) => new URLSearchParams(obj).toString();

const setNotice = (btn, text, ok = true) => {
  const wrap = btn.closest(".add");
  if (!wrap) return;

  const notice = wrap.querySelector(".notice");
  if (!notice) return;

  notice.textContent = text;
  notice.classList.toggle("ok", ok);
  notice.classList.toggle("err", !ok);

  clearTimeout(notice.__t);
  notice.__t = setTimeout(() => {
    notice.textContent = "";
    notice.classList.remove("ok", "err");
  }, 2500);
};


const replaceFragments = (fragments) => {
  if (!fragments || typeof fragments !== "object") return;
  Object.entries(fragments).forEach(([selector, html]) => {
    document.querySelectorAll(selector).forEach((n) => (n.innerHTML = html));
  });
};

const getQty = (form, btn) => {
  const fromData = btn?.getAttribute?.("data-qty");
  if (fromData) {
    const v = parseInt(fromData, 10);
    if (Number.isFinite(v) && v > 0) return v;
  }

  const el =
    form.querySelector?.('input[name="quantity"]') ||
    form.querySelector?.(".qtyInput") ||
    form.querySelector?.('input.qty');

  const v = el ? parseInt(el.value || "1", 10) : 1;
  return Number.isFinite(v) && v > 0 ? v : 1;
};

const getProductId = (form, btn) => {
  const raw =
    form.getAttribute("data-product_id") ||
    form.querySelector('input[name="add-to-cart"]')?.value ||
    btn?.getAttribute?.("data-product_id") ||
    btn?.value;
  const id = raw ? parseInt(raw, 10) : 0;
  return Number.isFinite(id) && id > 0 ? id : 0;
};

const fetchMiniCart = async () => {
  const res = await fetch(getWcAjaxBase() + "arismed_mini_cart", {
    method: "GET",
    credentials: "same-origin",
    cache: "no-store",
  });
  const data = await res.json().catch(() => null);
  if (data?.fragments) replaceFragments(data.fragments);
  return data;
};

const onClick = async (e) => {
  const btn = e.target?.closest?.(".addToCart");
  if (!btn) return;

  const form = btn.closest("form.cart") || btn.closest("form") || btn.closest(".slide") || btn;
  if (!form) return;

  e.preventDefault();
  e.stopPropagation();

  const product_id = getProductId(form, btn);
  if (!product_id) return;

  const quantity = getQty(form, btn);

  btn.disabled = true;
  btn.classList.add("loading");

  try {
    const res = await fetch(getWcAjaxBase() + "add_to_cart", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      credentials: "same-origin",
      body: enc({ product_id, quantity }),
    });

    const data = await res.json().catch(() => null);

    if (data?.fragments) replaceFragments(data.fragments);
    if (window.jQuery) window.jQuery(document.body).trigger("added_to_cart", [data?.fragments, data?.cart_hash, btn]);

    await fetchMiniCart();
    setNotice(form, "Добавлено в корзину", true);
  } catch (err) {
    setNotice(form, "Ошибка добавления", false);
  } finally {
    btn.disabled = false;
    btn.classList.remove("loading");
  }
};

export default function addToCart() {
  document.addEventListener("click", onClick);
}
