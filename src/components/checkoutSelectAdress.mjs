const checkoutAddresses = () => {
  const form = document.getElementById('checkoutForm');
  if (!form) return;

  const wrap   = document.getElementById('savedAddressWrap');
  const select = document.getElementById('savedAddressSelect');
  if (!wrap || !select) return;

  const getField = (name) => form.querySelector(`[name="${name}"]`);

  const fields = {
    name: getField('name'),
    surname: getField('surname'),
    adress: getField('adress'),
    city: getField('city'),
    state: getField('state'),
    post: getField('post'),
    phone: getField('phone'),
    building: getField('building'),
    entrance: getField('entrance'),
    floor: getField('floor'),
    email: getField('email'),
  };

  const setVal = (el, value) => {
    if (!el) return;
    el.value = value ?? '';
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  };

  const formatLabel = (a) => {
    const parts = [];
    const name = [a.first_name, a.last_name].filter(Boolean).join(' ');
    if (name) parts.push(name);
    if (a.address_1) parts.push(a.address_1);
    const city = [a.city, a.state, a.postcode].filter(Boolean).join(', ');
    if (city) parts.push(city);
    return parts.join(' — ');
  };

  const applyAddress = (a) => {
    setVal(fields.name, a.first_name);
    setVal(fields.surname, a.last_name);
    setVal(fields.adress, [a.address_1, a.address_2].filter(Boolean).join(' '));
    setVal(fields.city, a.city);
    setVal(fields.state, a.state);
    setVal(fields.post, a.postcode);
    setVal(fields.phone, a.phone);
    setVal(fields.building, a.building);
    setVal(fields.entrance, a.entrance);
    setVal(fields.floor, a.floor);
  };

  const loadAddresses = async () => {
    try {
      const body = new URLSearchParams();
      body.set('action', 'arismed_get_saved_addresses');

      const res = await fetch(
        window.ajaxurl || '/wp-admin/admin-ajax.php',
        {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body,
        }
      );

      const json = await res.json();
      if (!json?.success || !Array.isArray(json.data?.addresses)) return;

      const addresses = json.data.addresses;
      if (!addresses.length) return;

      select.innerHTML = '<option value="">— Не выбирать —</option>';

      addresses.forEach((a, i) => {
        const opt = document.createElement('option');
        opt.value = String(i);
        opt.textContent =
          (a.type === 'shipping' ? 'Доставка' : 'Платёжный') +
          ': ' +
          formatLabel(a);
        select.appendChild(opt);
      });

      wrap.style.display = '';

      select.addEventListener('change', () => {
        const idx = Number(select.value);
        if (Number.isNaN(idx) || !addresses[idx]) return;
        applyAddress(addresses[idx]);
      });
    } catch (e) {
      console.error('checkoutAddresses failed', e);
    }
  };

  loadAddresses();
};

export default checkoutAddresses;
