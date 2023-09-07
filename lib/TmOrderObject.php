<?php


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//Generates json for server-side api and javascript firePurchaseEvent
//Returns object containing both json strings
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
function setup_thoughtmetric_order($order)
{
    //get order data
    $order_id = (string) $order->getRealOrderId();
    $purchase_total = (float) $order->getGrandTotal();
    $shipping_total = (float) $order->getShippingAmount();
    $tax = (float) $order->getTaxAmount();
    $currency = $order->getOrderCurrencyCode();
    $time_of_purchase = date('c', strtotime($order->getCreatedAt()));
    $coupon_code = $order->getCouponCode();

    //Loop Over Order Items and add to products array
    $js_products_array = array();
    $item_quantity = 0;
    foreach ($order->getAllVisibleItems() as $item) {
        $js_prod_item = new stdClass();
        /* $product_id = (string) $item->getProductId(); */
        /* $js_prod_item->productId = $product_id; */

        /* $variation_id = (string) $item->getSku(); */
        /* $js_prod_item->variantId = $variation_id; */

        $name = $item->getName();
        $js_prod_item->product_name = $name;

        $quantity = (int) $item->getQtyOrdered();
        $js_prod_item->quantity = $quantity;
        $item_quantity += $quantity;

        $price = (float) $item->getPrice();
        $js_prod_item->unit_price = $price;

        $js_products_array[] = $js_prod_item;
    }

    $data = array(
             'transaction_id'       => (string) $order->getRealOrderId(),
             'status' => (string)$order->getStatus(),
             'total_price'    => (float) $order->getGrandTotal(),
             'subtotal_price' => (float)$order->getSubtotal(),
             'currency'      => $order->getStoreCurrencyCode(),
             'orderCurrency'      => $order->getOrderCurrencyCode(),
             'total_tax'      => (float) $order->getTaxAmount(),
             'total_shipping' => (float)$order->getShippingAmount(),
             'total_discounts' => (float)$order->getDiscountAmount(),
             'discount_codes' => array($order->getCouponCode()),
             'item_quantity' => $item_quantity,
             'items'      => $js_products_array,
             'platform' => 'magento',
         );


    //convert to json format
    return json_encode($data);
}


function setup_thoughtmetric_customer($order)
{
    //get customer data
    $address1 = $order->getShippingAddress()->getStreet()[0];
    $address2 = $order->getShippingAddress()->getStreet()[1] ?? "";
    $city = $order->getShippingAddress()->getCity();
    $state = $order->getShippingAddress()->getRegionCode() ?? "N/A";
    $zip = $order->getShippingAddress()->getPostcode();
    $country_code_mapping = ["AF" => "AFG", "AX" => "ALA", "AL" => "ALB", "DZ" => "DZA", "AS" => "ASM", "AD" => "AND", "AO" => "AGO", "AI" => "AIA", "AQ" => "ATA", "AG" => "ATG", "AR" => "ARG", "AM" => "ARM", "AW" => "ABW", "AU" => "AUS", "AT" => "AUT", "AZ" => "AZE", "BS" => "BHS", "BH" => "BHR", "BD" => "BGD", "BB" => "BRB", "BY" => "BLR", "BE" => "BEL", "BZ" => "BLZ", "BJ" => "BEN", "BM" => "BMU", "BT" => "BTN", "BO" => "BOL", "BQ" => "BES", "BA" => "BIH", "BW" => "BWA", "BV" => "BVT", "BR" => "BRA", "IO" => "IOT", "BN" => "BRN", "BG" => "BGR", "BF" => "BFA", "BI" => "BDI", "CV" => "CPV", "KH" => "KHM", "CM" => "CMR", "CA" => "CAN", "KY" => "CYM", "CF" => "CAF", "TD" => "TCD", "CL" => "CHL", "CN" => "CHN", "CX" => "CXR", "CC" => "CCK", "CO" => "COL", "KM" => "COM", "CG" => "COG", "CD" => "COD", "CK" => "COK", "CR" => "CRI", "CI" => "CIV", "HR" => "HRV", "CU" => "CUB", "CW" => "CUW", "CY" => "CYP", "CZ" => "CZE", "DK" => "DNK", "DJ" => "DJI", "DM" => "DMA", "DO" => "DOM", "EC" => "ECU", "EG" => "EGY", "SV" => "SLV", "GQ" => "GNQ", "ER" => "ERI", "EE" => "EST", "SZ" => "SWZ", "ET" => "ETH", "FK" => "FLK", "FO" => "FRO", "FJ" => "FJI", "FI" => "FIN", "FR" => "FRA", "GF" => "GUF", "PF" => "PYF", "TF" => "ATF", "GA" => "GAB", "GM" => "GMB", "GE" => "GEO", "DE" => "DEU", "GH" => "GHA", "GI" => "GIB", "GR" => "GRC", "GL" => "GRL", "GD" => "GRD", "GP" => "GLP", "GU" => "GUM", "GT" => "GTM", "GG" => "GGY", "GN" => "GIN", "GW" => "GNB", "GY" => "GUY", "HT" => "HTI", "HM" => "HMD", "VA" => "VAT", "HN" => "HND", "HK" => "HKG", "HU" => "HUN", "IS" => "ISL", "IN" => "IND", "ID" => "IDN", "IR" => "IRN", "IQ" => "IRQ", "IE" => "IRL", "IM" => "IMN", "IL" => "ISR", "IT" => "ITA", "JM" => "JAM", "JP" => "JPN", "JE" => "JEY", "JO" => "JOR", "KZ" => "KAZ", "KE" => "KEN", "KI" => "KIR", "KP" => "PRK", "KR" => "KOR", "KW" => "KWT", "KG" => "KGZ", "LA" => "LAO", "LV" => "LVA", "LB" => "LBN", "LS" => "LSO", "LR" => "LBR", "LY" => "LBY", "LI" => "LIE", "LT" => "LTU", "LU" => "LUX", "MO" => "MAC", "MG" => "MDG", "MW" => "MWI", "MY" => "MYS", "MV" => "MDV", "ML" => "MLI", "MT" => "MLT", "MH" => "MHL", "MQ" => "MTQ", "MR" => "MRT", "MU" => "MUS", "YT" => "MYT", "MX" => "MEX", "FM" => "FSM", "MD" => "MDA", "MC" => "MCO", "MN" => "MNG", "ME" => "MNE", "MS" => "MSR", "MA" => "MAR", "MZ" => "MOZ", "MM" => "MMR", "NA" => "NAM", "NR" => "NRU", "NP" => "NPL", "NL" => "NLD", "NC" => "NCL", "NZ" => "NZL", "NI" => "NIC", "NE" => "NER", "NG" => "NGA", "NU" => "NIU", "NF" => "NFK", "MK" => "MKD", "MP" => "MNP", "NO" => "NOR", "OM" => "OMN", "PK" => "PAK", "PW" => "PLW", "PS" => "PSE", "PA" => "PAN", "PG" => "PNG", "PY" => "PRY", "PE" => "PER", "PH" => "PHL", "PN" => "PCN", "PL" => "POL", "PT" => "PRT", "PR" => "PRI", "QA" => "QAT", "RE" => "REU", "RO" => "ROU", "RU" => "RUS", "RW" => "RWA", "BL" => "BLM", "SH" => "SHN", "KN" => "KNA", "LC" => "LCA", "MF" => "MAF", "PM" => "SPM", "VC" => "VCT", "WS" => "WSM", "SM" => "SMR", "ST" => "STP", "SA" => "SAU", "SN" => "SEN", "RS" => "SRB", "SC" => "SYC", "SL" => "SLE", "SG" => "SGP", "SX" => "SXM", "SK" => "SVK", "SI" => "SVN", "SB" => "SLB", "SO" => "SOM", "ZA" => "ZAF", "GS" => "SGS", "SS" => "SSD", "ES" => "ESP", "LK" => "LKA", "SD" => "SDN", "SR" => "SUR", "SJ" => "SJM", "SE" => "SWE", "CH" => "CHE", "SY" => "SYR", "TW" => "TWN", "TJ" => "TJK", "TZ" => "TZA", "TH" => "THA", "TL" => "TLS", "TG" => "TGO", "TK" => "TKL", "TO" => "TON", "TT" => "TTO", "TN" => "TUN", "TR" => "TUR", "TM" => "TKM", "TC" => "TCA", "TV" => "TUV", "UG" => "UGA", "UA" => "UKR", "AE" => "ARE", "GB" => "GBR", "US" => "USA", "UM" => "UMI", "UY" => "URY", "UZ" => "UZB", "VU" => "VUT", "VE" => "VEN", "VN" => "VNM", "VG" => "VGB", "VI" => "VIR", "WF" => "WLF", "EH" => "ESH", "YE" => "YEM", "ZM" => "ZMB", "ZW" => "ZWE"];
    $country_code = $country_code_mapping[$order->getShippingAddress()->getCountryId()];


    $data = array (
            'email' => $order->getCustomerEmail(),
            'first_name' => $order->getCustomerName(),
            'address1' => $address1,
            'address2' => $address2,
            'city' => $city,
            'state' => $state,
            'country' => $country_code,
            'zip' => $zip,
            'platform' => 'magento',
          );

    return json_encode($data);
}

function setup_thoughtmetric_customer_ID($order)
{
    $data = $order->getCustomerEmail();
    return json_encode($data);
}

function setup_thoughtmetric_is_duplicate($order)
{
    //get order data
    $time_of_purchase = date('c', strtotime($order->getCreatedAt()));

    // Convert the date strings to DateTime objects
    $timeOfPurchaseDate = new DateTime($time_of_purchase);
    $oneDayAgo = (new DateTime("now"))->modify("-1 day");

    if($timeOfPurchaseDate < $oneDayAgo) {
      return true;
    } else{
      return false;
    }
}
