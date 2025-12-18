const getBase = () =>
  window.ARISMED && ARISMED.wc_ajax ? ARISMED.wc_ajax : "/?wc-ajax=";

let inFlight = false;

const updateMiniCart = async () => {
  if (inFlight) return null;
  inFlight = true;

  try {
    const res = await fetch(`${getBase()}arismed_mini_cart&ts=${Date.now()}`, {
      credentials: "same-origin",
    });
    if (!res.ok) return null;

    const data = await res.json().catch(() => null);
    if (!data || typeof data.html !== "string") return null;

    const wrap = document.querySelector(".miniCartBtn");
    if (!wrap) return data;

    const tmp = document.createElement("div");
    tmp.innerHTML = data.html.trim();
    const next = tmp.firstElementChild;
    if (next) wrap.replaceWith(next);

    return data;
  } finally {
    inFlight = false;
  }
};

export default updateMiniCart;
