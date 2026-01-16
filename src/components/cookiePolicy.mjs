const cookiePolicy = () => {
  const CONSENT_KEY = "cookie_consent";
  const MAX_AGE = 60 * 60 * 24 * 365;

  const METRIKA_ID = window.ARISMED_METRIKA_ID;

  const banner = document.getElementById("cookies");
  if (!banner) return;

  const btnAccept = banner.querySelector("#acceptCookies");
  const btnDecline = banner.querySelector("#declineCookies");

  const show = () => banner.classList.add("active");
  const hide = () => banner.classList.remove("active");

  const getCookie = (name) => {
    const m = document.cookie.match(
      new RegExp("(^|;\\s*)" + name.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&") + "=([^;]*)")
    );
    return m ? decodeURIComponent(m[2]) : undefined;
  };

  const setCookie = (name, value, maxAgeSec) => {
    const secure = location.protocol === "https:" ? "; Secure" : "";
    document.cookie =
      `${encodeURIComponent(name)}=${encodeURIComponent(value)}` +
      `; Max-Age=${maxAgeSec}` +
      `; Path=/; SameSite=Lax${secure}`;
  };

  const loadMetrika = () => {
    if (!METRIKA_ID) return;
    if (window.__ym_loaded) return;
    window.__ym_loaded = true;

    window.ym =
      window.ym ||
      function () {
        (window.ym.a = window.ym.a || []).push(arguments);
      };
    window.ym.l = +new Date();

    const s = document.createElement("script");
    s.async = true;
    s.src = "https://mc.yandex.ru/metrika/tag.js";
    document.head.appendChild(s);

    window.ym(METRIKA_ID, "init", {
      clickmap: true,
      trackLinks: true,
      accurateTrackBounce: true,
      webvisor: true
    });
  };

  const acceptCookies = () => {
    setCookie(CONSENT_KEY, "accepted", MAX_AGE);
    hide();
    loadMetrika();
  };

  const declineCookies = () => {
    setCookie(CONSENT_KEY, "declined", MAX_AGE);
    hide();
  };

  const init = () => {
    hide();

    if (btnAccept) btnAccept.textContent = "Принять";
    if (btnDecline) btnDecline.textContent = "Отклонить";

    btnAccept?.addEventListener("click", acceptCookies);
    btnDecline?.addEventListener("click", declineCookies);

    setTimeout(() => {
      const v = getCookie(CONSENT_KEY);

      if (v === "accepted") {
        loadMetrika();
        return;
      }

      if (v === "declined") {
        return;
      }

      show();
    }, 2000);
  };

  hide();

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
};

export default cookiePolicy;
