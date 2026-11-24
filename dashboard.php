<?php
require __DIR__ . '/includes/conexion.php';

$totalPacientes      = (int) $pdo->query('SELECT COUNT(*) FROM Pacientes')->fetchColumn();
$totalMedicos        = (int) $pdo->query('SELECT COUNT(*) FROM Medicos')->fetchColumn();
$totalEspecialidades = (int) $pdo->query('SELECT COUNT(*) FROM Especialidades')->fetchColumn();
$totalCitas          = (int) $pdo->query('SELECT COUNT(*) FROM Citas')->fetchColumn();

$citasPendientes = (int) $pdo->query("SELECT COUNT(*) FROM Citas WHERE estado = 'Pendiente'")->fetchColumn();

$sqlCitas = "
    SELECT
        c.id,
        c.fecha_hora,
        c.estado,
        p.nombre AS paciente_nombre,
        p.apellido AS paciente_apellido,
        m.nombre AS medico_nombre,
        m.apellido AS medico_apellido,
        e.nombre_esp
    FROM Citas c
    INNER JOIN Pacientes p ON c.id_paciente = p.id
    INNER JOIN Medicos m ON c.id_medico = m.id
    INNER JOIN Especialidades e ON m.id_especialidad = e.id
    ORDER BY c.fecha_hora ASC
";
$citas = $pdo->query($sqlCitas)->fetchAll();

$pacientesRecientes = $pdo->query('SELECT id, nombre, apellido, correo, telefono, creado_en FROM Pacientes ORDER BY id DESC LIMIT 5')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="barra">
    <div>
        <h2>Dashboard Administrativo</h2>
        <p class="subtitulo">Vista general de pacientes, agenda médica y especialidades de Clínica Central.</p>
    </div>
    <div class="acciones">
        <a class="btn btn-claro" href="index.php">Ver Pacientes</a>
        <a class="btn" href="crear.php">
            <span>+</span> Nuevo paciente
        </a>
    </div>
</div>

<div class="grid-stats">
    <div class="stat-card">
        <span class="stat-label">Pacientes Registrados</span>
        <span class="stat-valor"><?= $totalPacientes ?></span>
        <span class="stat-badge positivo">Tabla Pacientes</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Citas en Agenda</span>
        <span class="stat-valor"><?= $totalCitas ?></span>
        <span class="stat-badge info"><?= $citasPendientes ?> pendientes</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Médicos Activos</span>
        <span class="stat-valor"><?= $totalMedicos ?></span>
        <span class="stat-badge neutro">Staff médico</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Especialidades</span>
        <span class="stat-valor"><?= $totalEspecialidades ?></span>
        <span class="stat-badge neutro">Áreas clínicas</span>
    </div>
</div>

<div class="card-tabla" style="margin-bottom: 24px;">
    <div class="tabla-header">
        <div>
            <h3 style="font-size: 17px; font-weight: 700;">Agenda de Citas Médicas</h3>
            <p class="subtitulo">Pacientes, profesionales asignados, especialidad y estado actual.</p>
        </div>
        <span class="contador-registros"><strong><?= count($citas) ?></strong> citas registradas</span>
    </div>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Especialidad</th>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($citas)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b;">No hay citas registradas.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($citas as $cita): ?>
                    <?php
                        $estado = $cita['estado'];
                        $claseBadge = 'neutro';
                        if ($estado === 'Confirmada') $claseBadge = 'positivo';
                        elseif ($estado === 'Pendiente') $claseBadge = 'info';
                        elseif ($estado === 'Cancelada') $claseBadge = 'peligro-badge';
                    ?>
                    <tr>
                        <td><span class="badge-id">#<?= str_pad((string) $cita['id'], 3, '0', STR_PAD_LEFT) ?></span></td>
                        <td><strong><?= htmlspecialchars($cita['paciente_nombre'] . ' ' . $cita['paciente_apellido']) ?></strong></td>
                        <td>Dr(a). <?= htmlspecialchars($cita['medico_nombre'] . ' ' . $cita['medico_apellido']) ?></td>
                        <td><span class="badge-tel"><?= htmlspecialchars($cita['nombre_esp']) ?></span></td>
                        <td class="texto-suave"><?= htmlspecialchars($cita['fecha_hora']) ?></td>
                        <td><span class="stat-badge <?= $claseBadge ?>"><?= htmlspecialchars($estado) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card-tabla">
    <div class="tabla-header">
        <div>
            <h3 style="font-size: 17px; font-weight: 700;">Últimos Pacientes Registrados</h3>
            <p class="subtitulo">Acceso rápido para editar o revisar registros recientes.</p>
        </div>
        <a class="btn btn-claro btn-sm" href="index.php">Ir al directorio completo</a>
    </div>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th class="texto-derecha">Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pacientesRecientes as $p): ?>
                <tr>
                    <td><span class="badge-id">#<?= str_pad((string) $p['id'], 3, '0', STR_PAD_LEFT) ?></span></td>
                    <td><strong><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></strong></td>
                    <td class="texto-suave"><?= htmlspecialchars($p['correo']) ?></td>
                    <td>
                        <?php if (!empty($p['telefono'])): ?>
                            <span class="badge-tel"><?= htmlspecialchars($p['telefono']) ?></span>
                        <?php else: ?>
                            <span class="sin-dato">No registrado</span>
                        <?php endif; ?>
                    </td>
                    <td class="acciones">
                        <a class="btn btn-claro btn-sm" href="editar.php?id=<?= $p['id'] ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>