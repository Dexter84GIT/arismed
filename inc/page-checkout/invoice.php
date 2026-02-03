<form action="" method="post" id="checkoutForm" class="checkoutForm df fdc gap60">

    <div class="block top df fdc gap30">
        <h2 class="sectionTitle druk">Сформировать счет</h2>
    </div>

    <div class="block seller df fdc gap30">
        <h3 class="druk">Поставщик (Исполнитель):</h3>
        <div class="block df fdc gap20">

            <p class="label">ООО “Арис-Мед", ИНН 1827006226, КПП 231501001, Краснодарский край, г.
                Новороссийск, ул. Панорамная, д. 8, Тел.: +7 (963) 900-42-08</p>
        </div>
    </div>

    <div class="block customer df fdc gap30">
        <h3 class="druk">Покупатель (Заказчик):</h3>

        <div class="block df fdc gap20">
            <p class="label">Укажите реквизиты организации</p>


            <div class="row">
                <div class="field">
                    <input type="text" maxlength="50" class="textInput" name="organization" placeholder="Организация">
                </div>
            </div>

            <div class="row row2 df aic gap20">
                <div class="field">
                    <input type="text" maxlength="12" class="textInput" name="inn" placeholder="ИНН">
                </div>
                <div class="field">
                    <input type="text" maxlength="9" class="textInput" name="kpp" placeholder="КПП">
                </div>
            </div>

            <div class="row row3 df aic gap20">
                <div class="field">
                    <input type="text" maxlength="120" class="textInput" name="organization_address"
                        placeholder="Адрес">
                </div>
            </div>
        </div>
    </div>

    <div class="block df fdc gap5">
        <p class="disclaimer">
            Оплата данного счета означает согласие с условиями поставки товара.
        </p>
        <p>
            Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.
        </p>
        <p>
            Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и
            паспорта.
        </p>
    </div>

    <input type="hidden" name="nonce" value="<?php echo esc_attr(
        wp_create_nonce('arismed_invoice_submit')
    ); ?>">



    <div class="block df aic gap60 jcfe submitRow">
        <p class="notice" id="errorNotice"></p>
        <button type="submit" class="druk submit df aic gap10">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.25 6.75H11.25V2.25H6.75V6.75H3.75L9 12L14.25 6.75ZM8.25 8.25V3.75H9.75V8.25H10.6275L9 9.8775L7.3725 8.25H8.25ZM3.75 13.5H14.25V15H3.75V13.5Z" />
            </svg>
            <p>Сформировать счет</p>
        </button>
    </div>

</form>