import accordeon from "./components/accordeon";
import slider from "./components/slider.mjs";
import mobileMenu from "./components/mobileMenu.mjs";
import tabs from "./components/tabs.mjs";
import drop from "./components/drop";
import quantity from "./components/quantity";

// woo

import goodsAddToCart from "./components/goodsAddToCart.mjs";
import { initMiniCartSync } from "./components/miniCartSync.mjs";

accordeon();
slider();
mobileMenu();
tabs();
drop();
quantity();

// woo

goodsAddToCart();
initMiniCartSync();
