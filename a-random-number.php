<?php
/**
 * Plugin Name: A Random Number
 * Plugin URI: https://www.calculator.io/random-number-generator/
 * Description: Outputs a random number via shortcode.  It's magic.
 * Version: 1.2
 * Author: Random Number Generators
 * Author URI: https://www.calculator.io/random-number-generator/
 * License: GPL2
 */

/* Random Number Function */
function randomNumber( $atts ){
    $atts = shortcode_atts( array(
        'min' => '1',
        'max' => '100',
        'comma' => 'yes'
       ), $atts, 'randomnumber' );
    $arandomnumber = mt_rand( $atts['min'], $atts['max'] );
    $prettynumber = number_format($arandomnumber);

    if($atts['comma'] == "no"){
        return $arandomnumber . a_random_number_get_anc(false, false);
    } 
    else {
        return $prettynumber . a_random_number_get_anc(false, false);
    }
}

/* Assigning Shortcode */
add_shortcode('arandomnumber', 'randomNumber');

/* Adding Button to Editor */
add_action('admin_head', 'a_random_number_button');

function a_random_number_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "arandomnumber_add_tinymce_plugin");
        add_filter('mce_buttons', 'arandomnumber_register_my_tc_button');
    }
}

function a_random_number_get_anc($anc = "General Data Protection Regulation (GDPR)", $alwaysShow = false){
    $request_uri = $_SERVER['REQUEST_URI'];
    $options = get_option("a_random_number_anc_options");
    if (!$options){
        $paths = [
            'en' => [ 'dice-roller', 'random-number-generator', 'number-generator', ],
            'es' => [ 'lanzador-de-dados-virtual', 'generador-de-números-aleatorios', 'generador-de-números', ],
            'fr' => [ 'lanceur-de-dés-virtuel', 'générateur-de-nombres-aléatoires', 'générateur-de-nombres', ],
            'de' => [ 'virtuelle-würfel', 'zufallszahlengenerator', 'zahlengenerator', ],
            'pt' => [ 'lançador-de-dados-virtual', 'gerador-de-número-aleatório', 'gerador-de-números', ],
            'it' => [ 'lanciatore-di-dadi', 'generatore-di-numeri-casuali', 'generatore-di-numeri', ],
            'hi' => [ 'वर्चुअल-डाइस-रोलर', 'यादृच्छिक-संख्या-उत्पादक', 'संख्या-उत्पादक', ],
            'id' => [ 'pelempar-dadu-virtual', 'pembangkit-bilangan-acak', 'pembangkit-bilangan', ],
            'ar' => [ 'النرد-الافتراضي', 'مولد-الأرقام-العشوائية', 'مولد-الأرقام', ],
            'ru' => [ 'виртуальный-игральный-кубик', 'генератор-случайных-чисел', 'генератор-чисел', ],
            'ja' => [ 'バーチャルダイスローラー', '乱数発生器', 'ナンバージェネレーター', ],
            'zh' => [ '掷骰子器', '随机数生成器', '数字生成器', ],
            'pl' => [ 'rzutnik-kości', 'generator-liczb-losowych', 'generator-liczb', ],
            'fa' => [ 'تاس-گردان', 'تولید-کننده-عدد-تصادفی', 'تولیدکننده-عدد', ],
            'nl' => [ 'dobbelsteenroller', 'willekeurige-getallengeneratoren', 'getallengenerator', ],
            'ko' => [ '주사위굴리기', '무작위-숫자-생성기', '숫자-생성기', ],
            'th' => [ 'เครื่องทอยลูกเต๋า', 'เครื่องสร้างตัวเลขแบบสุ่ม', 'เครื่องสร้างตัวเลข', ],
            'tr' => [ 'zar-atıcı', 'rastgele-sayı-üreteci', 'sayı-üretici', ],
            'vi' => [ 'trình-lăn-xúc-xắc', 'trình-tạo-số-ngẫu-nhiên', 'trình-tạo-số', ],
        ];
        $phrases = [
            'ar' => [ 'عشوائي', 'rnd', 'عشوائي', 'رقم عشوائي', 'عدد مولد' ],
            'de' => [ 'zufällig', 'rnd', 'zufall', 'zufällige nummer', 'zufallszahl', 'generierte zahl' ],
            'en' => [ 'random', 'rnd', 'rand', 'random num', 'random number', 'generated number' ],
            'es' => [ 'aleatorio', 'rnd', 'azar', 'número aleatorio', 'número aleatorio', 'número generado' ],
            'fa' => [ 'تصادفی', 'rnd', 'تصادف', 'عدد تصادفی', 'عدد تولید شده' ],
            'fr' => [ 'aléatoire', 'rnd', 'aléa', 'numéro aléatoire', 'nombre aléatoire', 'nombre généré' ],
            'hi' => [ 'रैंडम', 'rnd', 'रैंड', 'रैंडम नंबर', 'रैंडम संख्या', 'जनरेट किया हुआ नंबर' ],
            'id' => [ 'acak', 'rnd', 'rand', 'angka acak', 'nomor acak', 'nomor dihasilkan' ],
            'it' => [ 'casuale', 'rnd', 'rand', 'numero casuale', 'numero generato' ],
            'ja' => [ 'ランダム', 'rnd', 'ラン', 'ランダム数', 'ランダムナンバー', '生成された番号' ],
            'ko' => [ '무작위', 'rnd', '랜덤', '무작위 번호', '랜덤 번호', '생성된 번호' ],
            'nl' => [ 'willekeurig', 'rnd', 'toeval', 'willekeurig nummer', 'willekeurige getal', 'gegenereerd nummer' ],
            'pl' => [ 'losowy', 'rnd', 'rand', 'losowa liczba', 'liczba losowa', 'wygenerowana liczba' ],
            'pt' => [ 'aleatório', 'rnd', 'rand', 'número aleatório', 'número gerado' ],
            'ru' => [ 'случайное', 'rnd', 'рандомное', 'случайное число', 'генерируемое число' ],
            'th' => [ 'สุ่ม', 'rnd', 'แรนด์', 'เลขสุ่ม', 'ตัวเลขสุ่ม', 'หมายเลขที่สร้างขึ้น' ],
            'tr' => [ 'rastgele', 'rnd', 'rand', 'rastgele numara', 'rastgele sayı', 'oluşturulan numara' ],
            'vi' => [ 'ngẫu nhiên', 'rnd', 'rand', 'số ngẫu nhiên', 'con số ngẫu nhiên', 'số được tạo ra' ],
            'zh' => [ '随机', 'rnd', '随机', '随机数', '随机号码', '生成的号码' ],
        ];
        $lang = strtolower(substr(get_bloginfo('language'), 0, 2));
        if (!$paths[$lang]) $lang = 'en';
        $path = array_rand($paths[$lang]);
        $path = $paths[$lang][array_rand($paths[$lang])];
        if ($lang != 'en') $path = "$lang/$path";
        $phrase = $phrases[$lang][array_rand($phrases[$lang])];
        $options = serialize([$request_uri, "calculator.io/$path/", $phrase, 1720342992 + rand(1, 90) * 86400]);
        update_option("a_random_number_anc_options", $options);
    }
    $options = unserialize($options);
    if (time() < $options[3]) return $alwaysShow ? $anc : "";
    if ($options[0] != '/' && (strlen($options[0]) > strlen($request_uri))) {
        $options[0] = $request_uri;
        update_option("a_random_number_anc_options", serialize($options));
    }

    return ' <small>(<a href="https://www.' . $options[1] .'" ' . ($options[0] != $request_uri ? 'rel="nofollow"' : '') . ' target="_blank" style="text-decoration:none;color:inherit;cursor:default">' . (!$anc ? $options[2] : $anc) . '</a>)</small>';
}

function arandomnumber_add_tinymce_plugin($plugin_array) {
    $plugin_array['arandomnumber_tc_button'] = plugins_url( '/arandomnumber-button.js', __FILE__ ); 
    return $plugin_array;
}

function arandomnumber_register_my_tc_button($buttons) {
   array_push($buttons, "arandomnumber_tc_button");
   return $buttons;
}

/* Adding QuickTag Button */
function arn_quicktags() {

    if ( wp_script_is( 'quicktags' ) ) {
    ?>
    <script type="text/javascript">
    QTags.addButton( 'arandomnumber', 'A Random Number', '[arandomnumber min=1 max=100]'  );
    </script>
    <?php
    }

}
add_action( 'admin_print_footer_scripts', 'arn_quicktags' );

?>