<?php
$languages = array(
    "US_en" => "English (United States)",
    "GB_en" => "English (United Kingdom)",
    "CA_en" => "English (Canada)",
    "AU_en" => "English (Australia)",
    "SG_en" => "English (Singapore)",
    "BW_en" => "English (Botswana)",
    "ET_en" => "English (Ethiopia)",
    "GH_en" => "English (Ghana)",
    "ID_en" => "English (Indonesia)",
    "IE_en" => "English (Ireland)",
    "IL_en" => "English (Israel)",
    "KE_en" => "English (Kenya)",
    "LV_en" => "English (Latvia)",
    "MY_en" => "English (Malaysia)",
    "NA_en" => "English (Namibia)",
    "NZ_en" => "English (New Zealand)",
    "NG_en" => "English (Nigeria)",
    "PK_en" => "English (Pakistan)",
    "PH_en" => "English (Philippines)",
    "ZA_en" => "English (South Africa)",
    "TZ_en" => "English (Tanzania)",
    "UG_en" => "English (Uganda)",
    "ZW_en" => "English (Zimbabwe)",
    "ID_id" => "Bahasa Indonesia (Indonesia)",
    "CZ_cs" => "Čeština (Česko)",
    "DE_de" => "Deutsch (Deutschland)",
    "AT_de" => "Deutsch (Österreich)",
    "CH_de" => "Deutsch (Schweiz)",
    "AR_es-419" => "Español (Argentina)",
    "CL_es-419" => "Español (Chile)",
    "CO_es-419" => "Español (Colombia)",
    "CU_es-419" => "Español (Cuba)",
    "US_es-419" => "Español (Estados Unidos)",
    "MX_es-419" => "Español (México)",
    "PE_es-419" => "Español (Perú)",
    "VE_es-419" => "Español (Venezuela)",
    "BE_fr" => "Français (Belgique)",
    "CA_fr" => "Français (Canada)",
    "FR_fr" => "Français (France)",
    "MA_fr" => "Français (Maroc)",
    "SN_fr" => "Français (Sénégal)",
    "CH_fr" => "Français (Suisse)",
    "IT_it" => "Italiano (Italia)",
    "LT_lt" => "Latviešu (Latvija)",
    "HU_hu" => "Magyar (Magyarország)",
    "BE_nl" => "Nederlands (België)",
    "NL_nl" => "Nederlands (Nederland)",
    "NO_no" => "Norsk (Norge)",
    "PL_pl" => "Polski (Polska)",
    "BR_pt-419" => "Português (Brasil)",
    "PT_pt-150" => "Português (Portugal)",
    "RO_ro" => "Română (România)",
    "SK_sk" => "Slovenčina (Slovensko)",
    "SI_sl" => "Slovenščina (Slovenija)",
    "SE_sv" => "Svenska (Sverige)",
    "VN_vi" => "Tiếng Việt (Việt Nam)",
    "TR_tr" => "Türkçe (Türkiye)",
    "GR_el" => "Ελληνικά (Ελλάδα)",
    "BG_bg" => "Български (България)",
    "RU_ru" => "Русский (Россия)",
    "UA_ru" => "Русский (Украина)",
    "RS_sr" => "Српски (Србија)",
    "UA_uk" => "Українська (Україна)",
    "IL_he" => "עברית (ישראל)",
    "AE_ar" => "العربية (الإمارات العربية المتحدة)",
    "SA_ar" => "العربية (المملكة العربية السعودية)",
    "LB_ar" => "العربية (لبنان)",
    "EG_ar" => "العربية (مصر)",
    "IN_mr" => "मराठी (भारत)",
    "IN_hi" => "हिन्दी (भारत)",
    "BD_bn" => "বাংলা (বাংলাদেশ)",
    "IN_ta" => "தமிழ் (இந்தியா)",
    "IN_te" => "తెలుగు (భారతదేశం)",
    "IN_ml" => "മലയാളം (ഇന്ത്യ)",
    "TH_th" => "ไทย (ไทย)",
    "CN_zh-Hans" => "中文 (中国)",
    "TW_zh-Hant" => "中文 (台灣)",
    "HK_zh-Hant" => "中文 (香港)",
    "JP_ja" => "日本語 (日本)",
    "KR_ko" => "한국어 (대한민국)",
);
$robot_init_language = isset($settings['robot_init_language']) ? $settings['robot_init_language']  : 'US_en';
?>
<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Language and Location', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">
        <span class="dropdown-el robot-language-location-selector">
            <?php foreach ( $languages as $key => $value ) : ?>
                <input type="radio" name="robot_init_language" value="<?php echo esc_attr( $key ); ?>" <?php if($key == $robot_init_language){echo 'checked="checked"';} ?> id="<?php echo esc_attr( $key ); ?>">
                <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
            <?php endforeach; ?>
        </span>
    </div>

</div>
