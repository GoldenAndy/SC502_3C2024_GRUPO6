<?php
require __DIR__ . '/config/database.php'; // NO TOCAR, conecta a la BD de Drawzone

try {
    // 📌 Obtener conexión a la BD
    $pdo = Database::getConnection();

    // 📌 Consulta para obtener los primeros 5 usuarios
    $sql = "SELECT idUsuario, usuario, email, rol FROM usuarios LIMIT 5";
    $stmt = $pdo->query($sql);

    echo "<h2 style='color: green;'>✅ Conexión a la base de datos establecida correctamente!</h2>";

    // 🔹 Si hay usuarios, mostrarlos
    if ($stmt->rowCount() > 0) {
        echo "<h3>Usuarios registrados:</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #ddd;'><th>ID</th><th>Usuario</th><th>Email</th><th>Rol</th></tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['idUsuario']}</td>";
            echo "<td>{$row['usuario']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['rol']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay usuarios registrados en la base de datos.</p>";
    }
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error en la consulta a la base de datos: " . $e->getMessage() . "</h2>";
}
?>
