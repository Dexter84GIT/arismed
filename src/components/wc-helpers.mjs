export const getWcAjaxBase = () =>
  window.ARISMED && window.ARISMED.wc_ajax ? window.ARISMED.wc_ajax : "/?wc-ajax=";

export const enc = (obj) => new URLSearchParams(obj).toString();

export const replaceFragments = (fragments) => {
  if (!fragments || typeof fragments !== "object") return;
  Object.entries(fragments).forEach(([selector, html]) => {
    document.querySelectorAll(selector).forEach((n) => (n.innerHTML = html));
  });
};