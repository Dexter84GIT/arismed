export default function addToCart() {
  if (!window.jQuery || !window.wc_add_to_cart_params) return;

  const $ = window.jQuery;

  $(document).on("submit", "form.cart", function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    const $form = $(this);
    const $btn = $form.find(".single_add_to_cart_button");

    if ($btn.hasClass("loading")) return false;

    $btn.addClass("loading");

    $.ajax({
      type: "POST",
      url: wc_add_to_cart_params.wc_ajax_url
        .replace("%%endpoint%%", "add_to_cart"),
      data: $form.serialize(),
      success(response) {
        if (response?.fragments) {
          $(document.body).trigger("added_to_cart", [
            response.fragments,
            response.cart_hash,
            $btn,
          ]);
        }
      },
      complete() {
        $btn.removeClass("loading");
      },
    });

    return false;
  });
}
