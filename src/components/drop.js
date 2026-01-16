const drop = () => {
  const closeAll = () => {
    document.querySelectorAll(".dropBtn").forEach((btn) => {
      btn.querySelector(".drop")?.classList.remove("active");
      btn.querySelector(".iconLink")?.classList.remove("active");
    });
  };

  document.addEventListener("click", (e) => {
    const target = e.target;
    if (!(target instanceof Element)) return;

    if (target.closest(".drop")) {
      return;
    }

    const btn = target.closest(".dropBtn");

    if (!btn) {
      closeAll();
      return;
    }

    const dropdown = btn.querySelector(".drop");
    const img = btn.querySelector(".iconLink");

    const isOpen = dropdown?.classList.contains("active");

    closeAll();

    if (!isOpen) {
      dropdown?.classList.add("active");
      img?.classList.add("active");
    }
  });
};

export default drop;
