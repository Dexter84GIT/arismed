import accordeon from "./components/accordeon";
import slider from "./components/slider.mjs";
import mobileMenu from "./components/mobileMenu.mjs";
import tabs from "./components/tabs.mjs";
import drop from "./components/drop";
import quantity from "./components/quantity";

// woo
import addToCart from "./components/add-to-cart.mjs";
import { initMiniCartSync } from './components/miniCartSync.mjs'
import { initMiniCartRemove } from "./components/deleteFromMiniCart.mjs";
import quantityCart from "./components/quantityCart.mjs";

accordeon()
slider()
mobileMenu()
tabs()
drop()
quantity()

addToCart()
initMiniCartSync()
initMiniCartRemove()
quantityCart()