const quantity = () => {
  const clamp = (v) => Math.max(1, Number.parseInt(String(v ?? 1), 10) || 1);

  document.querySelectorAll('[data-qty-wrap]').forEach((wrap) => {
    const dec = wrap.querySelector('[data-qty-dec]');
    const inc = wrap.querySelector('[data-qty-inc]');
    const count = wrap.querySelector('[data-qty-count]');
    const input = wrap.querySelector('[data-qty-input]');
    if (!dec || !inc || !count || !input) return;

    const setVal = (v) => {
      const next = clamp(v);
      count.textContent = String(next);
      input.value = String(next);
    };

    dec.addEventListener('click', (e) => {
      e.preventDefault();
      setVal(Number(input.value) - 1);
    });

    inc.addEventListener('click', (e) => {
      e.preventDefault();
      setVal(Number(input.value) + 1);
    });
  });
}

export default quantity