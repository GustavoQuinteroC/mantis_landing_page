<?php
$NOINDEX = false;

// Contacto institucional. Se define antes de cargar el kit: el kit solo
// define estas constantes si no existen.
define('ALKES_MAIL',    'soporte@alkes.mx');
define('ALKES_TEL',     '+523339460097');
define('ALKES_TEL_TXT', '333 946 0097');


if (!defined('MA_SITIO'))     { define('MA_SITIO', 'https://mantis.alkes.mx'); }
if (!defined('MA_WHATSAPP'))  { define('MA_WHATSAPP', 'https://wa.me/523339460097'); }

/** Redes oficiales de Alkes. */
function ma_redes()
{
    return array(
        array('n' => 'Facebook',  'u' => 'https://www.facebook.com/alkes.mx'),
        array('n' => 'Instagram', 'u' => 'https://www.instagram.com/alkes.mx'),
        array('n' => 'LinkedIn',  'u' => 'https://www.linkedin.com/company/alkes-mx'),
        array('n' => 'TikTok',    'u' => 'https://www.tiktok.com/@alkes.mx'),
    );
}

/** Mensaje de partida para pedir una demostración guiada. */
function ma_url_agendar()
{
    return 'mailto:' . ALKES_MAIL . '?subject=' . rawurlencode('Demostración guiada de Mantis');
}

/** Logo en dos versiones; el CSS muestra la que corresponde al tema. */
function ma_logo($px = 34)
{
    $px = (int) $px;
    return '<img class="logo logo--claro" src="assets/img/logo.png" alt="" width="' . $px . '" height="' . $px . '">'
         . '<img class="logo logo--oscuro" src="assets/img/logo-blanco.png" alt="" width="' . $px . '" height="' . $px . '">';
}

/**
 * Captura dentro de una ventana de navegador. Si existe la versión oscura se
 * pintan las dos y el CSS muestra la del tema activo.
 */
function ma_ventana($archivo, $alt, $w, $h, $url = 'app.mantis.alkes.mx', $carga = 'lazy')
{
    $existe = is_file(__DIR__ . '/' . $archivo);
    $osc    = preg_replace('/\.(png|jpe?g|webp)$/i', '-oscuro.$1', $archivo);
    $hayOsc = $osc !== $archivo && is_file(__DIR__ . '/' . $osc);
    $o  = '<figure class="win">';
    $o .= '<div class="win__bar"><span class="win__dots" aria-hidden="true"><i></i><i></i><i></i></span>';
    $o .= '<span class="win__url">' . a_e($url) . '</span></div>';
    $o .= '<div class="win__shot" style="--ar:' . (int) $w . ' / ' . (int) $h . '">';
    if ($existe) {
        $o .= '<img src="' . a_e($archivo) . '" alt="' . a_e($alt) . '"' . ($hayOsc ? ' data-tema-img="claro"' : '')
            . ' width="' . (int) $w . '" height="' . (int) $h . '" loading="' . a_e($carga) . '" decoding="async">';
        if ($hayOsc) {
            $o .= '<img src="' . a_e($osc) . '" alt="' . a_e($alt) . '" data-tema-img="oscuro"'
                . ' width="' . (int) $w . '" height="' . (int) $h . '" loading="' . a_e($carga) . '" decoding="async">';
        }
    } else {
        $o .= '<div class="win__falta"><b>Captura pendiente</b><code>' . a_e($archivo) . '</code></div>';
    }
    return $o . '</div></figure>';
}

/** Tarifa por usuario (tarifa.json): el descuento sube 1% por cada usuario adicional hasta el tope, y aplica al precio de todos. */
function ma_tarifa()
{
    static $t = null;
    if ($t !== null) { return $t; }
    $t = array('anual_meses' => 10, 'base' => 100, 'paso' => 0.01, 'tope_descuento' => 0.5);
    $f = __DIR__ . '/tarifa.json';
    if (is_file($f)) {
        $j = json_decode((string) @file_get_contents($f), true);
        if (is_array($j) && !empty($j['base'])) { $t = $j + $t; }
    }
    return $t;
}

/** Mensualidad para $n usuarios: descuento de (n-1) × paso, con tope; el precio por usuario va a centavos. */
function ma_calcular($n)
{
    $n = max(1, (int) $n);
    $t = ma_tarifa();
    $d = min((float) $t['tope_descuento'], ($n - 1) * (float) $t['paso']);
    $precio = round((float) $t['base'] * (1 - $d), 2);
    return array('total' => round($n * $precio, 2), 'precio' => $precio, 'n' => $n, 'descuento' => $d);
}

/** Precio por usuario más alto (calculadora y checkout; la publicidad no lo cita). */
function ma_tarifa_max()
{
    return (float) ma_tarifa()['base'];
}

/** Precio por usuario más bajo (el que se anuncia). */
function ma_tarifa_min()
{
    $t = ma_tarifa();
    return round((float) $t['base'] * (1 - (float) $t['tope_descuento']), 2);
}

/** Usuario desde el que aplica el descuento máximo. */
function ma_tarifa_tope_usuarios()
{
    $t = ma_tarifa();
    return (int) ceil((float) $t['tope_descuento'] / (float) $t['paso']) + 1;
}

/** Sección de precios: explicación y calculadora. Sin JS se ve la explicación. */
function ma_tarifas_html($url_base)
{
    $t = ma_tarifa();
    $meses = (int) $t['anual_meses'];
    $pct = rtrim(rtrim(number_format((float) $t['paso'] * 100, 2, '.', ''), '0'), '.');
    $top = (int) round((float) $t['tope_descuento'] * 100);
    $h  = '<div class="tarifa" data-tarifa="' . a_e(json_encode($t, JSON_UNESCAPED_UNICODE)) . '" data-url="' . a_e($url_base) . '">';

    $h .= '<div class="tarifa__card"><p class="eyebrow">Precio por usuario</p>';
    $h .= '<p class="tarifa__desde"><small>Desde</small><b>$' . a_num(ma_tarifa_min()) . '</b><small>MXN por usuario al mes</small></p>';
    $h .= '<h3>Más usuarios, menos pagas por cada uno</h3>';
    $h .= '<ul class="tarifa__reglas">';
    $h .= '<li>' . a_icon('check') . 'Cada usuario adicional baja ' . $pct . '% el precio de todos tus usuarios.</li>';
    $h .= '<li>' . a_icon('check') . 'Arriba de ' . (ma_tarifa_tope_usuarios() - 1) . ' usuarios, obtienes el descuento máximo de ' . $top . '%.</li>';
    $h .= '<li>' . a_icon('check') . 'Sin planes ni paquetes. Cada usuario incluye hasta 20 activos.</li>';
    $h .= '<li>' . a_icon('check') . 'Al sumar un usuario se cobra al momento la parte proporcional del periodo. Al reducir tus usuarios contratados, el cobro menor rige desde tu siguiente renovación.</li>';
    $h .= '<li>' . a_icon('check') . 'Con el plan anual solo pagas ' . $meses . ' meses, y los otros ' . (12 - $meses) . ' son gratis.</li>';
    $h .= '</ul></div>';

    $h .= '<div class="tarifa__card tarifa__calc" hidden data-calc>';
    $h .= '<p class="eyebrow">Calcula tu mensualidad</p>';
    $h .= '<label class="tarifa__lbl" for="tarifa-n">¿Cuántos usuarios tendrás?</label>';
    $h .= '<input class="tarifa__in" id="tarifa-n" type="number" inputmode="numeric" min="1" max="100000" value="1">';
    $h .= '<div class="period period--sm" role="group" aria-label="Periodo de facturación">';
    $h .= '<button type="button" data-per="mensual" aria-pressed="true">Mensual</button>';
    $h .= '<button type="button" data-per="anual" aria-pressed="false">Anual<span class="save">−17%</span></button></div>';
    $h .= '<ul class="tarifa__desg" data-desg></ul>';
    $h .= '<p class="tarifa__tot"><span data-tot>$0</span><small data-per-txt>MXN al mes</small></p>';
    $h .= '<p class="tarifa__prom muted" data-prom></p>';
    $h .= '<a class="btn btn--primary btn--block" data-cta href="' . a_e($url_base) . '">Contratar' . a_icon('arrow') . '</a>';
    $h .= '</div>';
    $h .= '</div>';
    return $h;
}

/**
 * Rejilla de planes. Misma lógica de contratación que la del kit; cambia el
 * marcado y se quitan las frases que hablan del tamaño de la organización.
 */
function ma_planes($periodo)
{
    $items = array();
    foreach (a_planes() as $p) {
        if ($p['periodo'] === $periodo || $p['periodo'] === 'unico') { $items[] = $p; }
    }
    if (!$items) {
        return '<p class="muted">Por el momento no hay planes publicados. Escríbenos a '
             . '<a href="mailto:' . ALKES_MAIL . '">' . ALKES_MAIL . '</a>.</p>';
    }

    $clave  = a_producto_clave();
    $ahorro = a_ahorro_anual();
    $h = '<div class="plans">';

    foreach ($items as $p) {
        $rec = !empty($p['recomendado']);
        $per = $p['periodo'] === 'anual' ? 'MXN / año'
             : ($p['periodo'] === 'unico' ? 'MXN / pago único' : 'MXN / mes');

        $h .= '<article class="plan' . ($rec ? ' plan--rec' : '') . '">';
        $h .= '<div class="plan__head"><h3 class="plan__name">' . a_e($p['nombre']) . '</h3>';
        if ($rec) { $h .= '<span class="pill pill--azul">Recomendado</span>'; }
        $h .= '</div>';
        $h .= '<p class="plan__price"><span class="plan__cur">$</span><span class="plan__amt">' . a_num($p['precio'])
            . '</span><span class="plan__per">' . $per . '</span></p>';

        $nota = '';
        if (!empty($p['precio_anterior'])) {
            $nota = 'Antes <s>$' . a_num($p['precio_anterior']) . '</s>';
        } elseif ($p['periodo'] === 'anual') {
            $nota = 'Equivale a $' . a_num(round($p['precio'] / 12)) . ' al mes' . ($ahorro ? '. Ahorras ' . $ahorro . '%.' : '');
        } elseif ($p['periodo'] === 'mensual') {
            $nota = 'Sin plazo forzoso. Cancela cuando quieras.';
        }
        $h .= '<p class="plan__note">' . $nota . '</p>';

        $h .= '<ul class="checks plan__features">';
        foreach ($p['caracteristicas'] as $c) {
            $titulo = $c['titulo'];
            $desc   = !empty($c['descripcion']) ? $c['descripcion'] : '';
            if (stripos($titulo, 'técnicos') !== false) { $desc = ''; }           // La descripción habla de tamaño.
            if (stripos($titulo, 'activos') !== false)  { $desc = ''; }           // Repite el título.
            $titulo = preg_replace_callback('/\\b\\d{4,}\\b/', function ($m) { return number_format((int) $m[0]); }, $titulo);
            $h .= '<li><div><strong>' . a_e($titulo) . '</strong>' . ($desc !== '' ? '<p>' . a_e($desc) . '</p>' : '') . '</div></li>';
        }
        $h .= '</ul>';

        $url = ALKES_CENTRAL . '/?producto=' . urlencode($clave) . '&plan=' . urlencode($p['clave']) . '&periodo=' . urlencode($p['periodo']);
        $h .= '<a class="btn ' . ($rec ? 'btn--primary' : 'btn--line') . ' btn--block" href="' . a_e($url) . '">'
            . 'Contratar ' . a_e($p['nombre']) . a_icon('arrow') . '</a>';
        $h .= '</article>';
    }
    return $h . '</div>';
}

/** Diálogo con las credenciales del demo. */
function ma_dialogo_demo($url_app)
{
    $h  = '<dialog id="demoDialog" aria-labelledby="demoTitulo"><div class="dlg">';
    $h .= '<h2 id="demoTitulo">Acceso al demo</h2>';
    $h .= '<p>Entra a la empresa de demostración con estas credenciales. No captures información real.</p>';
    $h .= '<dl class="dlg__creds"><div><dt>Usuario</dt><dd>demo@alkes.mx</dd></div>';
    $h .= '<div><dt>Contraseña</dt><dd>Demo@Alkes2026</dd></div></dl>';
    $h .= '<p>Para una demostración guiada escríbenos a <a href="' . a_e(ma_url_agendar()) . '">' . ALKES_MAIL . '</a>, '
        . 'llama al <a href="tel:' . ALKES_TEL . '">' . ALKES_TEL_TXT . '</a> o usa '
        . '<a href="' . MA_WHATSAPP . '" target="_blank" rel="noopener">WhatsApp</a>.</p>';
    $h .= '<div class="dlg__foot"><a class="btn btn--primary" href="' . a_e($url_app) . '" target="_blank" rel="noopener">Ir a la app' . a_icon('external') . '</a>';
    $h .= '<button class="btn btn--line" type="button" data-close-demo>Cerrar</button></div>';
    return $h . '</div></dialog>';
}

/** Preguntas frecuentes: la misma lista alimenta la sección y el JSON-LD. */
function ma_faq()
{
    $ahorro = a_ahorro_anual();
    return array(
        array('q' => '¿Qué es Mantis?',
              'a' => 'Un sistema de Alkes para planificar, ejecutar y documentar el mantenimiento preventivo y correctivo de equipos e instalaciones, con plantillas checklist, evidencias y trazabilidad de cada intervención.'),
        array('q' => '¿Cuánto cuesta?',
              'a' => ma_faq_precio()),
        array('q' => '¿Cómo se calcula mi mensualidad?',
              'a' => ma_faq_calculo()),
        array('q' => '¿Qué pasa si agrego o quito usuarios a mitad del periodo?',
              'a' => ma_faq_altas()),
        array('q' => '¿Cuántos activos incluye cada usuario?',
              'a' => 'Cada usuario incluye hasta 20 activos. Las plantillas son ilimitadas y no hay planes ni paquetes: el precio depende solo del número de usuarios con cuenta activa.'),
        array('q' => '¿Qué evidencias puedo adjuntar a un mantenimiento?',
              'a' => 'Fotos, videos, PDF, Word y Excel. Se arrastran, se eligen o se pegan, y las fotos se revisan en galería.'),
        array('q' => '¿Mis técnicos pueden usarlo en campo?',
              'a' => 'Sí. Cada técnico entra con su usuario desde el celular o la computadora, registra el mantenimiento a su nombre y escanea el código QR del activo con la cámara.'),
        array('q' => '¿Cómo sé qué mantenimiento toca?',
              'a' => 'Cada activo tiene su frecuencia y Mantis calcula el siguiente servicio. Lo ves en el calendario y en el tablero por vencimiento, y la campana avisa de lo que elijas.'),
        array('q' => '¿La información de mi empresa se mezcla con la de otras?',
              'a' => 'No. Tu información queda aislada de la de cualquier otra cuenta, y dentro de tu empresa cada rol (administrador, supervisor o técnico) ve y hace solo lo que le corresponde.'),
        array('q' => '¿Hay que instalar algo?',
              'a' => 'No. Es una plataforma en la nube y se entra por el navegador.'),
        array('q' => '¿Puedo probarlo?',
              'a' => 'Sí, con el demo y sus credenciales de prueba.'),
    );
}

/** Datos estructurados. Todo sale de lo que la página dice; sin reseñas ni calificaciones. */
function ma_jsonld($seo = array())
{
    $ofertas = array(
        '@type' => 'AggregateOffer',
        'priceCurrency' => 'MXN',
        'lowPrice' => (string) ma_tarifa_min(),
        'highPrice' => (string) ma_tarifa_max(),
        'url' => MA_SITIO . '/#precios',
    );
    $faq = array();
    foreach (ma_faq() as $f) {
        $faq[] = array('@type' => 'Question', 'name' => $f['q'],
                       'acceptedAnswer' => array('@type' => 'Answer', 'text' => $f['a']));
    }
    $redes = array();
    foreach (ma_redes() as $r) { $redes[] = $r['u']; }

    $g = array(
        '@context' => 'https://schema.org',
        '@graph' => array(
            array('@type' => 'Organization', '@id' => ALKES_SITIO . '/#org', 'name' => 'Alkes', 'url' => ALKES_SITIO,
                  'email' => ALKES_MAIL, 'telephone' => ALKES_TEL, 'sameAs' => $redes),
            array('@type' => 'WebSite', '@id' => MA_SITIO . '/#web', 'name' => 'Mantis', 'url' => MA_SITIO . '/',
                  'inLanguage' => 'es-MX', 'publisher' => array('@id' => ALKES_SITIO . '/#org')),
            array('@type' => 'SoftwareApplication', '@id' => MA_SITIO . '/#app', 'name' => 'Mantis',
                  'applicationCategory' => 'BusinessApplication', 'operatingSystem' => 'Web',
                  'description' => 'Sistema en la nube para planificar, ejecutar y documentar el mantenimiento de equipos e instalaciones con plantillas checklist, evidencias y trazabilidad.',
                  'url' => MA_SITIO . '/', 'inLanguage' => 'es-MX', 'image' => MA_SITIO . '/assets/img/og-image.png',
                  'publisher' => array('@id' => ALKES_SITIO . '/#org'), 'offers' => $ofertas),
            array('@type' => 'FAQPage', '@id' => MA_SITIO . '/#faq', 'mainEntity' => $faq),
        ),
    );
    $g = seo_enriquecer($g, $seo);
    return json_encode($g, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
}

/** Texto de precio para las preguntas frecuentes. */
function ma_faq_precio()
{
    $t = ma_tarifa();
    $pct = rtrim(rtrim(number_format((float) $t['paso'] * 100, 2, '.', ''), '0'), '.');
    return 'Desde $' . a_num(ma_tarifa_min()) . ' MXN por usuario al mes. Se cobra por usuario con cuenta activa, y cada usuario adicional baja ' . $pct . '% el precio de todos hasta llegar a ' . (int) round((float) $t['tope_descuento'] * 100) . '% de descuento arriba de ' . (ma_tarifa_tope_usuarios() - 1) . ' usuarios. Con el plan anual solo pagas ' . (int) $t['anual_meses'] . ' meses, y los otros ' . (12 - (int) $t['anual_meses']) . ' son gratis.';
}

function ma_faq_calculo()
{
    $c15 = ma_calcular(15); $c50 = ma_calcular(50);
    return 'Todos tus usuarios pagan el mismo precio, que baja con cada usuario que sumas. Con 15 usuarios son 15 × $' . a_num($c15['precio']) . ' = $' . a_num($c15['total']) . ' MXN al mes; con 50 usuarios son 50 × $' . a_num($c50['precio']) . ' = $' . a_num($c50['total']) . ' MXN al mes. La calculadora de la sección de precios te da el total exacto.';
}

function ma_faq_altas()
{
    return 'Al sumar un usuario se cobra al momento la parte proporcional hasta tu próxima factura, y desde la siguiente el precio por usuario se ajusta a tu nuevo total. Desactivar un usuario libera su lugar para otra persona, pero no cambia el cobro: para pagar menos, reduce tus usuarios contratados en Configuración y la reducción rige desde la siguiente renovación; no hay reembolso a mitad del periodo.';
}

if (!defined('ALKES_SITIO'))    { define('ALKES_SITIO', 'https://alkes.mx'); }
if (!defined('ALKES_CENTRAL'))  { define('ALKES_CENTRAL', 'https://contratar.alkes.mx'); }
if (!defined('ALKES_MAIL'))     { define('ALKES_MAIL', 'gquintero@alkes.mx'); }
if (!defined('ALKES_TEL'))      { define('ALKES_TEL', '+523339460097'); }
if (!defined('ALKES_TEL_TXT'))  { define('ALKES_TEL_TXT', '333 946 0097'); }

/** Escape de todo dato que entre al HTML. */
function a_e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

/**
 * La familia completa. Alimenta la rejilla de ecosistema del pie.
 * Orden fijo en las seis landings: la firma tiene que ser reconocible.
 */
function a_familia()
{
    return array(
        'smartpend' => array('nombre' => 'SmartPend', 'url' => 'https://smartpend.alkes.mx', 'que' => 'Pendientes y proyectos'),
        'geocheck'  => array('nombre' => 'GeoCheck',  'url' => 'https://geocheck.alkes.mx',  'que' => 'Asistencia por ubicación'),
        'mantis'    => array('nombre' => 'Mantis',    'url' => 'https://mantis.alkes.mx',    'que' => 'Mantenimiento de activos'),
        'syspower'  => array('nombre' => 'SysPower',  'url' => 'https://syspower.alkes.mx',  'que' => 'Energía controlada a distancia'),
        'erp'       => array('nombre' => 'Alkes ERP', 'url' => 'https://erp.alkes.mx',       'que' => 'Gestión empresarial y CFDI'),
    );
}

// ---------------------------------------------------------------------------
// Iconos. Trazo 1.75, viewBox 24×24, currentColor. Decorativos siempre.
// ---------------------------------------------------------------------------
function a_icon($nombre, $clase = '')
{
    static $d = null;
    if ($d === null) { $d = array(
    'arrow'      => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    'check'      => '<path d="M20 6 9 17l-5-5"/>',
    'close'      => '<path d="M18 6 6 18M6 6l12 12"/>',
    'chevron'    => '<path d="m6 9 6 6 6-6"/>',
    'menu'       => '<path d="M4 7h16M4 12h16M4 17h16"/>',
    'moon'       => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/>',
    'sun'        => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
    'external'   => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
    'mail'       => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 7 7.6 5.3a1.6 1.6 0 0 0 1.8 0L20.5 7"/>',
    'phone'      => '<path d="M7 3h3l2 5-2.5 1.5a12 12 0 0 0 5 5L16 12l5 2v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4 5.2 2 2 0 0 1 6 3Z"/>',
    'globe'      => '<circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M3.5 15h17M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/>',
    'shield'     => '<path d="M12 3 5 6v5.5c0 4.2 2.9 7.6 7 9.5 4.1-1.9 7-5.3 7-9.5V6Z"/><path d="m9 12 2 2 4-4"/>',
    'play'       => '<circle cx="12" cy="12" r="9"/><path d="M10.5 9 15 12l-4.5 3Z"/>',
    'eye'        => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>',
    'lock'       => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
    'clock'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.3l3.3 2"/>',
    'history'    => '<path d="M3.5 12a8.5 8.5 0 1 0 2.6-6.1M3.5 5v4h4"/><path d="M12 7.5v5l3.2 1.9"/>',
    'users'      => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-5.5 6-5.5s6 2.2 6 5.5"/><path d="M16 5.3a3.2 3.2 0 0 1 0 6.2M17.5 14.8c2.1.6 3.5 2.3 3.5 4.6"/>',
    'user-check' => '<circle cx="10" cy="8" r="3.2"/><path d="M3.5 20c0-3.3 2.9-5.5 6.5-5.5 1 0 2 .2 2.8.5"/><path d="m15.5 17.5 2 2 4-4"/>',
    'mic'        => '<rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5.5 11.5a6.5 6.5 0 0 0 13 0M12 18v3"/>',
    'tag'        => '<path d="M3.5 11.2V4.5a1 1 0 0 1 1-1h6.7a1 1 0 0 1 .7.3l8.3 8.3a1 1 0 0 1 0 1.4l-6.7 6.7a1 1 0 0 1-1.4 0L3.8 11.9a1 1 0 0 1-.3-.7Z"/><circle cx="7.8" cy="7.8" r="1.3"/>',
    'form'       => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h4"/>',
    'report'     => '<path d="M6 3h8l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/><path d="M14 3v5h5"/><path d="M9 17v-3M12 17v-5M15 17v-2"/>',
    'calendar'   => '<rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
    'repeat'     => '<path d="M4 9V8a3 3 0 0 1 3-3h10l-3-3M20 15v1a3 3 0 0 1-3 3H7l3 3"/>',
    'layers'     => '<path d="m12 3 9 5-9 5-9-5Z"/><path d="m3.5 12.5 8.5 4.7 8.5-4.7"/><path d="m3.5 16.8 8.5 4.7 8.5-4.7"/>',
    'filter'     => '<path d="M3.5 5h17l-6.6 7.8V20l-3.8-2.2v-5Z"/>',
    'building'   => '<path d="M4 21V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v16"/><path d="M15 10h3a2 2 0 0 1 2 2v9M8 7h3M8 11h3M8 15h3M2.5 21h19"/>',
    'bell'       => '<path d="M6 9a6 6 0 1 1 12 0c0 4 1.5 5.5 2 6H4c.5-.5 2-2 2-6Z"/><path d="M10 19a2 2 0 0 0 4 0"/>',
    'cpu'        => '<rect x="6" y="6" width="12" height="12" rx="2"/><rect x="9.5" y="9.5" width="5" height="5" rx="1"/><path d="M9 3v3M15 3v3M9 18v3M15 18v3M3 9h3M3 15h3M18 9h3M18 15h3"/>',
    'sparkle'    => '<path d="m12 3 1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9Z"/><path d="M18.5 16.5 19 18l1.5.5-1.5.5-.5 1.5-.5-1.5L16.5 18l1.5-.5Z"/>',
    'cloud'      => '<path d="M7 18h10.5a3.5 3.5 0 0 0 .4-7A5.5 5.5 0 0 0 7.3 9.6 4.2 4.2 0 0 0 7 18Z"/>',
    'cloud-check'=> '<path d="M7 17.5h10a3.5 3.5 0 0 0 .4-7A5.5 5.5 0 0 0 7.3 9.1 4.2 4.2 0 0 0 7 17.5Z"/><path d="m9.5 13.5 2 2 3.5-3.5"/>',
    'refresh'    => '<path d="M20 11a8 8 0 0 0-13.8-5.2L3.5 8.5"/><path d="M3.5 4v4.5H8"/><path d="M4 13a8 8 0 0 0 13.8 5.2l2.7-2.7"/><path d="M20.5 20v-4.5H16"/>',
    'checklist'  => '<path d="M9 6h11M9 12h11M9 18h11"/><path d="m3.5 6 1.2 1.2L7 5M3.5 12l1.2 1.2L7 11M3.5 18l1.2 1.2L7 17"/>',
    'geo'        => '<path d="M12 21s7-5.7 7-11a7 7 0 1 0-14 0c0 5.3 7 11 7 11Z"/><circle cx="12" cy="10" r="2.7"/>',
    'target'     => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/>',
    'badge'      => '<rect x="3.5" y="4.5" width="17" height="15" rx="2"/><circle cx="9" cy="10.5" r="2.2"/><path d="M5.5 16.5c.5-1.7 1.9-2.6 3.5-2.6s3 .9 3.5 2.6M15 9.5h3.5M15 13h3.5"/>',
    'camera'     => '<path d="M4 7h3l1.5-2h7L17 7h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1Z"/><circle cx="12" cy="13" r="3.6"/>',
    'qr'         => '<rect x="3.5" y="3.5" width="7" height="7" rx="1"/><rect x="13.5" y="3.5" width="7" height="7" rx="1"/><rect x="3.5" y="13.5" width="7" height="7" rx="1"/><path d="M13.5 13.5h3v3h-3zM20.5 13.5v3M17.5 20.5h3M13.5 20.5h1"/>',
    'route'      => '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M15.5 6H10a4 4 0 0 0 0 8h4a4 4 0 0 1 0 8H8.5"/>',
    'truck'      => '<path d="M3 6h10v10H3zM13 9.5h4l3 3.2V16h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
    'warehouse'  => '<path d="M3 21V8.5L12 4l9 4.5V21"/><path d="M7.5 21v-7h9v7M7.5 17.5h9"/>',
    'factory'    => '<path d="M3 21V10l6 4V10l6 4V6h6v15Z"/><path d="M7 17.5h2M13 17.5h2M18 17.5h1"/>',
    'cart'       => '<path d="M3 4h2.2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h8.3a1.5 1.5 0 0 0 1.5-1.2L21 8H6"/><circle cx="9.5" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/>',
    'invoice'    => '<path d="M5.5 3h13v18l-2.2-1.5-2.1 1.5-2.2-1.5L9.8 21l-2.1-1.5L5.5 21Z"/><path d="M9 8h6M9 12h6"/>',
    'coins'      => '<ellipse cx="9" cy="7" rx="5.5" ry="2.5"/><path d="M3.5 7v5c0 1.4 2.5 2.5 5.5 2.5s5.5-1.1 5.5-2.5V7"/><path d="M14.5 11.4c3 .2 5.2 1.2 5.2 2.4v4.7c0 1.4-2.5 2.5-5.5 2.5s-5.5-1.1-5.5-2.5v-3.6"/>',
    'server'     => '<rect x="3.5" y="4" width="17" height="6.5" rx="1.5"/><rect x="3.5" y="13.5" width="17" height="6.5" rx="1.5"/><path d="M7 7.2h.01M7 16.8h.01"/>',
    'rack'       => '<rect x="4.5" y="3" width="15" height="18" rx="2"/><path d="M4.5 8.5h15M4.5 14h15M8 5.8h.01M8 11.2h.01M8 17.3h.01"/>',
    'ethernet'   => '<path d="M6 10V6.5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1V10l2 2v5.5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V12Z"/><path d="M8.5 12.5v3M12 12.5v3M15.5 12.5v3"/>',
    'wifi'       => '<path d="M2.5 8.5a15 15 0 0 1 19 0M5.8 12.2a10 10 0 0 1 12.4 0M9.2 15.8a5 5 0 0 1 5.6 0"/><circle cx="12" cy="19.3" r="1.2"/>',
    'power'      => '<path d="M12 3v9"/><path d="M7.3 6.3a8 8 0 1 0 9.4 0"/>',
    'zap'        => '<path d="M13.5 2 5 13.5h6L10.5 22 19 10.5h-6Z"/>',
    'display'    => '<rect x="2.5" y="4" width="19" height="12.5" rx="2"/><path d="M8.5 20.5h7M12 16.5v4"/>',
    'wrench'     => '<path d="M20 5.5a5 5 0 0 1-6.6 6.6L6 19.5a2.1 2.1 0 0 1-3-3l7.4-7.4A5 5 0 0 1 17 2.5l-3 3 1.5 3 3 1.5Z"/>',
    'box'        => '<path d="m12 3 8.5 4.5v9L12 21l-8.5-4.5v-9Z"/><path d="M3.5 7.5 12 12l8.5-4.5M12 12v9"/>',
    'chart'      => '<path d="M4 20V4M4 20h16"/><path d="M8 16.5v-4M12 16.5V8M16 16.5v-6.5"/>',
    'gear'       => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v2.2M12 19.3v2.2M4.2 12H2M22 12h-2.2M6.3 6.3 4.8 4.8M19.2 19.2l-1.5-1.5M6.3 17.7l-1.5 1.5M19.2 4.8l-1.5 1.5"/>',
    'database'   => '<ellipse cx="12" cy="6" rx="7.5" ry="3"/><path d="M4.5 6v12c0 1.7 3.4 3 7.5 3s7.5-1.3 7.5-3V6M4.5 12c0 1.7 3.4 3 7.5 3s7.5-1.3 7.5-3"/>',
    'code'       => '<path d="m8.5 8-5 4 5 4M15.5 8l5 4-5 4M13.5 4.5l-3 15"/>',
    'book'       => '<path d="M4 4.5A1.5 1.5 0 0 1 5.5 3H19v15.5H5.5A1.5 1.5 0 0 0 4 20Z"/><path d="M4 20a1.5 1.5 0 0 0 1.5 1.5H19"/>',
    'search'     => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/>',
    'image'      => '<rect x="3.5" y="4.5" width="17" height="15" rx="2"/><circle cx="8.8" cy="9.8" r="1.6"/><path d="m4 17 4.8-4.5 3.4 3.2 3-2.7 4.8 4.5"/>',
    'alert'      => '<path d="M12 3.5 21 19.5H3Z"/><path d="M12 10v4M12 16.8h.01"/>',
    'restart'    => '<path d="M12 3v5"/><path d="M17.6 6.4a8 8 0 1 1-11.2 0"/>',
    'login'      => '<path d="M14 3.5h4.5a1.5 1.5 0 0 1 1.5 1.5v14a1.5 1.5 0 0 1-1.5 1.5H14"/><path d="M4 12h11M11.5 8.5 15 12l-3.5 3.5"/>',
    'paperclip'  => '<path d="M20 11.5 12.2 19.3a5 5 0 0 1-7-7L12.9 4.4a3.3 3.3 0 0 1 4.7 4.7l-7.7 7.8a1.7 1.7 0 0 1-2.4-2.4l7.2-7.2"/>',
    'github'     => '<path d="M9 19c-4.5 1.4-4.5-2.4-6.5-2.9m13 4.9v-3.5a3 3 0 0 0-.85-2.35c2.85-.3 5.85-1.4 5.85-6.3a4.9 4.9 0 0 0-1.3-3.4 4.6 4.6 0 0 0-.1-3.4s-1.05-.3-3.5 1.3a12 12 0 0 0-6.3 0C6.85.65 5.8.95 5.8.95a4.6 4.6 0 0 0-.1 3.4A4.9 4.9 0 0 0 4.4 7.75c0 4.9 3 6 5.85 6.3a3 3 0 0 0-.85 2.3V20"/>',
    'linkedin'   => '<path d="M15 8.5a5.5 5.5 0 0 1 5.5 5.5v6h-3.5v-6a2 2 0 0 0-4 0v6H9.5V8.8H13v1.9A4.4 4.4 0 0 1 15 8.5Z"/><rect x="2.5" y="8.8" width="3.5" height="11.2"/><circle cx="4.25" cy="4.5" r="1.9"/>',
    ); }
    $p = isset($d[$nombre]) ? $d[$nombre] : $d['check'];
    $c = $clase !== '' ? ' class="' . a_e($clase) . '"' : '';
    return '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"'
         . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
}

// ---------------------------------------------------------------------------
// Piezas compartidas
// ---------------------------------------------------------------------------

/** Rejilla del ecosistema para el pie. */
function a_ecosistema_grid($actual)
{
    $h = '<div class="eco-grid" data-reveal>';
    foreach (a_familia() as $k => $p) {
        if ($k === $actual) {
            $h .= '<div class="eco-card" aria-current="page">';
            $h .= '<div><b>' . a_e($p['nombre']) . '</b><span>Estás aquí</span></div></div>';
        } else {
            $h .= '<a class="eco-card" href="' . a_e($p['url']) . '">';
            $h .= '<div><b>' . a_e($p['nombre']) . '</b><span>' . a_e($p['que']) . '</span></div></a>';
        }
    }
    return $h . '</div>';
}

/** Encabezado de sección: índice en el riel + eyebrow + título + entradilla. */
function a_head($idx, $eyebrow, $titulo, $lede = '', $extra = '')
{
    $h  = '<header class="head' . ($extra ? ' ' . a_e($extra) : '') . '" data-reveal>';
    $h .= '<p class="idx head__idx">' . a_e($idx) . '</p>';
    $h .= '<div class="head__main">';
    if ($eyebrow !== '') { $h .= '<p class="eyebrow">' . a_e($eyebrow) . '</p>'; }
    $h .= '<h2>' . $titulo . '</h2>';
    if ($lede !== '')    { $h .= '<p class="lede">' . a_e($lede) . '</p>'; }
    return $h . '</div></header>';
}

/**
 * Hueco de captura.
 * Si el archivo existe se pinta la imagen; si no, un marco con la ruta y las
 * dimensiones que debe tener, para sustituirlo sin adivinar nada.
 */
function a_shot($archivo, $alt, $w, $h, $pie = '', $fig = '', $clase = '')
{
    $ruta   = __DIR__ . '/' . $archivo;
    $existe = is_file($ruta);
    $osc    = preg_replace('/\.(png|jpe?g|webp)$/i', '-oscuro.$1', $archivo);
    $existeOsc = $osc !== $archivo && is_file(__DIR__ . '/' . $osc);
    $o  = '<figure class="shot' . ($existe ? '' : ' shot--pendiente')
        . ($clase ? ' ' . a_e($clase) : '') . '" data-reveal>';
    $o .= '<div class="shot__box ticks">';
    $o .= '<div class="shot__frame" style="--ar: ' . (int) $w . ' / ' . (int) $h . '">';
    if ($existe) {
        $o .= '<img src="' . a_e($archivo) . '" alt="' . a_e($alt) . '"'
            . ($existeOsc ? ' data-theme-img="light"' : '')
            . ' width="' . (int) $w . '" height="' . (int) $h . '" loading="lazy" decoding="async">';
        if ($existeOsc) {
            $o .= '<img src="' . a_e($osc) . '" alt="' . a_e($alt) . '" data-theme-img="dark"'
                . ' width="' . (int) $w . '" height="' . (int) $h . '" loading="lazy" decoding="async">';
        }
    } else {
        $o .= '<div class="shot__pending">' . a_icon('image')
            . '<b>Captura pendiente</b>'
            . '<code>' . a_e($archivo) . '</code>'
            . '<small>' . (int) $w . ' × ' . (int) $h . ' px</small></div>';
    }
    $o .= '</div></div>';
    if ($pie !== '') {
        $o .= '<figcaption class="shot__cap">';
        if ($fig !== '') { $o .= '<span class="mono">' . a_e($fig) . '</span>'; }
        $o .= '<span>' . a_e($pie) . '</span></figcaption>';
    }
    return $o . '</figure>';
}

/** Conmutador de tema. Muestra el icono de lo que va a activar. */
function a_tema($clase = 'theme-toggle')
{
    return '<button class="' . a_e($clase) . '" type="button" data-theme-toggle aria-label="Cambiar tema">'
         . a_icon('moon', 'i-moon') . a_icon('sun', 'i-sun')
         . '<span class="theme-toggle__label"></span></button>';
}

/** Bloque del autor: la persona real que desarrolla y da soporte. */
function a_autor($cita)
{
    $h  = '<div class="author" data-reveal>';
    $h .= '<img class="author__photo" src="https://gustavoquintero.dev/assets/img/my-profile-image.jpg"'
        . ' alt="Retrato de Gustavo Quintero" width="84" height="84" loading="lazy" decoding="async">';
    $h .= '<div><h3>Gustavo Quintero</h3>';
    $h .= '<p class="author__role">Desarrollo y operación</p>';
    $h .= '<blockquote><p>«' . a_e($cita) . '»</p></blockquote>';
    $h .= '<div class="author__links">';
    $h .= '<a href="mailto:atencion@gustavoquintero.dev">' . a_icon('mail') . 'atencion@gustavoquintero.dev</a>';
    $h .= '<a href="https://gustavoquintero.dev" target="_blank" rel="noopener">' . a_icon('globe')
        . 'gustavoquintero.dev' . a_icon('external') . '</a>';
    $h .= '<a href="https://github.com/GustavoQuinteroC" target="_blank" rel="noopener">' . a_icon('github')
        . 'GitHub' . a_icon('external') . '</a>';
    $h .= '<a href="https://www.linkedin.com/in/gustavo-quintero-81aa18169/" target="_blank" rel="noopener">' . a_icon('linkedin')
        . 'LinkedIn' . a_icon('external') . '</a>';
    $h .= '</div></div></div>';
    return $h;
}

/** Diálogo con las credenciales del demo. */
function a_dialogo_demo($url_app)
{
    $h  = '<dialog id="demoDialog" aria-labelledby="demoTitulo">';
    $h .= '<div class="dlg"><h2 id="demoTitulo">Acceso al demo</h2>';
    $h .= '<p>Entra a la empresa de demostración con estas credenciales.</p>';
    $h .= '<dl class="dlg__creds">';
    $h .= '<div><dt>Usuario</dt><dd>demo@alkes.mx</dd></div>';
    $h .= '<div><dt>Contraseña</dt><dd>Demo@Alkes2026</dd></div>';
    $h .= '</dl>';
    $h .= '<p>Para una demostración guiada, contacta a <strong>Gustavo Quintero</strong> al correo '
        . '<a href="mailto:' . ALKES_MAIL . '">' . ALKES_MAIL . '</a> o al teléfono '
        . '<a href="tel:' . ALKES_TEL . '">' . ALKES_TEL_TXT . '</a>.</p>';
    $h .= '<div class="dlg__foot">';
    $h .= '<a class="btn btn--primary" href="' . a_e($url_app) . '" target="_blank" rel="noopener">Ir a la app' . a_icon('external') . '</a>';
    $h .= '<button class="btn btn--outline" type="button" data-close-demo>Cerrar</button>';
    $h .= '</div></div></dialog>';
    return $h;
}


function a_cache()
{
    static $d = null;
    if ($d !== null) { return $d; }
    $f = __DIR__ . '/planes.cache.json';
    $d = array('producto' => array(), 'planes' => array());
    if (is_file($f)) {
        $j = json_decode((string) @file_get_contents($f), true);
        if (is_array($j)) { $d = $j + $d; }
    }
    return $d;
}

function a_planes()         { $d = a_cache(); return isset($d['planes']) ? $d['planes'] : array(); }
function a_producto_clave() { $d = a_cache(); return isset($d['producto']['clave']) ? $d['producto']['clave'] : ''; }
function a_url_panel()      { $d = a_cache(); return isset($d['producto']['url_panel']) ? $d['producto']['url_panel'] : ALKES_SITIO; }

function a_num($n) { return number_format((float) $n, (floor($n) == $n ? 0 : 2), '.', ','); }

/** Traduce los iconos del catálogo (Bootstrap Icons) al set propio. */
function a_map_icon($bi)
{
    $m = array(
        'bi-people-fill' => 'users',            'bi-cpu-fill' => 'cpu',
        'bi-paperclip' => 'paperclip',          'bi-tools' => 'wrench',
        'bi-arrow-up-circle-fill' => 'refresh', 'bi-clock-history' => 'history',
        'bi-shield-lock' => 'lock',             'bi-box-seam' => 'box',
        'bi-box-seam-fill' => 'box',            'bi-card-checklist' => 'checklist',
        'bi-geo-alt-fill' => 'geo',             'bi-person-badge-fill' => 'badge',
        'bi-file-earmark-bar-graph' => 'report','bi-display-fill' => 'display',
        'bi-ethernet' => 'ethernet',            'bi-hdd-rack-fill' => 'rack',
        'bi-cloud-check-fill' => 'cloud-check',
    );
    return isset($m[$bi]) ? $m[$bi] : 'check';
}

/** Ahorro del plan anual frente a doce mensualidades. Calculado, nunca escrito. */
function a_ahorro_anual()
{
    $m = array(); $a = array();
    foreach (a_planes() as $p) {
        if ($p['periodo'] === 'mensual') { $m[$p['clave']] = (float) $p['precio']; }
        if ($p['periodo'] === 'anual')   { $a[$p['clave']] = (float) $p['precio']; }
    }
    $pcts = array();
    foreach ($a as $k => $precio) {
        if (!empty($m[$k]) && $m[$k] > 0) {
            $full = $m[$k] * 12;
            if ($full > $precio) { $pcts[$k] = (int) round((1 - $precio / $full) * 100); }
        }
    }
    return $pcts ? max($pcts) : 0;
}

/** Rejilla de planes de un periodo. Los de pago único aparecen en ambos. */
function a_grid_planes($periodo)
{
    $items = array();
    foreach (a_planes() as $p) {
        if ($p['periodo'] === $periodo || $p['periodo'] === 'unico') { $items[] = $p; }
    }
    if (!$items) {
        return '<p class="center muted">Por el momento no hay planes publicados. '
             . '<a class="link-arrow" href="mailto:' . ALKES_MAIL . '">Escríbenos' . a_icon('arrow') . '</a></p>';
    }

    $clave  = a_producto_clave();
    $ahorro = a_ahorro_anual();
    $h = '<div class="plans">';

    foreach ($items as $p) {
        $rec = !empty($p['recomendado']);
        $per = $p['periodo'] === 'anual' ? 'MXN / año'
             : ($p['periodo'] === 'unico' ? 'MXN / pago único' : 'MXN / mes');

        $h .= '<div class="plan' . ($rec ? ' plan--rec' : '') . '">';
        $h .= '<div class="plan__head"><h3 class="plan__name">' . a_e($p['nombre']) . '</h3>';
        if ($rec) { $h .= '<span class="plan__tag">Recomendado</span>'; }
        $h .= '</div>';

        $h .= '<p class="plan__price"><span class="plan__cur">$</span>'
            . '<span class="plan__amt">' . a_num($p['precio']) . '</span>'
            . '<span class="plan__per">' . $per . '</span></p>';

        $nota = '';
        if (!empty($p['precio_anterior'])) {
            $nota = 'Antes <s>$' . a_num($p['precio_anterior']) . '</s>';
        } elseif ($p['periodo'] === 'anual') {
            $nota = 'Equivale a $' . a_num(round($p['precio'] / 12)) . ' al mes'
                  . ($ahorro ? ' · ahorras ' . $ahorro . ' %' : '');
        } elseif ($p['periodo'] === 'mensual') {
            $nota = 'Sin permanencia. Cancela cuando quieras.';
        }
        $h .= '<p class="plan__note">' . $nota . '</p>';

        $h .= '<ul class="plan__features">';
        foreach ($p['caracteristicas'] as $c) {
            $h .= '<li>' . a_icon(a_map_icon(isset($c['icono']) ? $c['icono'] : ''))
                . '<div><strong>' . a_e($c['titulo']) . '</strong>';
            if (!empty($c['descripcion'])) { $h .= '<p>' . a_e($c['descripcion']) . '</p>'; }
            $h .= '</div></li>';
        }
        $h .= '</ul>';

        $url = ALKES_CENTRAL . '/?producto=' . urlencode($clave)
             . '&plan=' . urlencode($p['clave'])
             . '&periodo=' . urlencode($p['periodo']);

        $h .= '<a class="btn ' . ($rec ? 'btn--primary' : 'btn--outline') . ' btn--block" href="' . a_e($url) . '">'
            . 'Contratar ' . a_e($p['nombre']) . a_icon('arrow') . '</a>';
        $h .= '</div>';
    }
    return $h . '</div>';
}

if (!defined('SEO_FUNDADOR_ID')) { define('SEO_FUNDADOR_ID', 'https://gustavoquintero.dev/#persona'); }

/** Persona fundadora: la misma entidad que describe gustavoquintero.dev. */
function seo_fundador()
{
    return array(
        '@type' => 'Person', '@id' => SEO_FUNDADOR_ID,
        'name' => 'Gustavo de Jesús Quintero Cerpa', 'alternateName' => 'Gustavo Quintero',
        'jobTitle' => 'Fundador y desarrollador de Alkes',
        'url' => 'https://gustavoquintero.dev/',
        'sameAs' => array('https://www.linkedin.com/in/gustavo-quintero-81aa18169/', 'https://github.com/GustavoQuinteroC'),
        'address' => array('@type' => 'PostalAddress', 'addressLocality' => 'Guadalajara', 'addressRegion' => 'Jalisco', 'addressCountry' => 'MX'),
    );
}

/** Versión publicada más reciente, leída del historial de novedades. */
function seo_version($dir)
{
    $t = (string) file_get_contents($dir . '/index.php');
    return preg_match('/disc__v">v(\d+\.\d+\.\d+)</', $t, $m) ? $m[1] : '';
}

/**
 * Lista de funciones y capturas a partir de los arreglos de la página.
 * Acepta $CAPACIDADES (con 'items' e 'img'), $SOSTIENE y $AVANZADAS.
 */
function seo_de_pagina($sitio, $capacidades, $extras = array())
{
    $funciones = array();
    $capturas = array();
    foreach ((array) $capacidades as $c) {
        if (!empty($c['h3'])) { $funciones[] = $c['h3']; }
        foreach ((array) (isset($c['items']) ? $c['items'] : array()) as $it) {
            if (is_array($it) && isset($it[1])) { $funciones[] = $it[1]; }
        }
        if (!empty($c['img'])) {
            $capturas[] = array_filter(array('@type' => 'ImageObject', 'url' => $sitio . '/' . ltrim($c['img'], '/'),
                                'caption' => isset($c['alt']) ? $c['alt'] : '',
                                'width' => isset($c['w']) ? (int) $c['w'] : null, 'height' => isset($c['h']) ? (int) $c['h'] : null),
                                function ($v) { return $v !== null && $v !== ''; });
        }
    }
    foreach ((array) $extras as $lista) {
        foreach ((array) $lista as $it) {
            if (is_array($it) && isset($it[1])) { $funciones[] = $it[1]; }
        }
    }
    $funciones = array_values(array_unique(array_filter($funciones)));
    return array('funciones' => $funciones, 'capturas' => $capturas);
}

/** Etiquetas extra del <head>. $s: sitio, nombre, titulo, alt, palabras, noindex. */
function seo_head($s)
{
    $e = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
    $sitio = $s['sitio'];
    $og = $sitio . '/assets/img/og-image.png';
    $out = array();
    if (empty($s['noindex'])) {
        $out[] = '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">';
        $out[] = '<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1">';
        $out[] = '<meta name="bingbot" content="index, follow, max-image-preview:large, max-snippet:-1">';
    }
    $out[] = '<meta name="keywords" content="' . $e(implode(', ', $s['palabras'])) . '">';
    $out[] = '<meta name="application-name" content="' . $e($s['nombre']) . '">';
    $out[] = '<meta name="apple-mobile-web-app-title" content="' . $e($s['nombre']) . '">';
    $out[] = '<meta name="creator" content="Gustavo Quintero">';
    $out[] = '<meta name="publisher" content="Alkes">';
    $out[] = '<meta name="classification" content="Software empresarial en la nube">';
    $out[] = '<meta name="language" content="es-MX">';
    $out[] = '<meta name="geo.region" content="MX-JAL">';
    $out[] = '<meta name="geo.placename" content="Guadalajara, Jalisco, México">';
    $out[] = '<meta name="geo.position" content="20.6597;-103.3496">';
    $out[] = '<meta name="ICBM" content="20.6597, -103.3496">';
    $out[] = '<link rel="alternate" hreflang="es-MX" href="' . $sitio . '/">';
    $out[] = '<link rel="alternate" hreflang="es" href="' . $sitio . '/">';
    $out[] = '<link rel="alternate" hreflang="x-default" href="' . $sitio . '/">';
    $out[] = '<link rel="alternate" type="text/markdown" title="Resumen para modelos de lenguaje" href="' . $sitio . '/llms.txt">';
    $out[] = '<link rel="sitemap" type="application/xml" href="' . $sitio . '/sitemap.xml">';
    $out[] = '<link rel="author" href="https://gustavoquintero.dev/">';
    $out[] = '<meta property="og:image:secure_url" content="' . $og . '">';
    $out[] = '<meta property="og:image:type" content="image/png">';
    $out[] = '<meta name="twitter:image:alt" content="' . $e($s['alt']) . '">';
    return implode("\n", $out) . "\n";
}

/**
 * Enriquece el grafo JSON-LD que arma cada landing.
 * $s: sitio, nombre, subcategoria, palabras, version, funciones, capturas,
 *     dispositivos, requisitos, titulo.
 */
function seo_enriquecer($g, $s)
{
    if (empty($s) || empty($g['@graph'])) { return $g; }
    $sitio = $s['sitio'];
    $org = ALKES_SITIO . '/#org';
    foreach ($g['@graph'] as $i => $n) {
        $tipo = isset($n['@type']) ? $n['@type'] : '';
        if ($tipo === 'Organization') {
            $g['@graph'][$i] = array_merge($n, array(
                'alternateName' => array('Alkes Software y Soluciones TI', 'Alkes TI'),
                'description' => 'Empresa de software y soluciones TI en Guadalajara, Jalisco. Desarrolla y opera sistemas en la nube para empresas mexicanas: ERP con CFDI 4.0, pendientes con IA, control de asistencia, mantenimiento y reinicio remoto de equipos.',
                'logo' => array('@type' => 'ImageObject', 'url' => ALKES_SITIO . '/assets/img/logo_alkes.png'),
                'address' => array('@type' => 'PostalAddress', 'addressLocality' => 'Guadalajara', 'addressRegion' => 'Jalisco', 'addressCountry' => 'MX'),
                'areaServed' => array('@type' => 'Country', 'name' => 'México'),
                'founder' => array('@id' => SEO_FUNDADOR_ID),
                'foundingLocation' => array('@type' => 'Place', 'name' => 'Guadalajara, Jalisco, México'),
                'contactPoint' => array('@type' => 'ContactPoint', 'contactType' => 'Ventas y soporte', 'telephone' => ALKES_TEL,
                                        'email' => ALKES_MAIL, 'areaServed' => 'MX', 'availableLanguage' => array('es')),
                'knowsAbout' => array('Software empresarial', 'ERP', 'Facturación electrónica CFDI 4.0', 'Gestión de pendientes',
                                      'Control de asistencia', 'Mantenimiento preventivo', 'Internet de las cosas', 'Inteligencia artificial'),
            ));
        } elseif ($tipo === 'SoftwareApplication') {
            $extra = array(
                'applicationSubCategory' => $s['subcategoria'],
                'keywords' => implode(', ', $s['palabras']),
                'countriesSupported' => 'MX',
                'availableOnDevice' => $s['dispositivos'],
                'browserRequirements' => $s['requisitos'],
                'provider' => array('@id' => $org),
                'creator' => array('@id' => SEO_FUNDADOR_ID),
                'author' => array('@id' => SEO_FUNDADOR_ID),
                'isAccessibleForFree' => false,
                'mainEntityOfPage' => array('@id' => $sitio . '/#pagina'),
            );
            if (!empty($s['version'])) {
                $extra['softwareVersion'] = $s['version'];
                $extra['releaseNotes'] = $sitio . '/#novedades';
            }
            if (!empty($s['funciones'])) { $extra['featureList'] = $s['funciones']; }
            if (!empty($s['capturas'])) { $extra['screenshot'] = $s['capturas']; }
            $g['@graph'][$i] = array_merge($n, $extra);
        } elseif ($tipo === 'WebSite') {
            $g['@graph'][$i] = array_merge($n, array(
                'description' => $s['titulo'],
                'creator' => array('@id' => SEO_FUNDADOR_ID),
                'copyrightHolder' => array('@id' => $org),
            ));
        }
    }
    $g['@graph'][] = array(
        '@type' => 'WebPage', '@id' => $sitio . '/#pagina', 'url' => $sitio . '/', 'name' => $s['titulo'],
        'isPartOf' => array('@id' => $sitio . '/#web'), 'about' => array('@id' => $sitio . '/#app'),
        'mainEntity' => array('@id' => $sitio . '/#app'), 'inLanguage' => 'es-MX',
        'primaryImageOfPage' => array('@type' => 'ImageObject', 'url' => $sitio . '/assets/img/og-image.png', 'width' => 1200, 'height' => 630),
        'breadcrumb' => array('@id' => $sitio . '/#migas'),
        'dateModified' => date('Y-m-d', (int) @filemtime(__DIR__ . '/index.php')),
        'publisher' => array('@id' => $org),
    );
    $g['@graph'][] = array(
        '@type' => 'BreadcrumbList', '@id' => $sitio . '/#migas',
        'itemListElement' => array(
            array('@type' => 'ListItem', 'position' => 1, 'name' => 'Alkes', 'item' => ALKES_SITIO . '/'),
            array('@type' => 'ListItem', 'position' => 2, 'name' => $s['nombre'], 'item' => $sitio . '/'),
        ),
    );
    $g['@graph'][] = seo_fundador();
    return $g;
}


$AHORRO  = a_ahorro_anual();
$URL_APP = a_url_panel();
$PROD    = 'mantis';

$DESDE_TXT = '$' . a_num(ma_tarifa_min());

$TITULO  = 'Mantis: software de mantenimiento preventivo y correctivo | Alkes';
$DESC    = 'Planifica, ejecuta y documenta el mantenimiento de equipos e instalaciones con plantillas checklist, evidencias y trazabilidad. Desde ' . $DESDE_TXT . ' MXN por usuario al mes. Pruébalo.';
$DESC_OG = 'Planifica, ejecuta y documenta el mantenimiento de equipos e instalaciones con plantillas checklist, evidencias y trazabilidad. Desde ' . $DESDE_TXT . ' MXN por usuario al mes.';

/* Capacidades en pestañas: icono, título y descripción de cada punto. */
$CAPACIDADES = array(
    array('id' => 'plantillas', 'tab' => 'Plantillas', 'eyebrow' => 'Plantillas',
        'h3' => 'Plantillas a la medida para cada tipo de activo',
        'p' => 'Diseña plantillas con secciones e ítems configurables. Casillas de verificación, listas desplegables, campos de texto e informativos se combinan para crear el checklist que tu proceso necesita.',
        'items' => array(
            array('checklist', 'Ítems configurables', 'Casillas, listas desplegables, texto libre e informativos.'),
            array('history', 'Versionado de plantillas', 'Cada cambio incrementa la versión. El historial queda asociado al mantenimiento ejecutado.'),
            array('box', 'Asociadas al activo', 'Cada equipo queda ligado a la plantilla de mantenimiento que le corresponde.'),
        ),
        'img' => 'assets/img/plantilla.jpg', 'alt' => 'Editor de plantillas de mantenimiento de Mantis con secciones e ítems configurables', 'w' => 1568, 'h' => 768,
        'cap' => 'Editor de plantillas: secciones e ítems configurables'),
    array('id' => 'ejecucion', 'tab' => 'Ejecución', 'eyebrow' => 'Ejecución',
        'h3' => 'Ejecución en tiempo real, con evidencia',
        'p' => 'Ejecuta mantenimientos con formularios dinámicos generados a partir de tus plantillas, desde la computadora o el celular en campo. Al editar, cada respuesta se guarda al instante y el reporte en PDF sale listo para el cliente.',
        'items' => array(
            array('camera', 'Evidencias multimedia', 'Fotos, videos, PDF, Word y Excel: arrástralos, elígelos o pégalos, y revisa las fotos en galería.'),
            array('cloud-check', 'Guardado automático', 'En un mantenimiento ya registrado, cada cambio se guarda al salir del campo.'),
            array('users', 'Técnicos de campo', 'Cada técnico con su usuario: registra a su nombre y completa sus mantenimientos pendientes.'),
        ),
        'img' => 'assets/img/ejecucion.jpg', 'alt' => 'Formulario dinámico de ejecución de un mantenimiento en Mantis', 'w' => 1568, 'h' => 768,
        'cap' => 'Ejecución de un mantenimiento con guardado automático'),
    array('id' => 'control', 'tab' => 'Control', 'eyebrow' => 'Control',
        'h3' => 'El estado de tus activos, en un panel',
        'p' => 'Activos vencidos y por vencer, mantenimientos del mes y por completar, los próximos servicios y la actividad reciente. La información que necesita quien decide, sin pedirla.',
        'items' => array(
            array('chart', 'Indicadores en el panel', 'Vencidos, por vencer, del mes y por completar, cada uno con acceso directo a su listado.'),
            array('filter', 'Filtros y búsqueda', 'Un filtro en cada columna de los listados y una búsqueda que recorre activos, clientes, mantenimientos y plantillas.'),
            array('history', 'Auditoría completa', 'Quién hizo qué y cuándo, y el historial de cada registro en línea de tiempo.'),
        ),
        'img' => 'assets/img/panel.jpg', 'alt' => 'Panel principal de Mantis con indicadores de mantenimiento', 'w' => 1568, 'h' => 768,
        'cap' => 'Panel principal con indicadores de la operación'),
    array('id' => 'seguimiento', 'tab' => 'Seguimiento', 'eyebrow' => 'Seguimiento',
        'h3' => 'Sabes qué toca hoy, y te avisa',
        'p' => 'Cada activo tiene su frecuencia y Mantis calcula cuándo le toca el siguiente servicio. Lo ves en el calendario y en el tablero, y la campana te avisa de lo que pasa con tus equipos.',
        'items' => array(
            array('calendar', 'Calendario de mantenimientos', 'Servicios realizados y próximos en vista de mes, semana o lista; los vencidos, en rojo.'),
            array('layers', 'Tablero por vencimiento', 'Vencidos, esta semana, al día y sin frecuencia, con un mantenimiento nuevo a un clic.'),
            array('bell', 'Avisos que tú eliges', 'Asignaciones, mantenimientos completados, notas nuevas y activos vencidos; cada usuario decide cuáles recibe.'),
        ),
        'img' => 'assets/img/calendario.jpg', 'alt' => 'Calendario de mantenimientos de Mantis con servicios realizados y próximos', 'w' => 1568, 'h' => 768,
        'cap' => 'Calendario: realizados y próximos de cada activo'),
);

$SOSTIENE = array(
    array('box', 'Gestión de activos', 'Equipos, maquinaria e instalaciones con identificador, tipo, ubicación, frecuencia y plantilla asociada, y su hoja de vida imprimible.'),
    array('building', 'Clientes y multiempresa', 'Los activos de cada cliente agrupados, y tu información aislada de la de cualquier otra cuenta.'),
    array('qr', 'Identificación por QR', 'Cada activo con su código QR: escanéalo con la cámara del celular y abre su ficha al instante.'),
    array('user-check', 'Roles y permisos', 'Administrador, supervisor y técnico: cada quien ve y hace solo lo que le corresponde.'),
    array('form', 'Notas en cada registro', 'Lo que encontraste o lo que falta queda escrito en el activo o el mantenimiento, como una conversación.'),
    array('book', 'Manual dentro del sistema', 'La ayuda se abre en la pantalla donde estás, con buscador.'),
);

$AVANZADAS = array(
    array('search', 'Búsqueda en todo el sistema', 'Escribe un activo, un cliente o un técnico en la barra de arriba, desde cualquier pantalla; solo aparece lo que tú puedes abrir.'),
    array('report', 'Hoja de vida del activo', 'Sus mantenimientos con su PDF, el historial de cambios en línea de tiempo y sus notas; se imprime como documento con el logo de tu empresa.'),
    array('database', 'Tablas de alto rendimiento', 'Procesamiento en el servidor con búsqueda, orden y paginación incluso con miles de registros.'),
    array('display', 'Apariencia a tu gusto', 'Tema claro, oscuro o automático, 20 colores de énfasis y un modo compacto para ver más filas.'),
    array('user-check', 'Mi perfil y ajustes', 'Tus datos y tus servicios del mes, cambio de contraseña, cerrar sesión en otros equipos y tus avisos.'),
    array('lock', 'Sesiones y contraseñas protegidas', 'Los permisos de cada rol se revisan en todas las pantallas y listados.'),
);

function ma_items($lista)
{
    $h = '';
    foreach ($lista as $i) {
        $h .= '<div class="item">' . a_icon($i[0]) . '<div><h4>' . a_e($i[1]) . '</h4><p>' . a_e($i[2]) . '</p></div></div>';
    }
    return $h;
}
?>
<?php
// SEO y GEO: datos para seo_head() y seo_enriquecer() (ver seo_head).
$SEO_PAG = seo_de_pagina(MA_SITIO, isset($CAPACIDADES) ? $CAPACIDADES : array(), array($SOSTIENE, $AVANZADAS));
$SEO = array(
    'sitio' => MA_SITIO, 'nombre' => 'Mantis', 'titulo' => $TITULO, 'noindex' => $NOINDEX,
    'subcategoria' => 'Gestión de mantenimiento (CMMS)', 'dispositivos' => 'Computadora, teléfono y tableta', 'requisitos' => 'Navegador web moderno con conexión a internet',
    'alt' => 'Vista de Mantis, software en la nube de Alkes',
    'palabras' => array('software de mantenimiento', 'CMMS', 'mantenimiento preventivo', 'mantenimiento correctivo', 'checklist de mantenimiento', 'órdenes de servicio', 'código QR para activos', 'gestión de activos', 'bitácora de mantenimiento', 'reporte de servicio en PDF', 'software para técnicos', 'sistema en la nube', 'SaaS México', 'software Guadalajara', 'Alkes'),
    'version' => seo_version(__DIR__), 'funciones' => $SEO_PAG['funciones'], 'capturas' => $SEO_PAG['capturas'],
);
?>
<!doctype html>
<html lang="es" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= a_e($TITULO) ?></title>

<meta name="description" content="<?= a_e($DESC) ?>">
<meta name="author" content="Alkes">
<meta name="theme-color" content="#061c6b">
<meta name="format-detection" content="telephone=no">
<?php if ($NOINDEX): ?><meta name="robots" content="noindex,nofollow">
<?php endif; ?>
<link rel="canonical" href="<?= MA_SITIO ?>/">

<meta property="og:type" content="website">
<meta property="og:site_name" content="Mantis">
<meta property="og:url" content="<?= MA_SITIO ?>/">
<meta property="og:title" content="<?= a_e($TITULO) ?>">
<meta property="og:description" content="<?= a_e($DESC_OG) ?>">
<meta property="og:image" content="<?= MA_SITIO ?>/assets/img/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Mantis, software de mantenimiento preventivo y correctivo">
<meta property="og:locale" content="es_MX">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= a_e($TITULO) ?>">
<meta name="twitter:description" content="<?= a_e($DESC_OG) ?>">
<meta name="twitter:image" content="<?= MA_SITIO ?>/assets/img/og-image.png">
<?= seo_head($SEO) ?>
<link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">

<link rel="preload" href="assets/fonts/lexend-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/source-sans-3-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/mantis.css">

<script type="application/ld+json"><?= ma_jsonld($SEO) ?></script>

<script>
(function () {
    var r = document.documentElement;
    r.className = r.className.replace('no-js', 'js');
    try {
        var t = localStorage.getItem('tema');
        if (t === 'dark' || t === 'light') { r.setAttribute('data-theme', t); }
    } catch (e) {}
})();
</script>
</head>
<body>

<a class="skip" href="#main">Saltar al contenido</a>

<header class="nav" id="nav">
    <div class="container nav__in">
        <a class="brand" href="#inicio"><?= ma_logo(34) ?><span>Mantis</span></a>
        <a class="brand__by" href="<?= ALKES_SITIO ?>">por <strong>Alkes</strong></a>
        <nav class="nav__links" aria-label="Secciones">
            <a class="nav__link" href="#producto">Producto</a>
            <a class="nav__link" href="#capacidades">Capacidades</a>
            <a class="nav__link" href="#precios">Precios</a>
            <a class="nav__link" href="#preguntas">Preguntas</a>
            <a class="nav__link" href="#novedades">Novedades</a>
            <a class="nav__link" href="#contacto">Contacto</a>
        </nav>
        <div class="nav__actions">
            <?= a_tema() ?>
            <a class="btn btn--line btn--sm" href="<?= a_e($URL_APP) ?>">Iniciar sesión</a>
            <a class="btn btn--primary btn--sm" href="#precios">Ver precios</a>
        </div>
        <button class="nav__burger" type="button" id="navToggle" aria-expanded="false" aria-controls="navPanel" aria-label="Abrir menú">
            <?= a_icon('menu', 'i-menu') ?><?= a_icon('close', 'i-close') ?>
        </button>
    </div>
    <div class="nav__panel container" id="navPanel">
        <a class="nav__link" href="#producto">Producto</a>
        <a class="nav__link" href="#capacidades">Capacidades</a>
        <a class="nav__link" href="#precios">Precios</a>
        <a class="nav__link" href="#preguntas">Preguntas</a>
        <a class="nav__link" href="#novedades">Novedades</a>
        <a class="nav__link" href="#contacto">Contacto</a>
        <a class="nav__link" href="#ecosistema">Ecosistema Alkes</a>
        <div class="nav__panel-actions">
            <?= a_tema() ?>
            <a class="btn btn--line" href="<?= a_e($URL_APP) ?>">Iniciar sesión</a>
            <a class="btn btn--primary" href="#precios">Ver precios</a>
        </div>
    </div>
</header>

<main id="main">

<!-- Hero ------------------------------------------------------------------ -->
<section class="hero" id="inicio">
    <div class="container">
        <div class="hero__grid">
            <div class="hero__body">
                <p class="eyebrow" data-reveal>Mantenimiento preventivo y correctivo para áreas de mantenimiento y proveedores de servicio</p>
                <h1 data-reveal>Ningún mantenimiento vuelve a <em>olvidarse</em></h1>
                <p class="lede" data-reveal>Planifica, ejecuta y documenta el mantenimiento de equipos e instalaciones, con plantillas checklist a la medida, evidencias fotográficas y trazabilidad de cada intervención. Desde <?= a_e($DESDE_TXT) ?> MXN por usuario al mes.</p>
                <div class="row" data-reveal>
                    <a class="btn btn--primary btn--lg" href="<?= a_e($URL_APP) ?>" data-open-demo><?= a_icon('play') ?>Probar el demo</a>
                    <a class="btn btn--line btn--lg" href="#precios">Ver precios</a>
                </div>
                <div class="row" data-reveal>
                    <a class="link" href="<?= a_e(ma_url_agendar()) ?>">Agendar demostración guiada<?= a_icon('arrow') ?></a>
                    <a class="link" href="#novedades">Ver novedades<?= a_icon('arrow') ?></a>
                </div>
                <ul class="hero__facts" data-reveal>
                    <li><?= a_icon('check') ?>Plantillas ilimitadas</li>
                    <li><?= a_icon('check') ?>Multiempresa</li>
                    <li><?= a_icon('check') ?>Auditoría completa</li>
                </ul>
            </div>
            <div class="hero__aside" data-reveal>
                <div class="scene" data-scene>
  <svg class="scene__svg" viewBox="0 0 560 420" role="img" aria-label="Animación: en un taller, un técnico aprieta una tuerca floja de un compresor y la máquina deja de vibrar; luego toma una foto con el celular, la envía y la orden de trabajo se cierra con palomitas de confirmación.">
    <defs>
      <clipPath id="sc-tk"><rect x="84" y="214" width="150" height="102" rx="51"/></clipPath>
      <clipPath id="sc-tr"><rect x="384" y="200" width="48" height="80" rx="14"/></clipPath>
      <clipPath id="p2-tr"><path d="M387 213Q387 200 401 200H417Q431 200 431 213L428 277H390Z"/></clipPath>
    </defs>
    <g class="sc-cam">
      <rect class="sc-wall" x="0" y="0" width="560" height="330"/>
      <rect class="sc-wall2" x="0" y="252" width="560" height="78"/>
      <path class="sc-line" d="M0 252H560"/>
      <g><rect class="sc-sky" x="22" y="24" width="120" height="94" rx="8"/><path class="sc-win" d="M82 24V118M22 71H142"/><rect class="sc-winf" x="22" y="24" width="120" height="94" rx="8"/>
         <polygon class="sc-beam" points="30,118 134,118 300,330 150,330"/></g>
      <rect class="sc-board" x="452" y="146" width="92" height="104" rx="8"/><circle class="sc-hole" cx="466" cy="160" r="1.6"/><circle class="sc-hole" cx="481" cy="160" r="1.6"/><circle class="sc-hole" cx="496" cy="160" r="1.6"/><circle class="sc-hole" cx="511" cy="160" r="1.6"/><circle class="sc-hole" cx="526" cy="160" r="1.6"/><circle class="sc-hole" cx="541" cy="160" r="1.6"/><circle class="sc-hole" cx="466" cy="180" r="1.6"/><circle class="sc-hole" cx="481" cy="180" r="1.6"/><circle class="sc-hole" cx="496" cy="180" r="1.6"/><circle class="sc-hole" cx="511" cy="180" r="1.6"/><circle class="sc-hole" cx="526" cy="180" r="1.6"/><circle class="sc-hole" cx="541" cy="180" r="1.6"/><circle class="sc-hole" cx="466" cy="200" r="1.6"/><circle class="sc-hole" cx="481" cy="200" r="1.6"/><circle class="sc-hole" cx="496" cy="200" r="1.6"/><circle class="sc-hole" cx="511" cy="200" r="1.6"/><circle class="sc-hole" cx="526" cy="200" r="1.6"/><circle class="sc-hole" cx="541" cy="200" r="1.6"/><circle class="sc-hole" cx="466" cy="220" r="1.6"/><circle class="sc-hole" cx="481" cy="220" r="1.6"/><circle class="sc-hole" cx="496" cy="220" r="1.6"/><circle class="sc-hole" cx="511" cy="220" r="1.6"/><circle class="sc-hole" cx="526" cy="220" r="1.6"/><circle class="sc-hole" cx="541" cy="220" r="1.6"/><circle class="sc-hole" cx="466" cy="240" r="1.6"/><circle class="sc-hole" cx="481" cy="240" r="1.6"/><circle class="sc-hole" cx="496" cy="240" r="1.6"/><circle class="sc-hole" cx="511" cy="240" r="1.6"/><circle class="sc-hole" cx="526" cy="240" r="1.6"/><circle class="sc-hole" cx="541" cy="240" r="1.6"/><path class="sc-tool" d="M470 158v40M464 158a6 6 0 0 1 12 0"/><path class="sc-tool" d="M492 158v38"/><rect class="sc-toolf" x="484" y="154" width="16" height="9" rx="2"/><path class="sc-tool" d="M516 158l-8 34M516 158l8 34"/><circle class="sc-toolf" cx="516" cy="158" r="4"/><rect class="sc-toolf" x="462" y="214" width="26" height="9" rx="3"/><rect class="sc-toolf" x="494" y="214" width="26" height="9" rx="3"/><rect class="sc-toolf" x="526" y="214" width="12" height="9" rx="3"/>
      <rect class="sc-floor" x="0" y="330" width="560" height="90"/>
      <path class="sc-line" d="M0 330H560"/>
      <path class="sc-tile" d="M0 356H560M0 384H560M120 330L72 420M290 330L284 420M450 330L500 420"/>
      <ellipse class="sc-shd" cx="160" cy="333" rx="104" ry="8"/><ellipse class="sc-shd" cx="408" cy="333" rx="36" ry="5"/>
      <g class="sc-all">
        <g class="sc-mach"><g class="mach-in">
          <rect class="sc-b" x="98" y="312" width="24" height="18" rx="3"/><rect class="sc-b" x="196" y="312" width="24" height="18" rx="3"/>
          <rect class="sc-b" x="84" y="214" width="150" height="102" rx="51"/>
          <rect class="sc-sh" x="168" y="214" width="70" height="102" clip-path="url(#sc-tk)"/>
          <circle class="sc-rv" cx="110" cy="246" r="2.4"/><circle class="sc-rv" cx="110" cy="284" r="2.4"/><circle class="sc-rv" cx="208" cy="246" r="2.4"/><circle class="sc-rv" cx="208" cy="284" r="2.4"/>
          <rect class="sc-b" x="118" y="158" width="86" height="58" rx="10"/>
          <rect class="sc-sh" x="164" y="158" width="40" height="58" rx="10"/>
          <path class="sc-p" d="M130 172H192M130 184H192M130 196H192"/>
          <rect class="sc-b" x="132" y="150" width="58" height="10" rx="4"/>
          <g class="sc-fly"><circle class="sc-b" cx="100" cy="186" r="22"/><path class="sc-p" d="M100 168v36M82 186h36M87 173l26 26M113 173L87 199"/><circle class="sc-dot" cx="100" cy="186" r="4"/></g>
          <path class="sc-hose" d="M204 176C232 170 244 190 238 222"/>
          <circle class="sc-b" cx="161" cy="124" r="17"/>
          <path class="sc-tick" d="M149 130l-3 3M153 118l-2-3M161 112v-4M169 118l2-3M173 130l3 3"/>
          <g class="sc-needle"><path class="sc-n" d="M161 124l8-8"/></g><circle class="sc-dot" cx="161" cy="124" r="2.4"/>
          <rect class="sc-b" x="232" y="226" width="42" height="22" rx="4"/>
          <g class="sc-nut"><polygon class="sc-nutp" points="291,237 286.5,244.8 277.5,244.8 273,237 277.5,229.2 286.5,229.2"/><circle class="sc-dot" cx="282" cy="237" r="2.6"/></g>
          <rect class="sc-plate" x="112" y="250" width="52" height="22" rx="4"/><text class="sc-txt" x="138" y="265.500" text-anchor="middle">A-12</text>
          <circle class="sc-led" cx="190" cy="262" r="5"/><circle class="sc-ledring" cx="190" cy="262" r="5"/>
          <g class="sc-vib"><path class="sc-vp" d="M64 196q-10 14 0 28M52 188q-16 22 0 44"/><path class="sc-vp" d="M258 192q10 14 0 28"/></g>
        </g>
        <g class="sc-puffs"><circle class="sc-puff" style="--i:0" cx="214" cy="146" r="5"/><circle class="sc-puff" style="--i:1" cx="224" cy="132" r="4"/><circle class="sc-puff" style="--i:2" cx="208" cy="124" r="3.4"/></g>
        <g class="sc-drips"><circle class="sc-drop" style="--i:0" cx="282" cy="252" r="3"/><circle class="sc-drop" style="--i:1" cx="278" cy="252" r="2.4"/></g>
        </g>
        <g class="sc-spark"><path class="sc-sp" d="M296 214l-4-10M306 220l6-8M310 236h10M298 256l-4 8"/></g>
        <g class="sc-finder"><path class="sc-fp" d="M66 150v-20h20M250 130h20v20M66 318v20h20M270 318v20h-20"/></g>
        <rect class="sc-flash" x="62" y="126" width="212" height="214" rx="14"/>
        <g class="sc-tag"><rect class="sc-tagb" x="76" y="62" width="164" height="34" rx="17"/><circle class="sc-tagc" cx="98" cy="79" r="10"/><path class="sc-ck" d="M93 79l3.500 3.500 6.500-7"/><text class="sc-txt2" x="116" y="84">Orden cerrada</text></g>
        <g class="sc-tech"><g class="mt" transform="translate(0,0)">
      <g class="sc-armb mt-b"><path class="p2-sleeve" style="stroke-width:13" d="M426 220L398 226"/><path class="p2-fore" style="stroke-width:9" d="M398 226L373 218.500"/><rect class="sc-phone" x="354" y="196" width="14" height="26" rx="3"/><rect class="sc-screen" x="356.500" y="199" width="9" height="19" rx="1.500"/><ellipse class="p2-skin" cx="369.500" cy="217.500" rx="6" ry="5.200" transform="rotate(-14 369.500 217.500)"/></g>
      <g class="sc-legl mt-ll">
        <path class="sc-pant" d="M390.500 270H405.500L404 323H391.500Z"/>
        <path class="sc-shoe" d="M406 322L392 322Q386 324 382 329Q380.500 332.500 385 332.500H408Q411.500 332.500 411.500 329Q411.500 324 406 322Z"/>
        <path class="p2-sole" d="M381.500 330.200H411.200L411.400 332.500H384Q381.600 332.500 381.500 330.200Z"/>
      </g>
      <g class="sc-legr mt-lr">
        <path class="sc-pant" d="M411.500 270H426.500L425 323H412.500Z"/>
        <path class="p2-shade" d="M411.500 270H426.500L425 323H412.500Z"/>
        <path class="sc-shoe" d="M427 322L413 322Q407 324 403 329Q401.500 332.500 406 332.500H429Q432.500 332.500 432.500 329Q432.500 324 427 322Z"/>
        <path class="p2-sole" d="M402.500 330.200H432.200L432.400 332.500H405Q402.600 332.500 402.500 330.200Z"/>
      </g>
      <g class="sc-body">
        <path class="sc-shirt" d="M387 213Q387 200 401 200H417Q431 200 431 213L428 277H390Z"/>
        <path class="sc-shirt2" clip-path="url(#p2-tr)" d="M414 196H436V281H408Q415 240 414 196Z"/>
        <path class="p2-placket" d="M408 211V268"/>
        <path class="sc-belt" d="M390 268H428L428.200 275H389.800Z"/><rect class="sc-buckle" x="404" y="268" width="8" height="7" rx="1.500"/>
        <rect class="sc-pouch" x="420" y="270" width="15" height="16" rx="3"/>
        <path class="p2-lan" d="M405 211L411 233M417 211L412 233"/><rect class="p2-badge" x="406" y="232" width="12" height="15" rx="2.500"/><rect class="p2-badgel" x="408.500" y="235" width="7" height="2.200" rx="1.100"/><rect class="p2-badgel" style="opacity:.45" x="408.500" y="239.500" width="7" height="1.800" rx=".9"/>
        <path class="p2-skin" d="M401.500 187H414.500V201Q408 207 401.500 201Z"/>
        <path class="p2-shade" d="M401.500 187H414.500V193Q408 197 401.500 193Z"/>
        <path class="p2-collar" d="M399.500 200.500L408 213L416.500 200.500L412 199Q408 203 404 199Z"/>
        <ellipse class="p2-skin" cx="408" cy="175" rx="15.500" ry="17.500"/>
        <path class="p2-hair" d="M392.500 176Q392 170 394 167L423.500 167Q425.500 172 424.500 178Q423 172 418 171Z" style="opacity:.9"/><path class="sc-hat" d="M390 172C389.500 158 397.500 150 408 150C418.500 150 426.500 158 426 172Z"/><path class="p2-shade" d="M408 150C418.500 150 426.500 158 426 172H415Q416 158 408 150Z"/><rect class="sc-hat" x="385" y="169.500" width="46" height="5.500" rx="2.700"/><rect class="sc-lamp" x="389" y="160" width="8" height="7" rx="2"/>
        <ellipse class="p2-skin" cx="418.200" cy="180" rx="3.200" ry="4.200"/>
        <path class="p2-shade" d="M416.800 178Q419.500 180 417 183"/>
        <ellipse class="p2-skin" cx="392.600" cy="180.400" rx="2.900" ry="3.300"/>
        <circle class="p2-blush" cx="397" cy="183.500" r="3.400"/>
        <g class="sc-eyeg mt-eye"><ellipse class="p2-eyew" cx="401.200" cy="177" rx="3.100" ry="3.300"/><circle class="p2-iris" cx="400.200" cy="177.300" r="1.900"/><circle class="p2-eyew" cx="399.500" cy="176.500" r=".65"/></g>
        <path class="p2-line" d="M396.500 172.300Q401 169.800 405.500 171.300"/>
        <path class="p2-mouth sc-m1 mt-m1" style="opacity:0;stroke-width:1.7" d="M398.5 187.8H403"/>
        <path class="p2-mouth sc-m2 mt-m2" d="M397 186.200Q401 191 406 186"/>
      </g>
      <g class="sc-arma mt-a">
        <path class="p2-sleeve" style="stroke-width:14" d="M391 214L364 222"/>
        <path class="p2-cuff" d="M365 221.700L362.500 222.700" style="stroke-width:12"/>
        <path class="p2-fore" style="stroke-width:9.500" d="M363.500 222.500L339 233.500"/>
        <ellipse class="p2-skin" cx="334.500" cy="235" rx="6.300" ry="5.400" transform="rotate(-24 334.500 235)"/>
        <ellipse class="p2-skin" cx="332.800" cy="229.300" rx="2.200" ry="4" transform="rotate(-38 332.800 229.300)"/>
        <g class="sc-wr"><path class="sc-wrench" d="M338 237H298"/><circle class="sc-ring" cx="282" cy="237" r="14"/></g>
        <circle class="sc-shirt" cx="392" cy="214.500" r="8.500" style="fill:var(--sc-shirt)"/>
      </g>
    </g>
        </g>
      </g>
    </g>
    <g class="sc-card">
      <rect class="sc-cardb" x="326" y="8" width="222" height="148" rx="18"/>
      <rect class="sc-logo" x="340" y="20" width="16" height="16" rx="4"/><path class="sc-ck2" d="M344 28l3 3 5-5.500"/>
      <text class="sc-t0" x="362" y="32.500">Mantis</text><text class="sc-t3" x="534" y="32.500" text-anchor="end">Orden de trabajo</text>
      <rect class="sc-thumb" x="340" y="44" width="70" height="52" rx="9"/>
      <g class="sc-ph"><rect class="sc-phd" x="348" y="52" width="54" height="36" rx="6"/><path class="sc-phi" d="M366 70h18M375 61v18"/></g>
      <g class="sc-pic"><rect class="sc-sky2" x="342" y="46" width="66" height="48" rx="8"/><rect class="sc-tm" x="352" y="64" width="40" height="22" rx="11"/><rect class="sc-tm" x="360" y="54" width="22" height="14" rx="3"/><rect class="sc-tm" x="394" y="72" width="10" height="7" rx="2"/><path class="sc-cam2" d="M342 54V50a4 4 0 0 1 4-4h6"/></g>
      <text class="sc-t1" x="420" y="58">Compresor A-12</text><text class="sc-t2" x="420" y="73">Mantenimiento</text><text class="sc-t2" x="420" y="86">preventivo</text>
      <g class="sc-chip c1"><rect class="sc-chipb" x="340" y="102" width="102" height="20" rx="10"/><circle class="sc-chipc" cx="351" cy="112" r="6"/><path class="sc-ck3" d="M348 112l2.200 2.200 4-4.400"/><text class="sc-chipt" x="361" y="115.500">Tuerca ajustada</text></g>
      <g class="sc-chip c2"><rect class="sc-chipb" x="447" y="102" width="40" height="20" rx="10"/><circle class="sc-chipc" cx="458" cy="112" r="6"/><path class="sc-ck3" d="M455 112l2.200 2.200 4-4.400"/><text class="sc-chipt" x="468" y="115.500">Foto</text></g>
      <g class="sc-chip c3"><rect class="sc-chipb" x="492" y="102" width="44" height="20" rx="10"/><circle class="sc-chipc" cx="503" cy="112" r="6"/><path class="sc-ck3" d="M500 112l2.200 2.200 4-4.400"/><text class="sc-chipt" x="513" y="115.500">Lista</text></g>
      <g class="sc-send"><rect class="sc-sendb" x="340" y="128" width="196" height="20" rx="10"/><text class="sc-sendt" x="438" y="142" text-anchor="middle">Enviar</text></g>
      <g class="sc-prog"><rect class="sc-track" x="340" y="128" width="196" height="20" rx="10"/><rect class="sc-fill" x="340" y="128" width="196" height="20" rx="10"/></g>
      <g class="sc-sent"><rect class="sc-sentb" x="340" y="128" width="196" height="20" rx="10"/><circle class="sc-sentc" cx="418" cy="138" r="6.500"/><path class="sc-ck3" d="M414.500 138l2.500 2.500 4.500-5"/><text class="sc-sentt" x="430" y="142">Enviado</text></g>
      <circle class="sc-finger" cx="470" cy="140" r="8"/>
    </g>
  </svg>
</div>
                <p class="hero__note"><?= a_icon('paperclip') ?><span><strong>Evidencias multimedia</strong>Fotos, PDF, documentos y videos en cada mantenimiento.</span></p>
            </div>
        </div>
    </div>
</section>

<!-- Qué es ---------------------------------------------------------------- -->
<section class="section section--tight" id="producto">
    <div class="container">
        <h2 class="sr-only">Qué es Mantis, para quién es y qué resuelve</h2>
        <div class="mosaic" data-reveal>
            <div class="card card--big">
                <p class="eyebrow">Qué es</p>
                <h3>Un sistema de mantenimiento, no una hoja de cálculo</h3>
                <p>Registras activos, diseñas plantillas checklist y ejecutas mantenimientos con formularios dinámicos que se guardan en tiempo real.</p>
                <p class="muted">Mantis concentra el mantenimiento de tus equipos en un solo lugar: qué activo toca, quién lo atiende, qué se encontró y qué evidencia queda. Con versionado de plantillas, reporte en PDF para el cliente y auditoría de cada movimiento.</p>
            </div>
            <div class="card card--md">
                <p class="eyebrow">Para quién</p>
                <h3>Quien da mantenimiento, propio o a terceros</h3>
                <p class="muted">Áreas de mantenimiento internas y proveedores de servicio: jefaturas, supervisores y técnicos de campo que cuidan equipos, maquinaria e instalaciones, propios o de sus clientes.</p>
            </div>
            <div class="card card--md">
                <p class="eyebrow">Qué resuelve</p>
                <h3>El mantenimiento sin registro</h3>
                <p class="muted">Elimina los mantenimientos olvidados y documenta cada inspección con evidencias y registros listos para auditorías externas.</p>
            </div>
        </div>
    </div>
</section>

<!-- Capacidades ----------------------------------------------------------- -->
<section class="section" id="capacidades">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Capacidades</p>
            <h2>De la inspección improvisada al proceso estandarizado</h2>
            <p class="lede">Mantis se adapta a tus procesos de mantenimiento específicos, no al revés.</p>
        </header>

        <div data-reveal>
            <div class="tabs" role="tablist" aria-label="Capacidades de Mantis">
                <?php foreach ($CAPACIDADES as $i => $c): ?>
                <button class="tab" type="button" role="tab" id="tab-<?= a_e($c['id']) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"<?= $i === 0 ? '' : ' tabindex="-1"' ?> aria-controls="panel-<?= a_e($c['id']) ?>"><?= a_e($c['tab']) ?></button>
                <?php endforeach; ?>
            </div>
            <?php foreach ($CAPACIDADES as $i => $c): ?>
            <div class="cap-panel" role="tabpanel" id="panel-<?= a_e($c['id']) ?>" aria-labelledby="tab-<?= a_e($c['id']) ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                <div class="split">
                    <div class="split__body">
                        <p class="eyebrow"><?= a_e($c['eyebrow']) ?></p>
                        <h3 style="font-size:clamp(1.5rem,1.4vw + 1rem,2rem)"><?= a_e($c['h3']) ?></h3>
                        <p class="muted"><?= a_e($c['p']) ?></p>
                        <ul class="feat-list">
                            <?php foreach ($c['items'] as $it): ?>
                            <li><span class="ico"><?= a_icon($it[0]) ?></span><div><strong><?= a_e($it[1]) ?></strong><p><?= a_e($it[2]) ?></p></div></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="split__media">
                        <?= ma_ventana($c['img'], $c['alt'], $c['w'], $c['h']) ?>
                        <p class="cap"><?= a_e($c['cap']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="group" data-reveal>
            <h3>Y todo lo que sostiene la operación</h3>
            <div class="items items--3"><?= ma_items($SOSTIENE) ?></div>
            <details class="more">
                <summary>Ver todas las capacidades avanzadas<?= a_icon('chevron') ?></summary>
                <h3 style="margin-top:22px;margin-bottom:0">Capacidades avanzadas</h3>
                <div class="items items--3"><?= ma_items($AVANZADAS) ?></div>
            </details>
        </div>
    </div>
</section>

<!-- Por qué Mantis -------------------------------------------------------- -->
<section class="section section--sunken" id="resultados">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Por qué Mantis</p>
            <h2>Maximiza la vida útil de tus activos</h2>
            <p class="lede">Diseñado para eliminar los mantenimientos olvidados y documentar cada inspección, para que tu equipo ejecute planes preventivos de forma ordenada y medible.</p>
        </header>

        <div class="why" data-reveal>
            <div class="why__col">
                <p class="eyebrow">Lo que ganas y lo que documentas</p>
                <h3>Mantis convierte cada inspección en un registro</h3>
                <p class="muted">Un plan preventivo programado, checklists uniformes y evidencia en cada servicio: tu equipo previene fallas y deja constancia de lo que hizo.</p>
                <ul class="checks">
                    <li><div><strong>Reduce tiempos muertos</strong><p>Previene fallas con mantenimiento preventivo programado.</p></div></li>
                    <li><div><strong>Estandariza procesos</strong><p>Checklists uniformes para todas las inspecciones.</p></div></li>
                    <li><div><strong>Mejora continua</strong><p>Datos históricos para optimizar frecuencias y procesos.</p></div></li>
                    <li><div><strong>Listo para auditorías</strong><p>Evidencias y registros para auditorías externas.</p></div></li>
                    <li><div><strong>Documentación visual</strong><p>Fotos y archivos adjuntos en cada mantenimiento.</p></div></li>
                    <li><div><strong>Decisiones informadas</strong><p>Indicadores claros en el panel: vencidos, por vencer, del mes y por completar.</p></div></li>
                </ul>
            </div>
            <div class="why__col">
                <p class="eyebrow">Integración con tus procesos</p>
                <p class="muted">Mantis se adapta a tu forma de trabajar, no al revés.</p>
                <div>
                    <details class="disc" open>
                        <summary class="disc__sum"><span class="disc__t">Plantillas a la medida de tu proceso</span><?= a_icon('chevron', 'disc__chev') ?></summary>
                        <div class="disc__body"><p>Diseñas el checklist de cada tipo de activo con secciones e ítems configurables, y al cambiarlo los mantenimientos anteriores conservan la versión con la que se hicieron.</p></div>
                    </details>
                    <details class="disc">
                        <summary class="disc__sum"><span class="disc__t">Un usuario para cada técnico</span><?= a_icon('chevron', 'disc__chev') ?></summary>
                        <div class="disc__body"><p>Cada técnico registra a su nombre y completa sus mantenimientos pendientes, desde el celular en campo o desde la computadora.</p></div>
                    </details>
                    <details class="disc">
                        <summary class="disc__sum"><span class="disc__t">Cada rol ve lo suyo</span><?= a_icon('chevron', 'disc__chev') ?></summary>
                        <div class="disc__body"><p>Administrador, supervisor y técnico: los botones de nuevo, guardar y eliminar aparecen solo a quien tiene permiso.</p></div>
                    </details>
                    <details class="disc">
                        <summary class="disc__sum"><span class="disc__t">Tu información, separada de la de otras empresas</span><?= a_icon('chevron', 'disc__chev') ?></summary>
                        <div class="disc__body"><p>Tu información queda aislada de la de cualquier otra cuenta, y los activos de cada cliente se agrupan por separado.</p></div>
                    </details>
                </div>
                <div class="dif">
                    <p class="eyebrow">La diferencia Mantis</p>
                    <p class="lede">No ofrecemos solo un software, ofrecemos una solución completa a tus necesidades de mantenimiento. Con soporte directo del equipo que lo desarrolla y actualizaciones incluidas, tu inversión evoluciona contigo.</p>
                </div>
            </div>
        </div>

        <div class="facts" data-reveal>
            <div class="fact"><b>Auditoría completa</b><span>Quién hizo qué y cuándo.</span></div>
            <div class="fact"><b>Sin instalar nada</b><span>Se entra desde el navegador.</span></div>
            <div class="fact"><b>Plantillas ilimitadas</b><span>Para todos los usuarios.</span></div>
            <div class="fact"><b>Evidencia en cada servicio</b><span>Fotos, videos, PDF, Word y Excel.</span></div>
        </div>
    </div>
</section>

<!-- Precios --------------------------------------------------------------- -->
<section class="section" id="precios">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Precios</p>
            <h2>Un precio por usuario, sin planes</h2>
            <p class="lede">Pagas por cada usuario con cuenta activa, y el precio baja con cada usuario que sumas. Cada usuario incluye hasta 20 activos, plantillas ilimitadas, soporte y las actualizaciones que se publiquen.</p>
        </header>
        <div data-reveal><?= ma_tarifas_html(ALKES_CENTRAL . '/?producto=mantis') ?></div>
        <ul class="plan-foot" data-reveal>
            <li><?= a_icon('check') ?>Plantillas ilimitadas</li>
            <li><?= a_icon('check') ?>Hasta 20 activos por usuario</li>
            <li><?= a_icon('check') ?>Soporte y mantenimiento incluidos</li>
            <li><?= a_icon('check') ?>Actualizaciones sin costo adicional</li>
            <li><?= a_icon('check') ?>Sin instalaciones ni servidores propios</li>
            <li><?= a_icon('check') ?>Precios en pesos mexicanos</li>
        </ul>
    </div>
</section>

<!-- Preguntas frecuentes -------------------------------------------------- -->
<section class="section section--sunken" id="preguntas">
    <div class="container">
        <div class="faq">
            <div class="faq__intro" data-reveal>
                <p class="eyebrow">Preguntas frecuentes</p>
                <h2>Lo que más nos preguntan sobre Mantis</h2>
                <p class="muted">¿Tienes otra duda? Escríbenos a <a href="mailto:<?= ALKES_MAIL ?>"><?= ALKES_MAIL ?></a>.</p>
            </div>
            <div class="faq__list" data-reveal>
                <?php foreach (ma_faq() as $i => $f): ?>
                <details class="disc"<?= $i === 0 ? ' open' : '' ?>>
                    <summary class="disc__sum"><span class="disc__t"><?= a_e($f['q']) ?></span><?= a_icon('chevron', 'disc__chev') ?></summary>
                    <div class="disc__body"><p><?= a_e($f['a']) ?></p></div>
                </details>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Confianza ------------------------------------------------------------- -->
<section class="section" id="confianza">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Confianza</p>
            <h2>Un equipo con nombre, correo y teléfono</h2>
            <p class="lede">Mantis es un producto de Alkes. Nuestro equipo desarrolla, opera y da soporte al mismo sistema.</p>
        </header>
        <div class="tiles" data-reveal>
            <div class="tile"><?= a_icon('cloud') ?><h3>Plataforma en la nube</h3><p>Sin instalaciones ni servidores propios. Entras por el navegador y empiezas a trabajar.</p></div>
            <div class="tile"><?= a_icon('history') ?><h3>Auditoría completa</h3><p>Creación, edición y eliminación quedan registradas con autor, fecha y resultado.</p></div>
            <div class="tile"><?= a_icon('refresh') ?><h3>Actualizaciones incluidas</h3><p>Soporte, mantenimiento y nuevas funciones sin costo adicional durante tu periodo.</p></div>
            <div class="tile"><?= a_icon('lock') ?><h3>Tus datos, separados</h3><p>Tu información queda aislada de la de cualquier otra cuenta, con permisos por rol.</p></div>
        </div>
        <div class="team" data-reveal>
            <div>
                <h3>Soporte del equipo que construye Mantis</h3>
                <p>Nuestro equipo desarrolla, opera y da soporte a Mantis de principio a fin. Para soporte técnico, una demostración guiada o un módulo a la medida de tu operación, hablas directamente con el equipo que construye el sistema.</p>
            </div>
            <div class="team__links">
                <a class="btn btn--primary" href="<?= a_e(ma_url_agendar()) ?>"><?= a_icon('mail') ?>Escribir al equipo</a>
                <a class="btn btn--line" href="tel:<?= ALKES_TEL ?>"><?= a_icon('phone') ?><?= ALKES_TEL_TXT ?></a>
                <a class="btn btn--line" href="<?= MA_WHATSAPP ?>" target="_blank" rel="noopener">WhatsApp<?= a_icon('external') ?></a>
            </div>
        </div>
    </div>
</section>

<!-- Novedades ------------------------------------------------------------- -->
<section class="section section--sunken" id="novedades">
    <div class="container narrow">
        <header class="head" data-reveal>
            <p class="eyebrow">Novedades</p>
            <h2>El producto se mueve, y queda por escrito</h2>
            <p class="lede">Historial público de versiones: lo que se entregó, cuándo y qué cambia en tu trabajo diario.</p>
        </header>
        <div data-reveal>
                        <details class="disc disc--latest" open>
                <summary class="disc__sum">
                    <span class="disc__v">v2.3.0</span>
                    <span class="disc__t">Un mantenimiento completado ya no se modifica</span>
                    <span class="disc__d">26 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Botón Completar</strong><p>Completar un mantenimiento es una acción con su botón, que revisa el activo, la fecha y los puntos obligatorios del checklist.</p></div></li>
                    <li><div><strong>Historial confiable</strong><p>Un mantenimiento completado ya no se edita, no regresa a pendiente ni se elimina. Puedes seguir agregando notas y evidencias.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.2.1</span>
                    <span class="disc__t">Mejora interna del manejo de archivos</span>
                    <span class="disc__d">24 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Sin cambios en tu forma de trabajar</strong><p>Se ordenó el código que guarda tus archivos para que sea el mismo en todos los sistemas de Alkes.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.2.0</span>
                    <span class="disc__t">Mi nube: tus archivos en un solo lugar</span>
                    <span class="disc__d">23 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Mi nube</strong><p>En el menú de tu nombre, después de «Mi perfil y ajustes»: todos tus archivos con el mantenimiento al que pertenecen, para verlos, descargarlos, borrarlos o subir archivos sueltos. Cada quien ve solo lo suyo.</p></div></li>
                    <li><div><strong>Tu espacio, siempre a la vista</strong><p>Cada usuario tiene su propio espacio y una barra te dice cuánto llevas usado, con aviso cuando se está llenando.</p></div></li>
                    <li><div><strong>Evidencias en tabla</strong><p>Las evidencias de un mantenimiento se ven en una tabla con búsqueda y orden. Las borra quien las subió, mientras el mantenimiento se pueda editar, o un administrador.</p></div></li>
                    <li><div><strong>Archivos mejor protegidos</strong><p>Cada archivo se revisa por su contenido real y solo se descarga con permiso.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.7</span>
                    <span class="disc__t">Captura más a prueba de errores</span>
                    <span class="disc__d">17 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Activos sin identificador repetido</strong><p>Dos activos de tu cuenta ya no pueden compartir identificador.</p></div></li>
                    <li><div><strong>Mantenimientos coherentes</strong><p>Un activo dado de baja no recibe mantenimientos nuevos y un mantenimiento completado no puede tener fecha futura.</p></div></li>
                    <li><div><strong>Teléfono del cliente</strong><p>Se revisa que tenga un formato válido.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.6</span>
                    <span class="disc__t">Las evidencias se ven en el visor</span>
                    <span class="disc__d">16 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>PDF, video, audio y texto en el modal</strong><p>Las evidencias de un mantenimiento se abren en un visor con botón Descargar, sin salir de la pantalla. Las imágenes siguen en la galería.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.5</span>
                    <span class="disc__t">El visor de PDF funciona en el celular</span>
                    <span class="disc__d">16 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>PDF dentro del mismo visor</strong><p>En Chrome para Android y otros navegadores sin visor propio, el PDF ahora se ve en el modal, página por página, con su botón Descargar.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.4</span>
                    <span class="disc__t">«Recuérdame» en hasta 5 equipos</span>
                    <span class="disc__d">16 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Cada equipo se recuerda por separado</strong><p>Puedes recordar hasta 5 equipos a la vez; al recordar el sexto se olvida el que lleva más tiempo sin usarse.</p></div></li>
                    <li><div><strong>Lista en Mi perfil</strong><p>En Mi perfil › Seguridad ves tus equipos recordados con navegador, sistema, IP y último uso, y puedes olvidar el que quieras.</p></div></li>
                    <li><div><strong>Más seguro</strong><p>Cerrar sesión olvida solo ese equipo, y cambiar la contraseña o dar de baja a un usuario olvida sus equipos recordados. Cada persona inicia sesión una vez más y vuelve a marcar «Recordarme».</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.3</span>
                    <span class="disc__t">Acceso cómodo en el celular</span>
                    <span class="disc__d">16 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Acceso cómodo en el celular</strong><p>La pantalla de acceso y la de recuperar contraseña se ajustan a cualquier tamaño, en vertical y en horizontal, sin hacer scroll ni tapar la tarjeta con el teclado.</p></div></li>
                    <li><div><strong>Código de recuperación más claro</strong><p>El botón dice «Enviar código», muestra a qué correo se mandó y permite reenviarlo.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.2</span>
                    <span class="disc__t">Tablas con el mismo control de orden</span>
                    <span class="disc__d">15 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Un solo control de orden</strong><p>La tabla de mantenimientos recientes y el modal de selección ordenan con el mismo menú que el resto de las tablas.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.1</span>
                    <span class="disc__t">Botones iguales y en el mismo orden en todos los módulos</span>
                    <span class="disc__d">15 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Cada botón, siempre del mismo color</strong><p>Guardar y Borrador en azul, Procesar en verde, Cancelar y Eliminar en rojo, todas las autorizaciones en amarillo, Imprimir y Regresar en gris. Los mismos colores en todos los módulos.</p></div></li>
                    <li><div><strong>Siempre en el mismo orden</strong><p>De izquierda a derecha, por relevancia: primero guardar, luego las acciones, las cancelaciones y las autorizaciones. Al extremo derecho van Enviar, Información y Regresar.</p></div></li>
                    <li><div><strong>Sin menús escondidos</strong><p>Todos los botones están a la vista, uno por uno, rellenos y con su ícono. Ya no hay botones de solo contorno ni menús «Más».</p></div></li>
                    <li><div><strong>Mejor en el celular</strong><p>En pantallas chicas los botones muestran solo su ícono para que quepan, y la acción principal conserva su texto.</p></div></li>
                    <li><div><strong>El botón de correo, más corto</strong><p>Ahora se llama simplemente Enviar.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.1.0</span>
                    <span class="disc__t">Envía reportes, cardex y fichas por correo desde Mantis</span>
                    <span class="disc__d">15 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Del mantenimiento al correo de tu cliente</strong><p>Con el botón Enviar mandas el reporte de un mantenimiento completado, el cardex de un activo o la ficha de un cliente. El PDF va como archivo adjunto.</p></div></li>
                    <li><div><strong>Revisas antes de enviar</strong><p>Una ventana te muestra el correo del cliente, el asunto y el mensaje para que los ajustes, y eliges qué secciones incluye el PDF.</p></div></li>
                    <li><div><strong>Con el nombre de tu empresa</strong><p>El correo sale a nombre de tu empresa, con tu firma y tu pie. Las respuestas de tus clientes llegan al correo que elijas.</p></div></li>
                    <li><div><strong>Con tu propio servidor de correo</strong><p>Si lo prefieres, configura el servidor de correo de tu empresa para que los envíos salgan desde tu dominio y sin el límite de uso justo.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.5</span>
                    <span class="disc__t">Formularios más claros, con ícono en cada campo</span>
                    <span class="disc__d">14 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Un ícono en cada campo</strong><p>Cada dato que capturas lleva su ícono al lado izquierdo, para reconocer de un vistazo qué se pide.</p></div></li>
                    <li><div><strong>Nombre y campo en la misma línea</strong><p>El nombre del campo queda a la izquierda y el campo a su derecha, en todos los formularios y ventanas.</p></div></li>
                    <li><div><strong>Tarjetas con título e ícono</strong><p>Cada bloque de una pantalla se identifica con su ícono y su título.</p></div></li>
                    <li><div><strong>Configuración y datos para facturar con el mismo orden</strong><p>Las pantallas de Configuración, suscripción y datos para facturar siguen el mismo acomodo que el resto del sistema.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.4</span>
                    <span class="disc__t">Reportes, cardex y fichas en PDF con el nombre de tu organización</span>
                    <span class="disc__d">14 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Reporte de mantenimiento renovado</strong><p>La lista de verificación, los problemas y recomendaciones, las evidencias y las notas salen en un documento limpio, listo para entregar al cliente.</p></div></li>
                    <li><div><strong>Cardex y ficha del activo</strong><p>El historial de mantenimientos del activo, con su código QR y la fecha del próximo vencimiento.</p></div></li>
                    <li><div><strong>Ficha del cliente</strong><p>Sus datos de contacto, sus activos y sus mantenimientos recientes en un solo documento.</p></div></li>
                    <li><div><strong>Tú eliges qué incluye</strong><p>El botón Imprimir, al pie del formulario, abre una ventana donde marcas las secciones y ves cuántos elementos tiene cada una. Recuerda tu última elección.</p></div></li>
                    <li><div><strong>Se ve sin salir de la pantalla</strong><p>El PDF se abre en una ventana, con la opción de descargarlo. Arriba lleva el logo y el nombre de tu organización; Mantis aparece solo como un crédito discreto al pie.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.3</span>
                    <span class="disc__t">Cobro por usuario, cupo editable y roles que se suman</span>
                    <span class="disc__d">13 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <ul>
                    <li><div><strong>Tú decides cuántos usuarios contratas</strong><p>Ya no hay planes ni topes por tipo de usuario. Contratas un número de usuarios y cada persona activa ocupa un lugar, sea cual sea su rol.</p></div></li>
                    <li><div><strong>Sin tope de activos</strong><p>Registra todos los activos que necesites: el cupo se mide solo por usuarios.</p></div></li>
                    <li><div><strong>Cambia tu cupo desde Configuración</strong><p>El administrador sube o baja el total de varios en varios y ve la vista previa del cobro antes de confirmar. Al subir, se cobra al momento la parte proporcional; al bajar, el cambio se aplica en la renovación.</p></div></li>
                    <li><div><strong>Aviso cuando el cupo se llena</strong><p>Dar de alta o reactivar a alguien con el cupo lleno no genera ningún cobro: el sistema avisa y tú decides si amplías.</p></div></li>
                    <li><div><strong>Roles que se suman</strong><p>Una misma persona puede ser administrador, supervisor y técnico a la vez. El supervisor consulta Usuarios en solo lectura y el técnico ve Clientes y su propia auditoría.</p></div></li>
                    <li><div><strong>Cardex imprimible del activo</strong><p>El historial completo de mantenimientos de un activo, listo para imprimir.</p></div></li>
                    <li><div><strong>Datos para facturar</strong><p>En Configuración puedes capturar, si los necesitas, los datos fiscales de tu suscripción.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.2</span>
                    <span class="disc__t">La misma forma de trabajar en Mantis, SmartPend y GeoCheck</span>
                    <span class="disc__d">8 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <h4 class="disc__group">Resumen de la versión</h4>
                    <ul>
                    <li><div><strong>El registro abierto, en su tarjeta</strong><p>El activo, cliente o mantenimiento que consultas sale en el encabezado de la tarjeta principal del formulario, con su estado. Arriba ya no se repite el nombre del módulo.</p></div></li>
                    <li><div><strong>Manual de usuario desde tu nombre</strong><p>La ayuda se abre desde el menú de tu nombre, arriba a la derecha, y ya no ocupa espacio al pie del menú lateral.</p></div></li>
                    <li><div><strong>Abrir un registro con el ojo</strong><p>En los listados de activos, clientes, plantillas y usuarios, el botón para abrir un registro es un ojo, igual que en mantenimientos.</p></div></li>
                    <li><div><strong>Filtros por columna también en las tablas internas</strong><p>Las tablas dentro de cada formulario, como los mantenimientos de un activo, traen el mismo filtro por columna que los listados.</p></div></li>
                    <li><div><strong>Suscripción en español y maximizar junto al buscador</strong><p>El estado de tu suscripción sale en español, y el botón para ver un listado a pantalla completa está a un lado de Buscar.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.1</span>
                    <span class="disc__t">Cada rol ve solo los botones que puede usar</span>
                    <span class="disc__d">6 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <h4 class="disc__group">Resumen de la versión</h4>
                    <ul>
                    <li><div><strong>El técnico cierra sus propios servicios</strong><p>Edita y completa los mantenimientos asignados a él mientras sigan pendientes, incluso si empezó subiendo fotos. Al completarlo queda en solo lectura; corregirlo o reabrirlo es de un supervisor o administrador.</p></div></li>
                    <li><div><strong>Cada quien ve solo lo que puede hacer</strong><p>Los botones de nuevo, guardar y eliminar aparecen únicamente a quien tiene permiso, y la ficha de un activo se muestra en solo lectura a quien no puede modificarla.</p></div></li>
                    <li><div><strong>Revisión de seguridad entre empresas</strong><p>Se comprobó, pantalla por pantalla, que ninguna empresa puede ver ni modificar la información de otra.</p></div></li>
                    </ul>
                </div>
            </details>

            <details class="hist">
                <summary>Ver historial completo, de v2.0.0 a v1.0.0<?= a_icon('chevron') ?></summary>
                <div style="margin-top:10px">            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v2.0.0</span>
                    <span class="disc__t">Interfaz renovada, calendario, avisos y notas en cada activo</span>
                    <span class="disc__d">6 oct 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body">
                    <h4 class="disc__group">Resumen de la versión</h4>
                    <ul>
                    <li><div><strong>Interfaz renovada de punta a punta</strong><p>Mantis estrena diseño completo. El encabezado, el menú y el pie se quedan fijos mientras trabajas, y se usa igual de cómodo en el celular, en campo, que en la computadora. Tema claro, oscuro o automático, 20 colores de énfasis y un modo compacto para ver más filas.</p></div></li>
                    <li><div><strong>Calendario de mantenimientos</strong><p>Los servicios realizados y los próximos de cada activo en una vista de mes, semana o lista. Los vencidos salen en rojo; un clic abre el mantenimiento o el activo.</p></div></li>
                    <li><div><strong>Tablero de activos por vencimiento</strong><p>Tus activos en cuatro columnas: vencidos, los que vencen esta semana, al día y sin frecuencia. Desde cada tarjeta abres un mantenimiento nuevo para ese equipo.</p></div></li>
                    <li><div><strong>Avisos en la campana, los que tú elijas</strong><p>Te avisa cuando te asignan un mantenimiento, cuando se registra o se completa uno, cuando alguien deja una nota y cuando un activo vence. Un interruptor por tipo de aviso decide cuáles recibes.</p></div></li>
                    <li><div><strong>Notas en activos y mantenimientos</strong><p>Lo que encontraste, lo que falta o un aviso para el siguiente servicio queda escrito en el propio equipo, como una conversación. Se envía con Enter.</p></div></li>
                    <li><div><strong>Evidencias más rápidas de subir</strong><p>Arrastra las fotos, elígelas o pégalas con Ctrl+V, con barra de progreso. Se ven en cuadrícula y se recorren en galería.</p></div></li>
                    <li><div><strong>Búsqueda en todo el sistema</strong><p>Escribe un activo, un cliente o un técnico en la barra de arriba, desde cualquier pantalla. Los resultados llegan agrupados y solo aparece lo que tú puedes abrir.</p></div></li>
                    <li><div><strong>Un dashboard que dice qué atender</strong><p>Activos vencidos, los que vencen esta semana, mantenimientos del mes y por completar, cada uno con su acceso directo, más los próximos servicios y la actividad reciente.</p></div></li>
                    <li><div><strong>La hoja de vida del activo, completa</strong><p>En la ficha de cada equipo: sus mantenimientos con su PDF, el historial de cambios en línea de tiempo y sus notas. Se imprime como documento, con el logo de tu empresa.</p></div></li>
                    <li><div><strong>Mi perfil y ajustes, sin salir de lo que haces</strong><p>Desde tu nombre, arriba a la derecha: tus datos y tus servicios del mes, el cambio de contraseña, cerrar sesión en otros equipos, la apariencia, tus avisos y tu actividad.</p></div></li>
                    <li><div><strong>Manual de usuario dentro del sistema</strong><p>Abre la ayuda en la sección de la pantalla donde estás, con buscador.</p></div></li>
                    <li><div><strong>Errores claros en los formularios</strong><p>Si un dato falta o no es válido, el campo se marca en rojo con el mensaje debajo. En las ventanas largas, el título y los botones se quedan fijos.</p></div></li>
                    <li><div><strong>Cada quien ve solo lo que le toca</strong><p>Los permisos de cada rol se revisan en todas las pantallas y listados, y se reforzó la seguridad de las sesiones y de las contraseñas.</p></div></li>
                    </ul>
                    <h4 class="disc__group">Impacto en tu día a día</h4>
                    <ul>
                    <li><div><strong>Los mismos flujos, con otra cara</strong><p>Escanear el QR, registrar el mantenimiento y sacar el PDF se hace igual. Cerrar sesión cambia de lugar: ahora está en el menú de tu nombre.</p></div></li>
                    <li><div><strong>Los QR que ya pegaste siguen funcionando</strong><p>No hay que reimprimir ninguna etiqueta.</p></div></li>
                    <li><div><strong>Un activo sin servicios también vence</strong><p>Si nunca ha tenido un mantenimiento, le toca una frecuencia después de su fecha de alta. Algunos activos pueden aparecer como vencidos por primera vez.</p></div></li>
                    <li><div><strong>Para ordenar, el botón junto al título</strong><p>En los listados se ordena con el botón de cada columna, que además trae su propio filtro.</p></div></li>
                    <li><div><strong>Tu nombre y tu correo los cambia un administrador</strong><p>Desde Usuarios, como siempre. En tu perfil se ven, pero no se editan.</p></div></li>
                    </ul>
                </div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v1.0.1</span>
                    <span class="disc__t">Correcciones tras la prueba funcional</span>
                    <span class="disc__d">22 sep 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body"><ul>
                    <li><div><strong>El checklist no se pierde al subir la primera foto</strong><p>Si en un mantenimiento nuevo subías una evidencia antes de guardar, las respuestas ya capturadas se conservan.</p></div></li>
                    <li><div><strong>El cliente de un activo siempre se ve</strong><p>Aunque el cliente esté inactivo, su nombre sigue apareciendo en la ficha del activo.</p></div></li>
                    <li><div><strong>Opciones de respuesta correctas en las plantillas</strong><p>Las listas desplegables muestran exactamente las opciones que capturaste.</p></div></li>
                    <li><div><strong>Historial limpio</strong><p>Abrir un activo o un mantenimiento ya no registra cambios que nadie hizo.</p></div></li>
                </ul></div>
            </details>
            <details class="disc">
                <summary class="disc__sum">
                    <span class="disc__v">v1.0.0</span>
                    <span class="disc__t">Lanzamiento de Mantis</span>
                    <span class="disc__d">jul 2026</span>
                    <?= a_icon('chevron', 'disc__chev') ?>
                </summary>
                <div class="disc__body"><ul>
                    <li><div><strong>Activos con código QR</strong><p>Cada equipo con su cliente, ubicación, frecuencia y plantilla, y un QR que se escanea con la cámara para abrir su ficha.</p></div></li>
                    <li><div><strong>Plantillas checklist con versiones</strong><p>Secciones e ítems a la medida; al cambiar una plantilla, los mantenimientos anteriores conservan la versión con la que se hicieron.</p></div></li>
                    <li><div><strong>Mantenimientos con evidencias y PDF</strong><p>Formulario armado desde la plantilla, fotos y archivos adjuntos, y el reporte en PDF para el cliente.</p></div></li>
                    <li><div><strong>Aviso de vencidos</strong><p>La campana muestra los activos con el mantenimiento vencido.</p></div></li>
                    <li><div><strong>Usuarios con roles</strong><p>Administrador, supervisor y técnico, cada uno con su acceso, y la auditoría de quién hizo qué.</p></div></li>
                    <li><div><strong>Contratación en línea</strong><p>Planes por número de técnicos y activos, y la suscripción administrada desde Configuración.</p></div></li>
                </ul></div>
            </details>
</div>
            </details>
        </div>

        <div class="block">
            <header class="head head--sub" data-reveal>
                <p class="eyebrow">En camino</p>
                <h2 style="font-size:clamp(1.4rem,1vw + 1.1rem,1.8rem)">Lo que sigue</h2>
            </header>
            <div class="roadmap" data-reveal>
                <div class="roadmap__item"><h3>Avisos también por correo</h3><p>Los avisos de la campana (vencimientos, asignaciones y notas) te llegan aunque no estés dentro del sistema.</p></div>
                <div class="roadmap__item"><h3>Resumen de vencimientos</h3><p>Lo que vence en la semana, en un solo correo.</p></div>
                <div class="roadmap__item"><h3>Modo offline con sincronización posterior</h3><p>El técnico trabaja sin señal y sincroniza al volver.</p></div>
                <div class="roadmap__item"><h3>App instalable en el celular</h3><p>Mantis como app en el teléfono, sin abrir el navegador. El escaneo de QR y las fotos ya funcionan hoy desde el celular.</p></div>
            </div>
            <div class="notice" data-reveal><?= a_icon('alert') ?><p>Las fechas, el orden y la disponibilidad de esta funcionalidad están sujetos a cambios sin previo aviso.</p></div>
        </div>
    </div>
</section>

<!-- Tecnología ------------------------------------------------------------ -->
<section class="section section--tight" id="tecnologia">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Tecnología</p>
            <h2>Construido con tecnología de punta</h2>
            <p class="lede">Utilizamos tecnología moderna y robusta para garantizar el rendimiento, la seguridad y la escalabilidad.</p>
        </header>
        <div class="tech" data-reveal>
            <div><?= a_icon('server') ?><h3>Procesamiento</h3><p>Motor de procesamiento rápido, eficiente y con comunicación instantánea con el navegador.</p></div>
            <div><?= a_icon('database') ?><h3>Base de datos</h3><p>Base de datos de alto rendimiento, garantizando integridad, velocidad y transacciones seguras en cada operación.</p></div>
            <div><?= a_icon('display') ?><h3>Interfaz</h3><p>Diseño moderno y adaptable con tablas interactivas y alertas elegantes.</p></div>
            <div><?= a_icon('ethernet') ?><h3>Infraestructura</h3><p>Servidor web con protección anti DDoS y certificado de seguridad incluido.</p></div>
        </div>
    </div>
</section>

<!-- Cierre ---------------------------------------------------------------- -->
<section class="section section--tight">
    <div class="container">
        <div class="cta" data-reveal>
            <div>
                <p class="eyebrow">Empieza hoy</p>
                <h2>Profesionaliza el mantenimiento de tus activos</h2>
                <p class="lede">Entra al demo con las credenciales de prueba, o contrata por usuario y documenta tus procesos de mantenimiento desde el primer día.</p>
            </div>
            <div class="cta__act">
                <a class="btn btn--cta btn--lg" href="<?= a_e($URL_APP) ?>" data-open-demo><?= a_icon('play') ?>Probar el demo</a>
                <a class="btn btn--line btn--lg" href="#precios">Ver precios</a>
                <a class="link" href="<?= a_e(ma_url_agendar()) ?>">Agendar demostración guiada<?= a_icon('arrow') ?></a>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -------------------------------------------------------------- -->
<section class="section section--sunken" id="contacto">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Contacto</p>
            <h2>Hablemos</h2>
            <p class="lede">Soporte técnico, consultas o la integración de módulos adicionales. Tu mensaje llega a una bandeja real.</p>
        </header>
        <div class="contact" data-reveal>
            <a class="contact__item" href="mailto:<?= ALKES_MAIL ?>"><?= a_icon('mail') ?><span class="contact__k">Correo</span><span class="contact__v"><?= ALKES_MAIL ?></span><span class="contact__d">Respuesta en horario hábil.</span></a>
            <a class="contact__item" href="tel:<?= ALKES_TEL ?>"><?= a_icon('phone') ?><span class="contact__k">Teléfono</span><span class="contact__v"><?= ALKES_TEL_TXT ?></span><span class="contact__d">Para demostraciones guiadas.</span></a>
            <a class="contact__item" href="<?= MA_WHATSAPP ?>" target="_blank" rel="noopener"><?= a_icon('phone') ?><span class="contact__k">WhatsApp</span><span class="contact__v"><?= ALKES_TEL_TXT ?></span><span class="contact__d">Escríbenos por mensaje.</span></a>
            <a class="contact__item" href="<?= a_e($URL_APP) ?>"><?= a_icon('login') ?><span class="contact__k">Acceso</span><span class="contact__v">app.mantis.alkes.mx</span><span class="contact__d">Entra a tu cuenta de Mantis.</span></a>
        </div>
        <div class="redes" data-reveal>
            <span class="redes__k">Síguenos</span>
            <?php foreach (ma_redes() as $r): ?>
            <a href="<?= a_e($r['u']) ?>" target="_blank" rel="noopener"><?= a_e($r['n']) ?><?= a_icon('external') ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ecosistema ------------------------------------------------------------ -->
<section class="section section--tight" id="ecosistema">
    <div class="container">
        <header class="head" data-reveal>
            <p class="eyebrow">Ecosistema Alkes</p>
            <h2>Mantis no está solo</h2>
            <p class="lede">Cinco productos desarrollados, alojados y operados por Alkes. Mismo soporte, misma casa.</p>
        </header>
        <?= a_ecosistema_grid($PROD) ?>
    </div>
</section>

</main>

<footer class="footer">
    <div class="container footer__top">
        <div class="footer__brand">
            <span class="brand"><?= ma_logo(34) ?><span>Mantis</span></span>
            <p>Sistema de gestión de mantenimiento diseñado para la eficiencia, el control y la trazabilidad. Centraliza tus operaciones de mantenimiento preventivo y correctivo.</p>
        </div>
        <div class="footer__cols">
            <div class="footer__col">
                <h2 class="footer__h">Producto</h2>
                <ul>
                    <li><a href="#producto">Qué es</a></li>
                    <li><a href="#capacidades">Capacidades</a></li>
                    <li><a href="#precios">Precios</a></li>
                    <li><a href="#preguntas">Preguntas frecuentes</a></li>
                    <li><a href="#novedades">Novedades</a></li>
                    <li><a href="#tecnologia">Tecnología</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h2 class="footer__h">Accesos</h2>
                <ul>
                    <li><a href="<?= a_e($URL_APP) ?>">Iniciar sesión</a></li>
                    <li><a href="<?= a_e($URL_APP) ?>" data-open-demo>Probar el demo</a></li>
                    <li><a href="<?= ALKES_CENTRAL ?>">Contratar</a></li>
                    <li><a href="manual.html">Manual de usuario</a></li>
                    <li><a href="<?= ALKES_SITIO ?>">Alkes</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h2 class="footer__h">Legal</h2>
                <ul>
                    <li><a href="/terminos.html">Términos y condiciones</a></li>
                    <li><a href="/privacidad.html">Aviso de privacidad</a></li>
                    <li><a href="/cookies.html">Cookies</a></li>
                    <li><a href="/encargo-de-datos.html">Tratamiento de datos</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h2 class="footer__h">Síguenos</h2>
                <ul>
                    <?php foreach (ma_redes() as $r): ?>
                    <li><a href="<?= a_e($r['u']) ?>" target="_blank" rel="noopener"><?= a_e($r['n']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer__bot">
            <p>© <?= date('Y') ?> Alkes. Mantis es un producto de Alkes. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<?= ma_dialogo_demo($URL_APP) ?>

<script src="assets/js/mantis.js" defer></script>
</body>
</html>
