<?php
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES["apk_file"]) && isset($_POST["app_type"])) {
        $appType = $_POST["app_type"];
        $file = $_FILES["apk_file"];

        if ($file["error"] === 0) {
            $ext = pathinfo($file["name"], PATHINFO_EXTENSION);

            if ($ext !== "apk") {
                $message = "Seuls les fichiers APK sont autorisés.";
                $messageType = "error";
            } else {
                if ($appType === "taxina") {
                    $target = "taxina.apk";
                    $appName = "TAXina Passager";
                } elseif ($appType === "driver") {
                    $target = "taxina_driver.apk";
                    $appName = "TAXina Driver";
                } else {
                    $message = "Type d'application invalide.";
                    $messageType = "error";
                }

                if (empty($message)) {
                    if (move_uploaded_file($file["tmp_name"], $target)) {
                        $message = "L'application $appName a été mise à jour avec succès !";
                        $messageType = "success";
                    } else {
                        $message = "Échec de l'upload. Vérifiez les permissions du serveur.";
                        $messageType = "error";
                    }
                }
            }
        } else {
            $message = "Erreur lors de l'envoi du fichier. Code: " . $file["error"];
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin TAXina – Upload APK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary: #0bbcd6;
      --secondary: #10b981;
      --dark: #034f4f;
      --light: #e8fffb;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--light) 0%, #ffffff 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      padding: 40px 20px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(11, 188, 214, 0.3);
      position: relative;
      overflow: hidden;
    }

    header::before {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -100px;
      right: -100px;
    }

    .header-icon {
      width: 90px;
      height: 90px;
      background: white;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .header-icon i {
      font-size: 3rem;
      color: var(--primary);
    }

    header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
      position: relative;
    }

    header p {
      font-size: 1.1rem;
      opacity: 0.95;
      position: relative;
    }

    .container {
      flex: 1;
      padding: 60px 20px;
    }

    .upload-card {
      background: white;
      border-radius: 25px;
      padding: 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
      border: 2px solid transparent;
      transition: all 0.3s ease;
    }

    .upload-card:hover {
      border-color: var(--primary);
      box-shadow: 0 25px 70px rgba(11, 188, 214, 0.15);
    }

    .alert-custom {
      border-radius: 15px;
      padding: 20px 25px;
      margin-bottom: 30px;
      border: none;
      display: flex;
      align-items: center;
      gap: 15px;
      animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      color: #065f46;
    }

    .alert-error {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #991b1b;
    }

    .alert-custom i {
      font-size: 1.8rem;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-label i {
      color: var(--primary);
    }

    .form-select, .form-control {
      border-radius: 12px;
      border: 2px solid #e5e7eb;
      padding: 14px 18px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(11, 188, 214, 0.1);
    }

    .custom-file-upload {
      border: 3px dashed #d1d5db;
      border-radius: 15px;
      padding: 40px 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #f9fafb;
    }

    .custom-file-upload:hover {
      border-color: var(--primary);
      background: var(--light);
    }

    .custom-file-upload.active {
      border-color: var(--secondary);
      background: linear-gradient(135deg, rgba(11, 188, 214, 0.1), rgba(16, 185, 129, 0.1));
    }

    .file-upload-icon {
      font-size: 4rem;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .file-upload-text {
      color: #6b7280;
      font-size: 1rem;
    }

    .file-upload-text strong {
      color: var(--primary);
    }

    .file-info {
      display: none;
      margin-top: 15px;
      padding: 15px;
      background: linear-gradient(135deg, rgba(11, 188, 214, 0.1), rgba(16, 185, 129, 0.1));
      border-radius: 10px;
      color: var(--dark);
      font-weight: 500;
    }

    .btn-upload {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      border: none;
      color: white;
      padding: 16px 40px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px rgba(11, 188, 214, 0.3);
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-upload:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(11, 188, 214, 0.4);
    }

    .btn-upload:active {
      transform: translateY(-1px);
    }

    .info-section {
      background: linear-gradient(135deg, rgba(11, 188, 214, 0.05), rgba(16, 185, 129, 0.05));
      border-radius: 20px;
      padding: 30px;
      margin-top: 30px;
    }

    .info-section h4 {
      color: var(--dark);
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-section ul {
      list-style: none;
      padding: 0;
    }

    .info-section li {
      padding: 10px 0;
      color: #4b5563;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-section li i {
      color: var(--secondary);
      font-size: 1.1rem;
    }

    footer {
      background: linear-gradient(135deg, #023d3d, var(--dark));
      color: white;
      text-align: center;
      padding: 25px;
      margin-top: auto;
    }

    footer p {
      margin: 0;
      opacity: 0.9;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .stat-box {
      background: white;
      border-radius: 15px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-box i {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .stat-box h3 {
      font-size: 1.1rem;
      color: var(--dark);
      margin: 0;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      header h1 {
        font-size: 2rem;
      }

      .upload-card {
        padding: 25px;
      }

      .custom-file-upload {
        padding: 30px 15px;
      }
    }
  </style>
</head>

<body>

<header>
  <div class="header-icon">
    <i class="fas fa-shield-alt"></i>
  </div>
  <h1>Administration TAXina</h1>
  <p><i class="fas fa-upload"></i> Gestion & mise à jour des applications</p>
</header>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="upload-card">

        <?php if (!empty($message)): ?>
          <div class="alert-custom alert-<?php echo $messageType; ?>">
            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
            <div>
              <strong><?php echo $messageType === 'success' ? 'Succès !' : 'Erreur'; ?></strong>
              <p style="margin: 5px 0 0 0;"><?php echo $message; ?></p>
            </div>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="uploadForm">

          <div class="mb-4">
            <label class="form-label">
              <i class="fas fa-mobile-alt"></i>
              Application à mettre à jour
            </label>
            <select name="app_type" class="form-select" required id="appType">
              <option value="">-- Sélectionnez une application --</option>
              <option value="taxina">📱 TAXina Passager</option>
              <option value="driver">🚖 TAXina Driver</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label">
              <i class="fas fa-file-upload"></i>
              Fichier APK
            </label>
            <div class="custom-file-upload" id="fileUploadZone">
              <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
              <p class="file-upload-text">
                <strong>Cliquez pour sélectionner</strong> ou glissez votre fichier APK ici
              </p>
              <p class="file-upload-text" style="font-size: 0.9rem; margin-top: 10px;">
                Taille maximale: 100 MB
              </p>
              <div class="file-info" id="fileInfo"></div>
            </div>
            <input type="file" name="apk_file" class="form-control d-none" accept=".apk" required id="fileInput">
          </div>

          <button type="submit" class="btn-upload" id="submitBtn">
            <i class="fas fa-upload"></i>
            <span>Mettre à jour l'application</span>
          </button>

        </form>

        <div class="info-section">
          <h4><i class="fas fa-info-circle"></i> Instructions importantes</h4>
          <ul>
            <li><i class="fas fa-check"></i> Assurez-vous que le fichier est bien un APK valide</li>
            <li><i class="fas fa-check"></i> La mise à jour remplacera l'ancienne version</li>
            <li><i class="fas fa-check"></i> Les utilisateurs recevront une notification de mise à jour</li>
            <li><i class="fas fa-check"></i> Testez l'APK avant de le déployer en production</li>
          </ul>
        </div>

      </div>

    </div>
  </div>
</div>

<footer>
  <p><i class="fas fa-lock"></i> Accès réservé aux administrateurs – TAXina © 2025</p>
</footer>

<script>
  const fileInput = document.getElementById('fileInput');
  const fileUploadZone = document.getElementById('fileUploadZone');
  const fileInfo = document.getElementById('fileInfo');
  const submitBtn = document.getElementById('submitBtn');
  const uploadForm = document.getElementById('uploadForm');

  // Click to select file
  fileUploadZone.addEventListener('click', () => {
    fileInput.click();
  });

  // File selection
  fileInput.addEventListener('change', (e) => {
    handleFile(e.target.files[0]);
  });

  // Drag and drop
  fileUploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadZone.classList.add('active');
  });

  fileUploadZone.addEventListener('dragleave', () => {
    fileUploadZone.classList.remove('active');
  });

  fileUploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    fileUploadZone.classList.remove('active');
    const file = e.dataTransfer.files[0];
    if (file && file.name.endsWith('.apk')) {
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      fileInput.files = dataTransfer.files;
      handleFile(file);
    }
  });

  function handleFile(file) {
    if (file) {
      const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
      fileInfo.style.display = 'block';
      fileInfo.innerHTML = `
        <i class="fas fa-file-archive"></i>
        <strong>${file.name}</strong> (${sizeMB} MB)
      `;
      fileUploadZone.classList.add('active');
    }
  }

  // Form submission
  uploadForm.addEventListener('submit', (e) => {
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Upload en cours...</span>';
    submitBtn.disabled = true;
  });
</script>

</body>
</html>