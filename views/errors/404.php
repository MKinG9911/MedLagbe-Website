<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - MedLagbe</title>
    <link rel="stylesheet" href="<?= BASE_URL ?? '' ?>public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .error-content {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary-color, #2563eb);
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
            margin: 1rem 0;
        }
        .error-message {
            font-size: 1.1rem;
            color: var(--text-secondary, #6b7280);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: var(--primary-color, #2563eb);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-hover, #1d4ed8);
        }
        .btn-outline {
            background: transparent;
            color: var(--primary-color, #2563eb);
            border: 2px solid var(--primary-color, #2563eb);
        }
        .btn-outline:hover {
            background: var(--primary-color, #2563eb);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page Not Found</h2>
            <p class="error-message">
                The page you're looking for doesn't exist. It might have been moved, deleted, or you entered the wrong URL.
            </p>
            <div class="error-actions">
                <a href="<?= BASE_URL ?? '/' ?>" class="btn btn-primary">Go Home</a>
                <a href="javascript:history.back()" class="btn btn-outline">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
