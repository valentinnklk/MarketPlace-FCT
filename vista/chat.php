<?php
// vista/chat.php
// Punto de entrada del chat. Enruta acciones AJAX y renderiza la interfaz.

session_start();

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../controladores/chatControlador.php';

$ctrl = new ChatControlador($conexion);

// ── Enrutado de acciones ──────────────────────────────
$accion = $_GET['accion'] ?? '';

if ($accion === 'abrir'    && $_SERVER['REQUEST_METHOD'] === 'POST') { $ctrl->abrirChat(); }
if ($accion === 'enviar'   && $_SERVER['REQUEST_METHOD'] === 'POST') { $ctrl->enviarMensaje(); }
if ($accion === 'polling'  && $_SERVER['REQUEST_METHOD'] === 'GET')  { $ctrl->polling(); }
if ($accion === 'noleidos' && $_SERVER['REQUEST_METHOD'] === 'GET')  { $ctrl->noLeidos(); }

// ── Carga de datos ────────────────────────────────────
$data = $ctrl->mostrarChat();

$chats       = $data['chats'];
$chat_activo = $data['chat_activo'];
$mensajes    = $data['mensajes'];
$usuario_id  = $data['usuario_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes · Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
    <style>
        html, body { height: 100%; margin: 0; }

        .chat-wrapper {
            display: flex;
            height: calc(100vh - 56px);
            max-width: 1100px;
            margin: 0 auto;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            background: #fff;
        }

        .sidebar {
            width: 320px;
            min-width: 260px;
            border-right: 1px solid #dee2e6;
            display: flex; flex-direction: column;
            flex-shrink: 0;
        }
        .sidebar-header {
            padding: 16px;
            font-weight: 700;
            font-size: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .sidebar-list { overflow-y: auto; flex: 1; }

        .chat-item {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f0f2f5;
            text-decoration: none; color: inherit;
            transition: background .12s;
        }
        .chat-item:hover, .chat-item.activa { background: #eef2ff; }

        .avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: #c7d2fe; color: #3730a3;
            font-weight: 700; font-size: .9rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }

        .chat-info { flex: 1; overflow: hidden; }
        .chat-nombre { font-weight: 600; font-size: .88rem; }
        .chat-ultimo {
            font-size: .75rem; color: #6b7280;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .badge-unread {
            background: #4f46e5; color: #fff;
            border-radius: 999px; font-size: .68rem;
            padding: 2px 6px; font-weight: 700; flex-shrink: 0;
        }

        .chat-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        .chat-cabecera {
            padding: 14px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex; align-items: center; gap: 10px;
            background: #fff;
        }
        .chat-cabecera h6 { margin: 0; font-weight: 700; }
        .chat-cabecera small { color: #6b7280; }

        .mensajes-area {
            flex: 1; overflow-y: auto;
            padding: 20px; background: #f9fafb;
            display: flex; flex-direction: column; gap: 10px;
        }

        .burbuja-wrap { display: flex; flex-direction: column; }
        .burbuja-wrap.mia  { align-items: flex-end; }
        .burbuja-wrap.suya { align-items: flex-start; }

        .burbuja {
            max-width: 65%; padding: 9px 14px;
            border-radius: 16px; font-size: .875rem; line-height: 1.45;
            word-break: break-word;
        }
        .burbuja.mia  { background: #4f46e5; color: #fff; border-bottom-right-radius: 4px; }
        .burbuja.suya { background: #fff; border: 1px solid #e5e7eb; border-bottom-left-radius: 4px; }

        .burbuja-meta { font-size: .68rem; color: #9ca3af; margin-top: 3px; padding: 0 3px; }

        .sin-seleccion {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: #d1d5db; text-align: center; gap: 8px;
        }
        .sin-seleccion svg { width: 56px; }

        .chat-footer {
            padding: 12px 16px;
            border-top: 1px solid #dee2e6;
            display: flex; gap: 8px; background: #fff;
        }
        .chat-footer textarea {
            flex: 1; resize: none; border: 1px solid #d1d5db;
            border-radius: 10px; padding: 9px 13px;
            font-size: .875rem; line-height: 1.4;
            height: 42px; max-height: 120px; font-family: inherit;
            outline: none; transition: border .15s;
        }
        .chat-footer textarea:focus { border-color: #4f46e5; }

        .btn-send {
            background: #4f46e5; color: #fff; border: none;
            border-radius: 10px; padding: 0 18px;
            font-size: .875rem; font-weight: 600;
            cursor: pointer; transition: background .15s; white-space: nowrap;
        }
        .btn-send:hover    { background: #4338ca; }
        .btn-send:disabled { background: #9ca3af; cursor: not-allowed; }

        @media (max-width: 640px) {
            .sidebar   { display: <?= $chat_activo ? 'none' : 'flex' ?>; width: 100%; }
            .chat-main { display: <?= $chat_activo ? 'flex' : 'none' ?>; }
        }
    </style>
</head>
<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Principal">
    <div id="contenido" role="main" class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <div class="d-flex gap-2 align-items-center">
            <a href="home.php"   class="btn btn-outline-light btn-sm">Inicio</a>
            <a href="perfil.php" class="btn btn-outline-light btn-sm"><i class="bi bi-person-fill" aria-hidden="true"></i> Mi perfil</a>
            <a href="chat.php"   class="btn btn-outline-light btn-sm position-relative">
                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Mensajes
                <span id="badge-navbar" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none">0</span>
            </a>
        </div>
    </div>
</nav>

<div class="chat-wrapper">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
        <div class="sidebar-header"><i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Mensajes</div>
        <div class="sidebar-list">
            <?php if (empty($chats)): ?>
                <p class="text-muted text-center p-4 small">
                    Aún no tienes conversaciones.<br>
                    Contacta con un prestador desde su servicio.
                </p>
            <?php else: ?>
                <?php foreach ($chats as $c):
                    $activa  = ($chat_activo && $chat_activo['id'] == $c['id']) ? 'activa' : '';
                    $inicial = mb_strtoupper(mb_substr($c['otro_nombre'], 0, 1));
                ?>
                    <a href="chat.php?chat_id=<?= (int) $c['id'] ?>" class="chat-item <?= $activa ?>">
                        <div class="avatar">
                            <?php if (!empty($c['otro_avatar'])): ?>
                                <img src="<?= htmlspecialchars($c['otro_avatar']) ?>" alt="">
                            <?php else: ?>
                                <?= $inicial ?>
                            <?php endif; ?>
                        </div>
                        <div class="chat-info">
                            <div class="chat-nombre"><?= htmlspecialchars($c['otro_nombre']) ?></div>
                            <div class="chat-ultimo">
                                <?= !empty($c['ultimo_mensaje'])
                                    ? htmlspecialchars($c['ultimo_mensaje'])
                                    : '<em>Sin mensajes aún</em>' ?>
                            </div>
                        </div>
                        <?php if ((int) $c['no_leidos'] > 0): ?>
                            <span class="badge-unread"><?= (int) $c['no_leidos'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- ── ÁREA PRINCIPAL ── -->
    <main class="chat-main">
        <?php if ($chat_activo): ?>

            <?php
                $es_cliente   = ($chat_activo['cliente_id'] == $usuario_id);
                $otro_nombre  = $es_cliente ? $chat_activo['prestador_nombre'] : $chat_activo['cliente_nombre'];
                $otro_avatar  = $es_cliente ? $chat_activo['prestador_avatar'] : $chat_activo['cliente_avatar'];
                $inicial_otro = mb_strtoupper(mb_substr($otro_nombre, 0, 1));
            ?>

            <!-- Cabecera -->
            <div class="chat-cabecera">
                <div class="avatar">
                    <?php if ($otro_avatar): ?>
                        <img src="<?= htmlspecialchars($otro_avatar) ?>" alt="">
                    <?php else: ?>
                        <?= $inicial_otro ?>
                    <?php endif; ?>
                </div>
                <div>
                    <h6><?= htmlspecialchars($otro_nombre) ?></h6>
                    <?php if (!empty($chat_activo['servicio_titulo'])): ?>
                        <small>Servicio: <?= htmlspecialchars($chat_activo['servicio_titulo']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            // ─────────────────────────────────────────────
            // CONTRATO VINCULADO A LA CONVERSACIÓN
            // ─────────────────────────────────────────────
            $contrato_chat = null;
            if (!empty($chat_activo['contrato_id'])) {
                $stmt = $conexion->prepare(
                    "SELECT id, cliente_id, servicio_id, estado,
                            fecha_servicio, confirmacion_cliente, confirmacion_prestador
                     FROM contratos
                     WHERE id = ?
                     LIMIT 1"
                );
                $stmt->execute([(int) $chat_activo['contrato_id']]);
                $contrato_chat = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Visibilidad del bloque de finalización
            $puede_finalizar  = false;
            $ya_confirmado_yo = false;
            if ($contrato_chat) {
                $en_estado_valido = in_array($contrato_chat['estado'], ['aceptado','en_proceso'], true);
                $fecha_pasada     = strtotime($contrato_chat['fecha_servicio']) <= time();
                $puede_finalizar  = $en_estado_valido && $fecha_pasada;

                if ($es_cliente) {
                    $ya_confirmado_yo = (int) $contrato_chat['confirmacion_cliente'] === 1;
                } else {
                    $ya_confirmado_yo = (int) $contrato_chat['confirmacion_prestador'] === 1;
                }
            }

            // Visibilidad del bloque de valoración (solo cliente)
            $ya_valorado = false;
            if ($contrato_chat && $contrato_chat['estado'] === 'completado' && $es_cliente) {
                $stmt = $conexion->prepare(
                    "SELECT id FROM valoraciones WHERE contrato_id = ? AND revisor_id = ? LIMIT 1"
                );
                $stmt->execute([(int) $contrato_chat['id'], $usuario_id]);
                $ya_valorado = (bool) $stmt->fetch();
            }
            ?>

            <!-- Bloque de finalización (servicio prestado, falta confirmar) -->
            <?php if ($puede_finalizar): ?>
                <div class="alert alert-warning rounded-0 mb-0 px-3 py-2">
                    <?php if ($ya_confirmado_yo): ?>
                        <small>
                            <i class="bi bi-check" aria-hidden="true"></i> Ya confirmaste. Esperando al
                            <?php echo $es_cliente ? 'prestador' : 'cliente'; ?>.
                        </small>
                    <?php else: ?>
                        <form method="POST" action="../controladores/finalizar_servicio.php"
                              class="d-flex gap-2 align-items-center mb-0">
                            <input type="hidden" name="contrato_id"     value="<?php echo (int) $contrato_chat['id']; ?>">
                            <input type="hidden" name="conversacion_id" value="<?php echo (int) $chat_activo['id']; ?>">
                            <small class="me-auto">¿El servicio se ha completado satisfactoriamente?</small>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Marcar como finalizado
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Bloque de valoración (contrato completado y soy el cliente) -->
            <?php if ($contrato_chat && $contrato_chat['estado'] === 'completado' && $es_cliente): ?>
                <div class="alert alert-success rounded-0 mb-0 px-3 py-2">
                    <?php if ($ya_valorado): ?>
                        <small>⭐ Ya has valorado este servicio.</small>
                    <?php else: ?>
                        <div class="d-flex gap-2 align-items-center">
                            <small class="me-auto">El servicio está completado. ¡Comparte tu experiencia!</small>
                            <a href="reseñaVista.php?contrato_id=<?php echo (int) $contrato_chat['id']; ?>"
                               class="btn btn-warning btn-sm">⭐ Dejar valoración</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Mensajes -->
            <div class="mensajes-area" id="mensajes-area">
                <?php foreach ($mensajes as $msg):
                    $clase = ($msg['remitente_id'] == $usuario_id) ? 'mia' : 'suya';
                ?>
                    <div class="burbuja-wrap <?= $clase ?>" data-id="<?= (int) $msg['id'] ?>">
                        <div class="burbuja <?= $clase ?>"><?= nl2br(htmlspecialchars($msg['contenido'])) ?></div>
                        <div class="burbuja-meta">
                            <?= $clase === 'mia' ? 'Tú' : htmlspecialchars($msg['remitente_nombre']) ?>
                            · <?= date('H:i', strtotime($msg['fecha_envio'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Input -->
            <div class="chat-footer">
                <textarea id="txt" placeholder="Escribe un mensaje... (Enter para enviar)" onkeydown="handleEnter(event)"></textarea>
                <button class="btn-send" id="btn-enviar" onclick="enviar()">Enviar <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
            </div>

            <script>
            const CHAT_ID    = <?= (int) $chat_activo['id'] ?>;
            const USUARIO_ID = <?= (int) $usuario_id ?>;
            let   ultimoId   = <?= !empty($mensajes) ? (int) end($mensajes)['id'] : 0 ?>;

            const area = document.getElementById('mensajes-area');
            const txt  = document.getElementById('txt');
            const btn  = document.getElementById('btn-enviar');

            area.scrollTop = area.scrollHeight;

            txt.addEventListener('input', () => {
                txt.style.height = 'auto';
                txt.style.height = Math.min(txt.scrollHeight, 120) + 'px';
            });

            function handleEnter(e) {
                if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviar(); }
            }

            async function enviar() {
                const contenido = txt.value.trim();
                if (!contenido) return;
                btn.disabled = true;

                const fd = new FormData();
                fd.append('chat_id',   CHAT_ID);
                fd.append('contenido', contenido);

                try {
                    const res  = await fetch('chat.php?accion=enviar', { method: 'POST', body: fd });
                    const data = await res.json();
                    if (data.ok) {
                        txt.value = '';
                        txt.style.height = '42px';
                        agregarBurbuja({ id: data.mensaje_id, remitente_id: USUARIO_ID,
                                         contenido, fecha_envio: new Date().toISOString() });
                        ultimoId = data.mensaje_id;
                    }
                } catch (e) {
                    alert('Error al enviar. Inténtalo de nuevo.');
                } finally {
                    btn.disabled = false;
                    txt.focus();
                }
            }

            function agregarBurbuja(msg) {
                const esMia  = msg.remitente_id == USUARIO_ID;
                const clase  = esMia ? 'mia' : 'suya';
                const hora   = new Date(msg.fecha_envio).toLocaleTimeString('es-ES', { hour:'2-digit', minute:'2-digit' });
                const texto  = esc(msg.contenido).replace(/\n/g, '<br>');
                const nombre = esMia ? 'Tú' : esc(msg.remitente_nombre ?? '');

                if (document.querySelector(`[data-id="${msg.id}"]`)) return;

                const div = document.createElement('div');
                div.className   = `burbuja-wrap ${clase}`;
                div.dataset.id  = msg.id;
                div.innerHTML   = `<div class="burbuja ${clase}">${texto}</div>
                                   <div class="burbuja-meta">${nombre} · ${hora}</div>`;
                area.appendChild(div);
                area.scrollTop = area.scrollHeight;
            }

            function esc(s) {
                return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }

            // ── Polling cada 3 s ──────────────────────────────────────────
            setInterval(async () => {
                try {
                    const res  = await fetch(`chat.php?accion=polling&chat_id=${CHAT_ID}&desde_id=${ultimoId}`);
                    const data = await res.json();
                    if (data.ok && data.mensajes.length) {
                        data.mensajes.forEach(m => {
                            agregarBurbuja(m);
                            ultimoId = Math.max(ultimoId, m.id);
                        });
                    }
                } catch (e) { /* silencioso */ }
            }, 3000);
            </script>

        <?php else: ?>
            <div class="sin-seleccion">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                    <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="small">Selecciona una conversación para empezar</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- Badge navbar no leídos -->
<script>
(async function actualizarBadge() {
    try {
        const res   = await fetch('chat.php?accion=noleidos');
        const data  = await res.json();
        const badge = document.getElementById('badge-navbar');
        if (data.total > 0) { badge.textContent = data.total; badge.style.display = ''; }
        else                 { badge.style.display = 'none'; }
    } catch (e) {}
    setTimeout(actualizarBadge, 5000);
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>
