<?php
require __DIR__ . '/includes/conexion.php';

$Pacientes = $pdo->query('SELECT id, nombre, apellido, correo, telefono, creado_en FROM Pacientes ORDER BY id DESC')->fetchAll();
$totalCitas = (int) $pdo->query('SELECT COUNT(*) FROM Citas')->fetchColumn();
$totalEspecialidades = (int) $pdo->query('SELECT COUNT(*) FROM Especialidades')->fetchColumn();

$mensajes = [
    'creado'      => 'Paciente creado correctamente.',
    'actualizado' => 'Paciente actualizado correctamente.',
    'eliminado'   => 'Paciente eliminado correctamente.',
];
$msg = $_GET['msg'] ?? '';

$coloresAvatar = ['', 'avatar-teal', 'avatar-indigo', 'avatar-amber'];

require __DIR__ . '/includes/header.php';
?>

<?php if (isset($mensajes[$msg])): ?>
    <div class="mensaje"><?= htmlspecialchars($mensajes[$msg]) ?></div>
<?php endif; ?>

<div class="barra">
    <div>
        <h2>Directorio de Pacientes</h2>
        <p class="subtitulo">Administra los registros clínicos, datos de contacto y seguimiento.</p>
    </div>
    <a class="btn" href="crear.php">
        <span>+</span> Nuevo paciente
    </a>
</div>

<div class="grid-stats">
    <div class="stat-card">
        <span class="stat-label">Total Pacientes</span>
        <span class="stat-valor"><?= count($Pacientes) ?></span>
        <span class="stat-badge positivo">Activos en sistema</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Citas Programadas</span>
        <span class="stat-valor"><?= $totalCitas ?></span>
        <span class="stat-badge info">Base de datos</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Especialidades</span>
        <span class="stat-valor"><?= $totalEspecialidades ?></span>
        <span class="stat-badge neutro">Clínica Central</span>
    </div>
</div>

<?php if (empty($Pacientes)): ?>
    <div class="vacio">No hay pacientes registrados.</div>
<?php else: ?>
    <div class="card-tabla">
        <div class="tabla-header">
            <div class="buscador">
                <input type="text" id="buscarPaciente" placeholder="Buscar por nombre, correo o teléfono..." onkeyup="filtrarTabla()">
            </div>
            <span class="contador-registros">Mostrando <strong><?= count($Pacientes) ?></strong> registros</span>
        </div>

        <div class="tabla-responsive">
            <table id="tablaPacientes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Correo electrónico</th>
                        <th>Teléfono</th>
                        <th>Fecha de registro</th>
                        <th class="texto-derecha">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($Pacientes as $i => $u): ?>
                    <?php
                        $iniciales = strtoupper(
                            mb_substr($u['nombre'], 0, 1) . mb_substr($u['apellido'], 0, 1)
                        );
                        $claseAvatar = $coloresAvatar[$i % count($coloresAvatar)];
                    ?>
                    <tr>
                        <td><span class="badge-id">#<?= str_pad((string) $u['id'], 3, '0', STR_PAD_LEFT) ?></span></td>
                        <td>
                            <div class="paciente-info">
                                <div class="avatar <?= $claseAvatar ?>"><?= htmlspecialchars($iniciales) ?></div>
                                <div>
                                    <div class="paciente-nombre"><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></div>
                                    <div class="paciente-estado"><?= !empty($u['telefono']) ? 'Paciente activo' : 'Sin teléfono' ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="texto-suave"><?= htmlspecialchars($u['correo']) ?></td>
                        <td>
                            <?php if (!empty($u['telefono'])): ?>
                                <span class="badge-tel"><?= htmlspecialchars($u['telefono']) ?></span>
                            <?php else: ?>
                                <span class="sin-dato">No registrado</span>
                            <?php endif; ?>
                        </td>
                        <td class="texto-suave"><?= htmlspecialchars($u['creado_en']) ?></td>
                        <td class="acciones">
                            <a class="btn btn-claro btn-sm" href="editar.php?id=<?= $u['id'] ?>">Editar</a>
                            <form method="post" action="eliminar.php"
                                  onsubmit="return confirm('¿Eliminar este paciente?');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-peligro btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>