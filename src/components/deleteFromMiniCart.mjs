import { enc, getWcAjaxBase, replaceFragments } from "./wc-helpers.mjs";

export const onMiniCartRemoveClick = async (e) => {
  const btn = e.target?.closest?.(".miniCart .delete");
  if (!btn) return;

  e.preventDefault();
  e.stopPropagation();

  const key = btn.getAttribute("data-key");
  const nonce = btn.getAttribute("data-nonce");
  if (!key || !nonce) return;

  btn.classList.add("loading");

  try {
    const res = await fetch(getWcAjaxBase() + "arismed_remove_from_cart", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: enc({ key, nonce }),
      credentials: "same-origin",
    });

    const data = await res.json().catch(() => null);
    if (data?.fragments) replaceFragments(data.fragments);
  } finally {
    btn.classList.remove("loading");
  }
};

export const initMiniCartRemove = () => {
  document.addEventListener("click", onMiniCartRemoveClick);
};

export const destroyMiniCartRemove = () => {
  document.removeEventListener("click", onMiniCartRemoveClick);
};
