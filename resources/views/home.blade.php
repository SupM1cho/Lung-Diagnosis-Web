<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deteksi Penyakit Paru-Paru</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
    }
    .top-bar {
      width: 100%;
      height: 60px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 30px;
      box-sizing: border-box;
      background: transparent;
      position: relative;
    }
    .login-btn {
      background-color: #3b82f6;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }
    .login-btn:hover {
      background-color: #45a049;
    }
    
    /* User Profile Styles */
    .user-profile {
      position: relative;
      display: inline-block;
    }
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #3b82f6;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .user-avatar:hover {
      background-color: #2563eb;
    }
    
    /* Dropdown Menu */
    .dropdown-menu {
      position: absolute;
      top: 50px;
      right: 0;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      min-width: 160px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 1000;
    }
    .dropdown-menu.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .dropdown-item {
      display: block;
      padding: 12px 16px;
      text-decoration: none;
      color: #333;
      border-bottom: 1px solid #eee;
      transition: background-color 0.2s;
    }
    .dropdown-item:hover {
      background-color: #f8f9fa;
    }
    .dropdown-item:last-child {
      border-bottom: none;
    }
    .dropdown-item.logout {
      color: #dc3545;
    }
    
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 30px;
    }
    .title {
      font-size: 36px;
      font-weight: bold;
      color: #333;
      margin-top: 20px;
    }
    .card {
      background: #fff;
      padding: 40px;
      width: 700px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      position: relative;
    }
    .upload-area {
      width: 700px;
      height: 300px;
      border: 2px dashed #3b82f6;
      border-radius: 12px;
      background-color: #f0f8ff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #555;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .upload-area:hover {
      background-color: #e0f0ff;
    }
    .upload-area.has-file {
      border-color: #28a745;
      background-color: #f0f9f0;
    }
    .upload-area img {
      width: 50px;
      margin-bottom: 10px;
    }
    
    /* File preview styles */
    .file-preview {
      display: none;
      background-color: #e8f5e8;
      border: 1px solid #28a745;
      border-radius: 8px;
      padding: 15px;
      margin-top: 15px;
      width: 100%;
      max-width: 700px;
    }
    .file-preview.show {
      display: block;
    }
    .file-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .file-icon {
      width: 40px;
      height: 40px;
      background-color: #28a745;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }
    .file-details {
      flex: 1;
    }
    .file-name {
      font-weight: 600;
      color: #333;
      margin-bottom: 2px;
    }
    .file-size {
      font-size: 12px;
      color: #666;
    }
    .remove-file {
      background: none;
      border: none;
      color: #dc3545;
      cursor: pointer;
      font-size: 18px;
      padding: 5px;
    }
    .remove-file:hover {
      color: #c82333;
    }
    
    .info-text {
      font-size: 14px;
      color: #666;
      text-align: center;
    }
    .upload-btn {
      position: absolute;
      bottom: 30px;
      right: 30px;
      width: 150px;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
      opacity: 0.5;
      pointer-events: none;
    }
    .upload-btn:hover {
      background-color: #0056b3;
    }
    .upload-btn.enabled {
      opacity: 1;
      pointer-events: auto;
    }

    /* Success message styles */
    .alert {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      border-radius: 5px;
      color: white;
      font-weight: 500;
      z-index: 1001;
      animation: slideIn 0.3s ease;
    }
    .alert-success {
      background-color: #28a745;
    }
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>
</head>
<body>

@if(session('success'))
<div class="alert alert-success" id="successAlert">
    {{ session('success') }}
</div>
@endif

<div class="top-bar">
  @auth
    <div class="user-profile">
      <div class="user-avatar" onclick="toggleDropdown()">
        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
      </div>
      <div class="dropdown-menu" id="dropdownMenu">
        <a href="{{ route('user.dashboard') }}" class="dropdown-item">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" class="dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
            Logout
          </button>
        </form>
      </div>
    </div>
  @else
    <button class="login-btn" onclick="handleLogin()">Login</button>
  @endauth
</div>

<div class="main-content">
  <div class="title">Upload file</div>
  <div class="card">
    <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data" style="width:100%; display:flex; flex-direction:column; align-items:center;" id="uploadForm">
    @csrf
      <label for="file-upload" class="upload-area" id="uploadArea">
        <img src="https://cdn-icons-png.flaticon.com/512/126/126477.png" alt="Upload Icon" id="uploadIcon">
        <p id="uploadText">Drag & Drop your files or <span style="color:#3b82f6;">Browse</span></p>
        <input type="file" id="file-upload" name="xray_image" accept="image/png, image/jpeg" style="display:none;" required>
      </label>
      
      <!-- File Preview -->
      <div class="file-preview" id="filePreview">
        <div class="file-info">
          <div class="file-icon">📷</div>
          <div class="file-details">
            <div class="file-name" id="fileName"></div>
            <div class="file-size" id="fileSize"></div>
          </div>
          <button type="button" class="remove-file" onclick="removeFile()" title="Remove file">×</button>
        </div>
      </div>
      
      <p class="info-text" style="margin-top: 10px;">Supported formats: PNG, JPG<br>Maximum size: 25MB</p>
      <button type="submit" class="upload-btn" id="uploadBtn">Upload</button>
    </form>
  </div>
</div>

<script>
  function handleLogin() {
    window.location.href = "{{ url('/login') }}";
  }

  // File upload handling - VERSI DIPERBAIKI
  const fileInput = document.getElementById('file-upload');
  const uploadArea = document.getElementById('uploadArea');
  const uploadIcon = document.getElementById('uploadIcon');
  const uploadText = document.getElementById('uploadText');
  const filePreview = document.getElementById('filePreview');
  const fileName = document.getElementById('fileName');
  const fileSize = document.getElementById('fileSize');
  const uploadBtn = document.getElementById('uploadBtn');

  // Function to format file size
  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  // Function to show file preview
  function showFilePreview(file) {
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    filePreview.classList.add('show');
    uploadArea.classList.add('has-file');
    uploadBtn.classList.add('enabled');
    
    // Update upload area text
    uploadIcon.style.display = 'none';
    uploadText.innerHTML = `<strong>File selected:</strong> ${file.name}<br><small>Click to change file</small>`;
  }

  // Function to remove file
  function removeFile() {
    fileInput.value = '';
    filePreview.classList.remove('show');
    uploadArea.classList.remove('has-file');
    uploadBtn.classList.remove('enabled');
    
    // Reset upload area
    uploadIcon.style.display = 'block';
    uploadText.innerHTML = 'Drag & Drop your files or <span style="color:#3b82f6;">Browse</span>';
  }

  // Function to handle file validation and preview
  function handleFileSelection(file) {
    if (!file) return;
    
    // Validate file type
    const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
      alert('Please select a valid image file (PNG, JPG, JPEG)');
      removeFile();
      return false;
    }
    
    // Validate file size (25MB)
    if (file.size > 25 * 1024 * 1024) {
      alert('File size must be less than 25MB');
      removeFile();
      return false;
    }
    
    showFilePreview(file);
    return true;
  }

  // File input change event - EVENT HANDLER UTAMA
  fileInput.addEventListener('change', function(e) {
    console.log('File input changed:', e.target.files); // Debug log
    const file = e.target.files[0];
    handleFileSelection(file);
  });

  // Click area upload untuk membuka file dialog
  uploadArea.addEventListener('click', function(e) {
    // Cegah buka dialog jika klik tombol remove
    if (e.target.classList.contains('remove-file')) {
      e.stopPropagation();
      return;
    }
    fileInput.click();
  });

  // Drag and drop functionality - DIPERBAIKI
  uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.style.backgroundColor = '#e0f0ff';
  });

  uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.style.backgroundColor = uploadArea.classList.contains('has-file') ? '#f0f9f0' : '#f0f8ff';
  });

  uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.style.backgroundColor = uploadArea.classList.contains('has-file') ? '#f0f9f0' : '#f0f8ff';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      const file = files[0];
      
      // PERBAIKAN UTAMA: Set file ke input element dengan benar
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      fileInput.files = dataTransfer.files;
      
      // Handle file selection
      handleFileSelection(file);
    }
  });

  // Form submit validation - TAMBAHAN KEAMANAN
  document.getElementById('uploadForm').addEventListener('submit', function(e) {
    console.log('Form submitting with files:', fileInput.files); // Debug log
    
    if (!fileInput.files || fileInput.files.length === 0) {
      e.preventDefault();
      alert('Please select a file before uploading');
      return false;
    }
    
    const file = fileInput.files[0];
    if (!file) {
      e.preventDefault();
      alert('No file selected');
      return false;
    }
    
    // Final validation
    const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
      e.preventDefault();
      alert('Please select a valid image file (PNG, JPG, JPEG)');
      return false;
    }
    
    console.log('Form submission approved with file:', file.name); // Debug log
    return true;
  });

  // Dropdown functionality
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const userProfile = document.querySelector('.user-profile');
    const dropdown = document.getElementById('dropdownMenu');
    
    if (userProfile && !userProfile.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });

  // Auto hide success alert after 5 seconds
  const successAlert = document.getElementById('successAlert');
  if (successAlert) {
    setTimeout(() => {
      successAlert.style.animation = 'slideIn 0.3s ease reverse';
      setTimeout(() => {
        successAlert.remove();
      }, 300);
    }, 5000);
  }
</script>

</body>
</html>