<?php
require __DIR__ . '/includes/conexion.php';

// Solo se permite eliminar por POST (evita borrados accidentales vía URL)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        // CORREGIDO: Se cambió 'usuarios' por 'Pacientes'
        $stmt = $pdo->prepare('DELETE FROM Pacientes WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}

header('Location: index.php?msg=eliminado');
exit;