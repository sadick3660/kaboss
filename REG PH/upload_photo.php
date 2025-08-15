<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circular Photo Upload</title>
    <style>
        /* CSS Styles */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .circular-upload {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            background-color: #ddd;
            cursor: pointer;
            border: 2px dashed #999;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .upload-label {
            display: block;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        #preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-text {
            font-size: 14px;
            color: #666;
        }

        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
    <!-- HTML Structure -->
    <div class="circular-upload">
        <input type="file" id="file-input" accept="image/*">
        <label for="file-input" class="upload-label">
            <img id="preview-image" src="https://via.placeholder.com/150" alt="Upload Photo">
            <div class="upload-text">Click to Upload</div>
        </label>
    </div>

    <!-- JavaScript -->
    <script>
        // JavaScript for handling file upload and preview
        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById('preview-image');
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    document.querySelector('.upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>