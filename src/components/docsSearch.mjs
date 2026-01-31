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

export default function initDocsSearch({
    rootSelector = ".docs.section",
    inputSelector = ".searchInput",
    resultsSelector = ".results",
    countSelector = ".count",
    emptySelector = ".js-docs-empty",
    minChars = 3,
    debounceMs = 250,
    endpoint = "/wp-json/mydocs/v1/search",
    limit = 200,
} = {}) {
    const root = document.querySelector(rootSelector);
    if (!root) return;

    const input = root.querySelector(inputSelector);
    const results = root.querySelector(resultsSelector);
    const initialHTML = results.innerHTML;
    const initialCount = results.querySelectorAll("a.row").length;
    const countEl = root.querySelector(countSelector);
    const emptyEl = root.querySelector(emptySelector);

    if (!input || !results) return;

    let abort = null;
    let lastQ = "";

    const restoreInitial = () => {
        results.innerHTML = initialHTML;
        setCount(initialCount);
        setEmptyMsg(false);
    };

    const setCount = (n) => {
        if (countEl) countEl.textContent = String(n);
    };

    const setEmptyMsg = (show) => {
        if (!emptyEl) return;
        emptyEl.style.display = show ? "" : "none";
    };

    const clearList = () => {
        results.querySelectorAll("a.row").forEach((a) => a.remove());
    };

    const renderItems = (items = []) => {
        clearList();

        if (!items.length) {
            setEmptyMsg(true);
            setCount(0);
            return;
        }

        setEmptyMsg(false);
        setCount(items.length);

        const html = items
            .map((it) => {
                const title = escapeHtml(it.title || "");
                const file = escapeHtml(it.file || "#");
                const type = escapeHtml(it.type || "");

                return `
          <a href="${file}" class="row df aic gap20" download>
            <div class="img ${type}"></div>
            <p>${title}</p>
          </a>
        `;
            })
            .join("");

        results.insertAdjacentHTML("beforeend", html);
    };

    const fetchDocs = async (q) => {
        if (abort) abort.abort();
        abort = new AbortController();

        const url = new URL(endpoint, window.location.origin);
        url.searchParams.set("q", q);
        url.searchParams.set("limit", String(limit));

        const res = await fetch(url.toString(), {
            method: "GET",
            headers: { Accept: "application/json" },
            signal: abort.signal,
            credentials: "same-origin",
        });

        if (!res.ok) throw new Error(`Docs search HTTP ${res.status}`);
        return res.json();
    };

    const run = debounce(async () => {
        const q = input.value.trim();

        if (q.length < minChars) {
            lastQ = q;
            restoreInitial();
            return;
        }

        if (q === lastQ) return;
        lastQ = q;

        try {
            const data = await fetchDocs(q);
            if (input.value.trim() !== q) return;

            renderItems(data.items || []);
        } catch (e) {
            if (e?.name === "AbortError") return;
            renderItems([]);
        }
    }, debounceMs);

    input.addEventListener("input", run);
}
