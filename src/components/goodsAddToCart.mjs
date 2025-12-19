import { getWcAjaxBase, enc, replaceFragments } from "./wc-helpers.mjs";
import { fetchSync } from "./miniCartSync.mjs";

let addedTimer = null;

const showAddedNotice = () => {
  const el =
    document.getElementById("addedNotice") ||
    document.getElementById("added") ||
    document.querySelector(".added");

  if (!el) return;

  clearTimeout(addedTimer);

  el.hidden = false;
  el.classList.add("show");

  addedTimer = setTimeout(() => {
    el.classList.remove("show");
    el.hidden = true;
  }, 2000);
};

const setNotice = (btn, text, ok = true) => {
  const wrap = btn.closest(".swiper-slide") || btn.closest(".bottom") || btn.closest(".add") || btn.parentElement;
  if (!wrap) return;

  const el = wrap.querySelector(".notice");
  if (!el) return;

  el.textContent = text || "";
  el.classList.toggle("ok", !!ok);
  el.classList.toggle("err", !ok);

  if (text) {
    clearTimeout(el.__t);
    el.__t = setTimeout(() => {
      el.textContent = "";
      el.classList.remove("ok", "err");
    }, 2500);
  }
};

const getQty = (form, btn) => {
  const el =
    (form && (form.querySelector('input[name="quantity"]') || form.querySelector(".qtyInput") || form.querySelector("input.qty"))) ||
    (btn && (btn.closest(".quantity")?.querySelector("input") || null));

  const v = el ? parseInt(el.value || "1", 10) : 1;
  return Number.isFinite(v) && v > 0 ? v : 1;
};

const getProductId = (form, btn) => {
  const raw =
    btn?.dataset?.product_id ||
    btn?.getAttribute?.("data-product_id") ||
    form?.getAttribute?.("data-product_id") ||
    form?.querySelector?.('input[name="add-to-cart"]')?.value ||
    btn?.value;

  const id = raw ? parseInt(String(raw), 10) : 0;
  return Number.isFinite(id) && id > 0 ? id : 0;
};

const addProductToCart = async (productId, qty) => {
  const res = await fetch(getWcAjaxBase() + "add_to_cart", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
    credentials: "same-origin",
    body: enc({ product_id: String(productId), quantity: String(qty) }),
  });

  const data = await res.json().catch(() => null);

  if (data?.fragments) replaceFragments(data.fragments);

  if (window.jQuery) {
    window.jQuery(document.body).trigger("added_to_cart", [
      data?.fragments || {},
      data?.cart_hash || "",
      null,
    ]);
  }

  await fetchSync();
  return data;
};

const isValidBtn = (btn) => {
  if (!btn) return false;
  if (btn.matches("a.ajaxAddToCart")) return true;
  if (btn.matches("button.addToCart")) return true;
  if (btn.matches("button.single_add_to_cart_button")) return true;
  if (btn.matches(".ajax_add_to_cart.addToCart")) return true;
  return false;
};

const onAddClick = async (e) => {
  const btn = e.target?.closest?.("a.ajaxAddToCart, button.addToCart, button.single_add_to_cart_button");
  if (!isValidBtn(btn)) return;

  const form = btn.closest("form.cart") || btn.closest("form") || null;

  e.preventDefault();
  e.stopPropagation();
  e.stopImmediatePropagation();

  const productId = getProductId(form, btn);
  const qty = btn.dataset?.qty ? Number(btn.dataset.qty || 1) : getQty(form, btn);

  if (!productId || qty <= 0) return;

  if (btn.__busy) return;
  btn.__busy = true;

  btn.classList.add("loading");
  btn.disabled = true;

  try {
    await addProductToCart(productId, qty);
    showAddedNotice();
    setNotice(btn, "Товар добавлен", true);
  } catch (err) {
    setNotice(btn, "Ошибка добавления", false);
  } finally {
    btn.__busy = false;
    btn.classList.remove("loading");
    btn.disabled = false;
  }
};

export default function goodsAddToCart() {
  document.addEventListener("click", onAddClick, true);
}
