const drop = () => {
  const closeAll = () => {
    document.querySelectorAll(".dropBtn").forEach((btn) => {
      const dropdown = btn.querySelector(".drop");
      const img = btn.querySelector(".iconLink");
      if (img) img.classList.remove("active");
      if (dropdown) dropdown.classList.remove("active");
    });
  };

  document.addEventListener("click", (e) => {
    const target = e.target;
    if (!(target instanceof Element)) return;

    const btn = target.closest(".dropBtn");

    if (!btn) {
      closeAll();
      return;
    }

    const dropdown = btn.querySelector(".drop");
    const img = btn.querySelector(".iconLink");

    const isOpen = dropdown ? dropdown.classList.contains("active") : false;

    closeAll();

    if (!isOpen) {
      if (img) img.classList.add("active");
      if (dropdown) dropdown.classList.add("active");
    }
  });
};

export default drop;
