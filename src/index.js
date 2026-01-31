import accordeon from "./components/accordeon";
import slider from "./components/slider.mjs";
import mobileMenu from "./components/mobileMenu.mjs";
import tabs from "./components/tabs.mjs";
import drop from "./components/drop";
import quantity from "./components/quantity";
import cookiePolicy from "./components/cookiePolicy.mjs";
import headerSearch from "./components/headerSearch.mjs";
import initDocsSearch from "./components/docsSearch.mjs";
import checkout from "./components/checkoutValidate.mjs";
import initDevPay from "./components/checkoutSubmit.mjs";
import checkoutAddresses from "./components/checkoutSelectAdress.mjs";

// аккаунт
import accountSortOrders from "./components/accountSortOrders.mjs";
import addAdressForm from "./components/accountAddAdress.mjs";
import accountDeleteAdress from "./components/accountDeleteAdress.mjs";
// woo
import goodsAddToCart from "./components/goodsAddToCart.mjs";
import { initMiniCartSync } from "./components/miniCartSync.mjs";

document.addEventListener('DOMContentLoaded', () => {
    cookiePolicy();
    accordeon();
    slider();
    mobileMenu();
    tabs();
    drop();
    quantity();
    headerSearch();
    initDocsSearch();
    checkout();
    initDevPay();
    checkoutAddresses();

    // аккаунт
    accountSortOrders('');
    addAdressForm();
    // accountDeleteAdress();

    // woo
    goodsAddToCart();
    initMiniCartSync();
})
