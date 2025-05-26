<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="h-[100dvh] bg-gray-850 p-20 overflow-auto max-[1100px]:h-auto max-[1100px]:min-h-[calc(100dvh-16px)] max-[1100px]:p-7">
        <div class="flex flex-col " style="width: 40%;">
            <div class="h-[100dvh] bg-gray-850 p-20 overflow-auto max-[1100px]:h-auto max-[1100px]:min-h-[calc(100dvh-16px)] max-[1100px]:p-7">
                <div class="flex flex-col">
                    <h3 class="text-center">Login</h3>
                    <form method="POST" action="app/controllers/AuthController.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" name="senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
