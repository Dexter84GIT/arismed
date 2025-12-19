const enc = (obj) => new URLSearchParams(obj).toString();

const getWcAjaxBase = () =>
  window.ARISMED && ARISMED.wc_ajax ? ARISMED.wc_ajax : "/?wc-ajax=";

const replaceFragments = (fragments) => {
  if (!fragments || typeof fragments !== "object") return;
  Object.keys(fragments).forEach((sel) => {
    const html = fragments[sel];
    const nodes = document.querySelectorAll(sel);
    if (!nodes.length) return;
    nodes.forEach((n) => {
      if (n.tagName === "INPUT" || n.tagName === "TEXTAREA") {
        n.value = String(html ?? "");
      } else {
        n.innerHTML = String(html ?? "");
      }
    });
  });
};

const post = async (endpoint, bodyObj) => {
  const res = await fetch(`${getWcAjaxBase()}${endpoint}`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
    body: enc(bodyObj || {}),
    credentials: "same-origin",
  });
  const data = await res.json().catch(() => null);
  return data;
};

let syncInFlight = null;

const fetchSync = async () => {
  const data = await post("arismed_cart_sync", {});

  // cart page
  const cart = document.querySelector('[data-arismed-cart="1"]');
  if (cart && data?.cart_page_items_html != null) {
    const items = cart.querySelector("[data-cart-items]");
    if (items) items.innerHTML = data.cart_page_items_html;

    const total = cart.querySelector("[data-cart-total]");
    if (total) total.innerHTML = data.cart_page_total_html || "";
  }

  // mini-cart (ВАЖНО: у тебя нет fragments, поэтому обновляем напрямую)
  const miniBtn = document.querySelector(".miniCartBtn");
  if (miniBtn) {
    const countEl = miniBtn.querySelector(".iconLink .count");
    if (countEl) {
      if (data?.count_html != null) countEl.textContent = String(data.count_html);
      else if (data?.count != null) countEl.textContent = String(data.count);
    }

    const mini = miniBtn.querySelector(".miniCart");
    if (mini) {
      const content = mini.querySelector(".content");
      if (content && data?.items_html != null) content.innerHTML = String(data.items_html);

      const sum = mini.querySelector(".overall .sum");
      if (sum && data?.total_html != null) sum.innerHTML = String(data.total_html);
    }
  }

  if (data && data.fragments) replaceFragments(data.fragments);

  return data;
};


const findCartRoot = () => document.querySelector('[data-arismed-cart="1"]');

let miniBusy = false;

const onMiniRemove = async (btn) => {
  if (miniBusy) return;

  const item = btn.closest(".item");
  if (!item) return;

  const key = item.getAttribute("data-key") || "";
  if (!key) return;

  const nonce =
    btn.getAttribute("data-nonce") ||
    (btn.closest(".miniCart") && btn.closest(".miniCart").getAttribute("data-nonce")) ||
    "";

  if (!nonce) return;

  miniBusy = true;
  try {
    const data = await post("arismed_cart_remove", { key, nonce });
    if (!data || !data.ok) return;
    await fetchSync();
  } finally {
    miniBusy = false;
  }
};

const clampQty = (n) => {
  const x = Number(n);
  if (!Number.isFinite(x) || x <= 0) return 1;
  return Math.floor(x);
};

let cartBusy = false;

const onCartAction = async (actEl) => {
  if (cartBusy) return;

  const cart = findCartRoot();
  if (!cart) return;

  const row = actEl.closest(".row.unit");
  const key = row?.getAttribute("data-key") || "";
  if (!key) return;

  const nonce = cart.getAttribute("data-nonce") || "";
  if (!nonce) return;

  const action = actEl.getAttribute("data-action") || "";

  cartBusy = true;
  try {
    if (action === "remove") {
      const data = await post("arismed_cart_remove", { key, nonce });
      if (!data || !data.ok) return;
      await fetchSync();
      return;
    }

    if (action === "inc" || action === "dec") {
      const countEl =
        row.querySelector("[data-count]") ||
        row.querySelector(".count");

      const cur = clampQty((countEl && countEl.textContent) || 1);
      const next = action === "inc" ? cur + 1 : Math.max(1, cur - 1);

      if (next === cur) return;

      const data = await post("arismed_cart_update_qty", { key, qty: String(next), nonce });
      if (!data || !data.ok) return;
      await fetchSync();
      return;
    }
  } finally {
    cartBusy = false;
  }
};

const onClick = (e) => {
  const delMini = e.target.closest(".miniCartBtn .miniCart .delete");
  if (delMini) {
    e.preventDefault();
    e.stopPropagation();
    onMiniRemove(delMini);
    return;
  }

  const cart = findCartRoot();
  if (!cart) return;

  const actEl = e.target.closest("[data-action]");
  if (!actEl || !cart.contains(actEl)) return;

  e.preventDefault();
  e.stopPropagation();
  e.stopImmediatePropagation();
  onCartAction(actEl);
};

const onKeyDown = (e) => {
  if (e.key !== "Enter" && e.key !== " ") return;

  const cart = findCartRoot();
  if (!cart) return;

  const actEl = e.target.closest("[data-action]");
  if (!actEl || !cart.contains(actEl)) return;

  e.preventDefault();
  e.stopPropagation();
  onCartAction(actEl);
};

const onAddedToCart = () => fetchSync();

let inited = false;

const initMiniCartSync = () => {
  if (inited) return;
  inited = true;

  document.addEventListener("click", onClick, true);
  document.addEventListener("keydown", onKeyDown, true);
  document.body.addEventListener("added_to_cart", onAddedToCart);

  fetchSync();
};

const destroyMiniCartSync = () => {
  if (!inited) return;
  inited = false;

  document.removeEventListener("click", onClick, true);
  document.removeEventListener("keydown", onKeyDown, true);
  document.body.removeEventListener("added_to_cart", onAddedToCart);
};

export {
  initMiniCartSync,
  destroyMiniCartSync,
  fetchSync,
  onAddedToCart,
  getWcAjaxBase,
  enc,
  replaceFragments,
};
