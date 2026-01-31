const checkoutAddresses = () => {
  const form = document.getElementById("checkoutForm");
  if (!form) return;

  const wrap = document.getElementById("savedAddressWrap");
  const select = document.getElementById("savedAddressSelect");
  if (!wrap || !select) return;

  const $ = (name) => form.querySelector(`[name="${name}"]`);

  const fields = {
    name: $("name"),
    surname: $("surname"),
    adress: $("adress"),
    city: $("city"),
    state: $("state"),
    post: $("post"),
    phone: $("phone"),
    building: $("building"),
    entrance: $("entrance"),
    floor: $("floor"),
    email: $("email"),
  };

  const setVal = (el, v) => {
    if (!el) return;
    el.value = v ?? "";
    el.dispatchEvent(new Event("input", { bubbles: true }));
    el.dispatchEvent(new Event("change", { bubbles: true }));
  };

  const formatLabel = (a) => {
    const parts = [];
    const full = [a.first_name, a.last_name].filter(Boolean).join(" ").trim();
    if (full) parts.push(full);
    if (a.address_1) parts.push(a.address_1);
    const cityline = [a.city, a.state, a.postcode].filter(Boolean).join(", ").trim();
    if (cityline) parts.push(cityline);
    return parts.join(" — ");
  };

  const applyAddress = (a) => {
    setVal(fields.name, a.first_name);
    setVal(fields.surname, a.last_name);

    setVal(fields.adress, [a.address_1, a.address_2].filter(Boolean).join(" ").trim());
    setVal(fields.city, a.city);
    setVal(fields.state, a.state);
    setVal(fields.post, a.postcode);

    if (a.phone) setVal(fields.phone, a.phone);

    if (a.building !== undefined) setVal(fields.building, a.building);
    if (a.entrance !== undefined) setVal(fields.entrance, a.entrance);
    if (a.floor !== undefined) setVal(fields.floor, a.floor);
  };

  const load = async () => {
    try {
      const body = new URLSearchParams();
      body.set("action", "arismed_get_saved_addresses");

      const res = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body,
      });

      const json = await res.json();
      const addresses = json?.success ? (json.data.addresses || []) : [];

      if (!addresses.length) return;

      select.innerHTML = `<option value="">— Не выбирать —</option>`;
      addresses.forEach((a, idx) => {
        const opt = document.createElement("option");
        opt.value = String(idx);
        opt.textContent = (a.type === "shipping" ? "Доставка" : "Платёжный") + ": " + formatLabel(a);
        select.appendChild(opt);
      });

      wrap.style.display = "";

      select.addEventListener("change", () => {
        const idx = select.value === "" ? -1 : Number(select.value);
        if (idx < 0 || !addresses[idx]) return;
        applyAddress(addresses[idx]);
      });
    } catch (e) {
      console.log("NO");
      console.error(e);
    }
  };

  load();
};

export default checkoutAddresses;
