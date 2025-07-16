<?php
// app/models/User.php
// Model untuk mengelola data pengguna

// Fungsi untuk mendapatkan semua pengguna
function getAllUsers($conn) {
    $users = [];
    $query = "SELECT id, username, is_admin, created_at FROM users ORDER BY created_at DESC";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Fungsi untuk mendapatkan data satu pengguna berdasarkan ID
function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, username, is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// Fungsi untuk memperbarui data pengguna
function updateUser($conn, $id, $username, $is_admin) {
    $stmt = $conn->prepare("UPDATE users SET username = ?, is_admin = ? WHERE id = ?");
    $stmt->bind_param("sii", $username, $is_admin, $id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }
    $stmt->close();
    return false;
}

// Fungsi untuk menghapus pengguna
function deleteUser($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }
    $stmt->close();
    return false;
}
?>
