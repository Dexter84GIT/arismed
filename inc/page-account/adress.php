<div class="tabContent df fdc gap30" id="adress">
    <div class="top df fdc gap20">
        <h2 class="sectionTitle druk">Адреса доставки</h2>
        <div class="content df fdc gap20">

            <?php if (!is_user_logged_in()) : ?>
              <p>Войдите, чтобы управлять адресами</p>
            <?php else : ?>

            <?php
                $customer_id = get_current_user_id();
                $addresses = get_user_meta($customer_id, 'arismed_saved_addresses', true);
                if (!is_array($addresses)) $addresses = [];
            ?>

            <?php if (!$addresses) : ?>
                    <p>Адресов пока нет</p>
                <?php else : ?>
                    <?php foreach ($addresses as $addr) : ?>
                        <div class="row df aic jcsb" data-address-id="<?php echo esc_attr($addr['id']); ?>">
                            <p>
                                <?php
                                    echo esc_html(implode(', ', array_filter([
                                      $addr['address'] ?? '',
                                      $addr['building'] ? 'корп. ' . $addr['building'] : '',
                                      $addr['entrance'] ? 'под. ' . $addr['entrance'] : '',
                                      $addr['floor'] ? 'этаж ' . $addr['floor'] : '',
                                      $addr['city'] ?? '',
                                      $addr['state'] ?? '',
                                      $addr['postcode'] ?? '',
                                    ])));
                                ?>
                            </p>
                    
                            <div class="controls df aic gap15">
                                <button
                                  type="button"
                                  class="edit"
                                  data-edit-address="<?php echo esc_attr($addr['id']); ?>"
                                >
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.1127 3.27378L11.3993 2.56045L7.69935 6.26045V6.98045H8.41935L12.1127 3.27378ZM10.666 1.82712L11.526 0.967116C11.6184 0.873589 11.7284 0.799333 11.8498 0.748652C11.9711 0.697972 12.1012 0.671875 12.2327 0.671875C12.3642 0.671875 12.4943 0.697972 12.6156 0.748652C12.7369 0.799333 12.847 0.873589 12.9393 0.967116L13.706 1.73378C14.0993 2.12712 14.0993 2.76045 13.706 3.14712L13.2527 3.60045L13.2393 3.61378L12.8527 4.00045L8.85268 8.00045H6.66602V5.82712L10.666 1.82712ZM9.14602 1.46045L8.77935 1.82712L7.93268 2.67378C5.73268 2.70712 3.99935 4.40712 3.99935 6.80712C3.99935 8.36712 5.29935 10.4338 7.99935 12.9004C10.6993 10.4338 11.9993 8.37378 11.9993 6.80712V6.74045L13.1993 5.54045C13.286 5.94045 13.3327 6.36712 13.3327 6.80712C13.3327 9.02045 11.5527 11.6404 7.99935 14.6738C4.44602 11.6404 2.66602 9.02045 2.66602 6.80712C2.66602 3.48712 5.19935 1.34045 7.99935 1.34045C8.38602 1.34045 8.77268 1.38045 9.14602 1.46045Z" fill="#2D3A4F"/>
                                    </svg>
                                </button>
                                <button
                                  type="button"
                                  class="delete"
                                  data-delete-address="<?php echo esc_attr($addr['id']); ?>"
                                >   
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" fill="#2D3A4F"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>    
        </div>

        <form class="form checkoutForm" id="addAddressForm">
            <div class="row">
                <div class="field">
                    <input type="text" maxlength="50" class="textInput" name="address" placeholder="Адрес">
                </div>
            </div>
            <div class="row row3 df aic gap20">
                <div class="field">
                    <input type="text" maxlength="4" class="textInput" name="building" placeholder="Корпус">
                </div>
                <div class="field">
                    <input type="text" maxlength="4" class="textInput" name="entrance" placeholder="Подъезд">
                </div>
                <div class="field">
                    <input type="text" maxlength="4" class="textInput" name="floor" placeholder="Этаж">
                </div>
            </div>
            <div class="row row2 df aic gap20">
                <div class="field">
                    <input type="text" maxlength="30" class="textInput" name="city" placeholder="Населенный пункт">
                </div>
                <div class="field">
                    <input type="text" maxlength="30" class="textInput" name="state" placeholder="Область, район">
                </div>
            </div>
            <div class="row df aic gap20">
                <div class="field">
                    <input type="text" maxlength="6" name="post" class="textInput" placeholder="Почтовый индекс">
                </div>
            </div>
            <button type="submit" class="accountBtn">Сохранить адрес</button>
        </form>

        <button type="button" class="accountBtn df aic gap10" id="addShippingAddress" data-add-address="shipping">
            <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0V2.25H14.25V3.75H12V6H10.5V3.75H8.25V2.25H10.5V0H12ZM6 9C5.175 9 4.5 8.325 4.5 7.5C4.5 6.675 5.175 6 6 6C6.825 6 7.5 6.675 7.5 7.5C7.5 8.325 6.825 9 6 9ZM6.75 1.545V3.06C6.50191 3.0208 6.25117 3.00074 6 3C3.4875 3 1.5 4.9275 1.5 7.65C1.5 9.405 2.9625 11.73 6 14.505C9.0375 11.73 10.5 9.4125 10.5 7.65V7.5H12V7.65C12 10.14 9.9975 13.0875 6 16.5C2.0025 13.0875 0 10.14 0 7.65C0 3.915 2.85 1.5 6 1.5C6.255 1.5 6.5025 1.515 6.75 1.545Z" fill="white"/>
            </svg>
            <p class="druk">Добавить адрес</p>
        </button>
    </div>
</div>

