<?php

function getValues($conn, $table, $column) {
    $sql = "SELECT $column FROM $table order by $column";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // wird die daten in einem array speichern
    $values = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $values;
}

function getValue($conn, $table, $column, $conditionColumn, $conditionValue) {
    $sql = "SELECT $column FROM $table WHERE $conditionColumn = :conditionValue LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':conditionValue', $conditionValue);
    $stmt->execute();
    $value = $stmt->fetch(PDO::FETCH_COLUMN);
    return $value;
}

function recordExists($conn, $table, $column, $value) {
    $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}

function addRecord($conn, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));

    $stmt = $conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

    $stmt->execute(array_values($data));

    $lastId = $conn->lastInsertId();

    return $lastId;
}

function generateTableFromQuery($stmt) {
    // führe die übergebene Query aus
    $table = '<table class="table mt-3">';

    // Kopfzeile mit Spaltennamen aus der Query generieren
    $table .= '<thead><tr>';
    for ($i = 0; $i < $stmt->columnCount(); $i++) {
        $columnMeta = $stmt->getColumnMeta($i);
        $table .= '<th>' . htmlspecialchars($columnMeta['name']) . '</th>';
        
    }
    $table .= '</tr></thead>';

    //datenzeilen generieren
    $table .= '<tbody>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $table .= '<tr>';
        foreach ($row as $cell) {
            $table .= '<td>' . $cell . '</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</tbody>';

    //schließe die Tabelle
    $table .= '</table>';

    return $table;
}
