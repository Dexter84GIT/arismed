

export const getWcAjaxBase = () =>
  window.ARISMED && ARISMED.wc_ajax ? ARISMED.wc_ajax : "/?wc-ajax=";

export const enc = (obj) => new URLSearchParams(obj).toString();

export const replaceFragments = (fragments) => {
  if (!fragments || typeof fragments !== "object") return;
  Object.entries(fragments).forEach(([selector, html]) => {
    document.querySelectorAll(selector).forEach((n) => (n.innerHTML = html));
  });
};

export const fetchMiniCart = async () => {
  const res = await fetch(getWcAjaxBase() + "arismed_mini_cart", {
    method: "GET",
    credentials: "same-origin",
    cache: "no-store",
  });
  const data = await res.json();
  if (data?.fragments) replaceFragments(data.fragments);
  return data;
};

export const onAddedToCart = () => {
  fetchMiniCart();
};

export const initMiniCartSync = () => {
  if (!window.jQuery) return;
  window.jQuery(document.body).on("added_to_cart", onAddedToCart);
};

export const destroyMiniCartSync = () => {
  if (!window.jQuery) return;
  window.jQuery(document.body).off("added_to_cart", onAddedToCart);
};
