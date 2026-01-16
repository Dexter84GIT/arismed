const debounce = (fn, ms = 250) => {
    let t = null;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), ms);
    };
};

const escapeHtml = (s) =>
    String(s).replace(/[&<>"']/g, (c) => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;",
    }[c]));

export default function headerSearch({
    rootSelector = "#searchBtn",
    inputSelector = 'input[type="search"]',
    resultSelector = ".result",
    minChars = 3,
    debounceMs = 250,
    endpoint = "/wp-json/myshop/v1/search",
    limit = 8,
} = {}) {
    const root = document.querySelector(rootSelector);
    if (!root) return;

    const input = root.querySelector(inputSelector);
    const result = root.querySelector(resultSelector);

    if (!input || !result) return;

    let lastQuery = "";
    let abort = null;

    const setEmpty = () => {
        result.classList.remove("active");
        result.innerHTML = `<div class="searchLoading">По вашему запросу ничего не найдено</div>`;
    };

    const setLoading = () => {
        result.classList.add("active");
        result.innerHTML = `<div class="searchLoading">Идет поиск...</div>`;
    };

    const render = ({ products = [], categories = [] }) => {
        const hasAny = (products?.length || 0) + (categories?.length || 0) > 0;

        if (!hasAny) {
            setEmpty();
            return;
        }

        result.classList.add("active");

        const catBlock = categories.length
            ? `
        <div class="searchGroup">
          <div class="searchGroupTitle">Категории</div>
          <div class="searchList">
            ${categories
                .map(
                    (c) => `
                <a class="searchItem" href="${escapeHtml(c.url)}">
                  ${escapeHtml(c.name)}
                </a>`
                )
                .join("")}
          </div>
        </div>
      `
            : "";

        const prodBlock = products.length
            ? `
        <div class="searchGroup">
          <div class="searchGroupTitle">Товары</div>
          <div class="searchList">
            ${products
                .map(
                    (p) => `
                <a class="searchItem" href="${escapeHtml(p.url)}">
                  ${escapeHtml(p.title)}
                </a>`
                )
                .join("")}
          </div>
        </div>
      `
            : "";

        result.innerHTML = catBlock + prodBlock;
    };

    const fetchSearch = async (q) => {
        if (abort) abort.abort();
        abort = new AbortController();

        const url = new URL(endpoint, window.location.origin);
        url.searchParams.set("q", q);
        url.searchParams.set("limit", String(limit));

        setLoading();

        const res = await fetch(url.toString(), {
            method: "GET",
            headers: { Accept: "application/json" },
            signal: abort.signal,
            credentials: "same-origin",
        });

        if (!res.ok) throw new Error(`Search HTTP ${res.status}`);

        return res.json();
    };

    const onInput = debounce(async () => {
        const q = input.value.trim();

        if (q.length < minChars) {
            lastQuery = q;
            setEmpty();
            return;
        }

        if (q === lastQuery) return;
        lastQuery = q;

        try {
            const data = await fetchSearch(q);
            if (input.value.trim() !== q) return;

            render(data);
        } catch (err) {
            if (err?.name === "AbortError") return;
            setEmpty();
        }
    }, debounceMs);

    input.addEventListener("input", onInput);

    input.addEventListener("keydown", (e) => {
        if (e.key === "Escape") setEmpty();
    });
}
