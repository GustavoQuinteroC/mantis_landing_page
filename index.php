<?php
$NOINDEX = false;

require_once __DIR__ . '/_mantis.php';
require_once __DIR__ . '/_seo.php';

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
// SEO y GEO: datos para seo_head() y seo_enriquecer() (ver _seo.php).
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
            <?php include __DIR__ . '/_novedades_recientes.php'; ?>
            <details class="hist">
                <summary>Ver historial completo, de v2.0.0 a v1.0.0<?= a_icon('chevron') ?></summary>
                <div style="margin-top:10px"><?php include __DIR__ . '/_novedades_historial.php'; ?></div>
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
                    <li><a href="/terminos/">Términos y condiciones</a></li>
                    <li><a href="/privacidad/">Aviso de privacidad</a></li>
                    <li><a href="/cookies/">Cookies</a></li>
                    <li><a href="/encargo-de-datos/">Tratamiento de datos</a></li>
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
