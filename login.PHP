<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "gov"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Requête préparée pour vérifier les informations de connexion
    $stmt = $conn->prepare("SELECT IdAdmin, NomComplet, Poste FROM Administration WHERE Email = ? AND MotPasse = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Récupérer les informations de l'utilisateur
        $user = $result->fetch_assoc();

        // Stocker les informations utilisateur dans la session
        $_SESSION['IdAdmin'] = $user['IdAdmin'];
        $_SESSION['NomComplet'] = $user['NomComplet'];
        $_SESSION['Poste'] = $user['Poste'];

        // Rediriger vers la page principale après une connexion réussie
        header("Location: index.php");
        exit();
    } else {
        // Afficher un message d'erreur si les informations de connexion sont incorrectes
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        $_SESSION['error_message'] = $error_message; // Stocker l'erreur dans la session

        // Rediriger vers la page de connexion avec l'erreur
        header("Location: Log.php");
        exit();
    }

    $stmt->close();
}

// Fermer la connexion à la base de données
$conn->close();
?>
