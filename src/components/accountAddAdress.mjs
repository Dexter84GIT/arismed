const addAdressForm = () => {
  const form = document.getElementById('addAddressForm');
  const addBtn = document.getElementById('addShippingAddress');
  const list = document.querySelector('#adress .content');

  if (!form || !list) return;

  const esc = (s) =>
    String(s ?? '').replace(/[&<>"']/g, (m) => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    }[m]));

  const buildText = (a) =>
    [
      a.address || a.adress || '',
      a.building ? `корп. ${a.building}` : '',
      a.entrance ? `под. ${a.entrance}` : '',
      a.floor ? `этаж ${a.floor}` : '',
      a.city || '',
      a.state || '',
      a.postcode || a.post || '',
    ].filter(Boolean).join(', ');

  const renderRow = (addr) => {
    const row = document.createElement('div');
    row.className = 'row df aic jcsb';
    row.dataset.addressId = addr.id ?? '';
    row.innerHTML = `
      <div class="df fdc gap5">
        <p>${esc(buildText(addr))}</p>
      </div>
      <div class="controls df aic gap20"> 
        <button type="button" class="edit" data-edit-address="${esc(addr.id ?? '')}">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12.1127 3.27378L11.3993 2.56045L7.69935 6.26045V6.98045H8.41935L12.1127 3.27378ZM10.666 1.82712L11.526 0.967116C11.6184 0.873589 11.7284 0.799333 11.8498 0.748652C11.9711 0.697972 12.1012 0.671875 12.2327 0.671875C12.3642 0.671875 12.4943 0.697972 12.6156 0.748652C12.7369 0.799333 12.847 0.873589 12.9393 0.967116L13.706 1.73378C14.0993 2.12712 14.0993 2.76045 13.706 3.14712L13.2527 3.60045L13.2393 3.61378L12.8527 4.00045L8.85268 8.00045H6.66602V5.82712L10.666 1.82712ZM9.14602 1.46045L8.77935 1.82712L7.93268 2.67378C5.73268 2.70712 3.99935 4.40712 3.99935 6.80712C3.99935 8.36712 5.29935 10.4338 7.99935 12.9004C10.6993 10.4338 11.9993 8.37378 11.9993 6.80712V6.74045L13.1993 5.54045C13.286 5.94045 13.3327 6.36712 13.3327 6.80712C13.3327 9.02045 11.5527 11.6404 7.99935 14.6738C4.44602 11.6404 2.66602 9.02045 2.66602 6.80712C2.66602 3.48712 5.19935 1.34045 7.99935 1.34045C8.38602 1.34045 8.77268 1.38045 9.14602 1.46045Z" fill="#2D3A4F"/>
          </svg>
        </button>
        <button type="button" class="delete" data-delete-address="${esc(addr.id ?? '')}">
          <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" fill="#2D3A4F"/>
          </svg>
        </button>
      </div>
    `;
    return row;
  };

  const removeEmptyState = () => {
    const p = list.querySelector('p');
    if (p && /Адресов пока нет/i.test(p.textContent || '')) p.remove();
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const fd = new FormData(form);
    fd.append('action', 'arismed_add_address');

    let json;
    try {
      const res = await fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        credentials: 'same-origin',
        body: fd,
      });
      json = await res.json();
    } catch (err) {
      console.error(err);
      alert('Ошибка сохранения адреса');
      return;
    }

    if (!json?.success) {
      console.error(json);
      alert(json?.data?.message || 'Ошибка сохранения адреса');
      return;
    }

    const addr =
      json?.data?.address ||
      json?.data?.addr ||
      json?.data?.saved_address ||
      null;

    if (!addr) {
      console.error('No address in response', json);
      alert('Адрес сохранён, но сервер не вернул данные адреса');
      return;
    }

    removeEmptyState();
    list.prepend(renderRow(addr));

    form.reset();
    form.classList.remove('open');
  });

  addBtn?.addEventListener('click', () => {
    form.classList.toggle('open');
  });
};

export default addAdressForm;
