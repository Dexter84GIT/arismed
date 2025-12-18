export function enc(obj) {
  return new URLSearchParams(obj).toString();
}

export function getWcAjaxBase() {
  return window.ARISMED && ARISMED.wc_ajax ? ARISMED.wc_ajax : "/?wc-ajax=";
}

export function replaceFragments(fragments) {
  if (!fragments || typeof fragments !== "object") return;

  Object.entries(fragments).forEach(([selector, html]) => {
    document.querySelectorAll(selector).forEach((node) => {
      node.innerHTML = html;
    });
  });
}

export async function removeMiniCartItem({ key, nonce }) {
  const res = await fetch(getWcAjaxBase() + "arismed_remove_from_cart", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: enc({ key, nonce }),
    credentials: "same-origin",
  });

  const data = await res.json();
  if (data && data.fragments) replaceFragments(data.fragments);

  return data;
}

export function onMiniCartDeleteClick(e) {
  const btn = e.target && e.target.closest ? e.target.closest(".miniCart .delete") : null;
  if (!btn) return;

  e.preventDefault();

  const key = btn.getAttribute("data-key");
  const nonce = btn.getAttribute("data-nonce");
  if (!key || !nonce) return;

  if (btn.dataset.lock === "1") return;
  btn.dataset.lock = "1";

  btn.classList.add("loading");

  removeMiniCartItem({ key, nonce })
    .finally(() => {
      btn.classList.remove("loading");
      btn.dataset.lock = "0";
    });
}

export function initMiniCartRemove() {
  document.addEventListener("click", onMiniCartDeleteClick);
}

export function destroyMiniCartRemove() {
  document.removeEventListener("click", onMiniCartDeleteClick);
}
