<?php
require_once 'functions.php';

// Check rate limit
if (!checkRateLimit()) {
    header('Location: index.php?error=' . urlencode('Too many requests. Please try again later.'));
    exit;
}

// Get tracking ID from POST or URL
$trackingId = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tracking_id'])) {
    $trackingId = trim($_POST['tracking_id']);
} elseif (isset($_GET['id'])) {
    $trackingId = trim($_GET['id']);
}

// Validate
if (empty($trackingId)) {
    header('Location: index.php?error=' . urlencode('Please enter a tracking ID'));
    exit;
}

if (!validateTrackingId($trackingId)) {
    header('Location: index.php?error=' . urlencode('Invalid tracking ID format') . '&tracking_id=' . urlencode($trackingId));
    exit;
}

// Fetch data
$data = fetchTrackingData($trackingId);

if (!$data || !($data['success'] ?? false)) {
    logError("Tracking not found: {$trackingId}");
    header('Location: index.php?error=' . urlencode('Tracking number not found') . '&tracking_id=' . urlencode($trackingId));
    exit;
}

// Add to recent searches
addRecentSearch($trackingId);

$shipment = $data['data'];
$timeline = $shipment['timeline'] ?? [];
$latest = $timeline[0] ?? null;
$progress = $latest['progress'] ?? 0;
$status = $latest['status'] ?? 'pending';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($shipment['tracking_id']); ?> - Tracking Result | <?php echo APP_NAME; ?></title>
    <meta name="description" content="Track <?php echo htmlspecialchars($shipment['tracking_id']); ?>: <?php echo formatStatus($status); ?> - <?php echo $progress; ?>% complete. From <?php echo htmlspecialchars($shipment['origin'] ?? 'Origin'); ?> to <?php echo htmlspecialchars($shipment['destination'] ?? 'Destination'); ?>.">
    <meta property="og:title" content="Shipment <?php echo htmlspecialchars($shipment['tracking_id']); ?> - <?php echo formatStatus($status); ?>">
    <meta property="og:description" content="<?php echo $progress; ?>% complete - <?php echo formatStatus($status); ?>">
    <link rel="canonical" href="<?php echo 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/result.php?id=' . urlencode($shipment['tracking_id']); ?>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; }
            .shadow-lg { box-shadow: none !important; }
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        emerald: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857' }
                    }
                }
            }
        }
    </script>
    <script>
        (function() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-900 min-h-screen flex flex-col">
    
    <!-- Header -->
    <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="./" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900 dark:text-white"><?php echo APP_NAME; ?></span>
                </a>

                <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                    <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 mb-6 no-print">
                <a href="./" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
n                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Track Another
                </a>

                <button onclick="copyLink()" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors" id="copyBtn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span id="copyText">Copy Link</span>
                </button>

                <button onclick="window.print()" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>

            <!-- Print Header (hidden on screen) -->
            <div class="hidden print-only mb-6">
                <h1 class="text-2xl font-bold"><?php echo APP_NAME; ?> - Tracking Receipt</h1>
                <p class="text-sm text-gray-500">Printed on <?php echo date('F j, Y \a\t g:i A'); ?></p>
            </div>

            <!-- Status Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 md:p-8 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Tracking ID</p>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-mono"><?php echo htmlspecialchars($shipment['tracking_id']); ?></h1>
                    </div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold uppercase bg-<?php echo getStatusColor($status); ?>-100 text-<?php echo getStatusColor($status); ?>-800 dark:bg-<?php echo getStatusColor($status); ?>-900 dark:text-<?php echo getStatusColor($status); ?>-200">
                        <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                        <?php echo formatStatus($status); ?>
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Shipment Progress</span>
                        <span class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="h-4 w-full bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 bg-<?php echo getProgressColor($progress); ?>-500" style="width: <?php echo $progress; ?>%;"></div>
                    </div>
                </div>

                <!-- Quick Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Shipped</p>
                        <p class="font-semibold text-slate-900 dark:text-white"><?php echo formatDate($shipment['shipped_at']); ?></p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">ETA</p>
                        <p class="font-semibold text-slate-900 dark:text-white"><?php echo formatDate($shipment['estimated_delivery']); ?></p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Type</p>
                        <p class="font-semibold text-slate-900 dark:text-white capitalize"><?php echo str_replace('_', ' ', $shipment['shipment_type']); ?></p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Updates</p>
                        <p class="font-semibold text-slate-900 dark:text-white"><?php echo count($timeline); ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Route Info -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 7m0 13V7"/>
                        </svg>
                        Route Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">From</p>
                                <p class="font-semibold text-slate-900 dark:text-white"><?php echo htmlspecialchars($shipment['sender']); ?></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400"><?php echo htmlspecialchars($shipment['origin'] ?? 'N/A'); ?></p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <svg class="w-5 h-5 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">To</p>
                                <p class="font-semibold text-slate-900 dark:text-white"><?php echo htmlspecialchars($shipment['receiver']); ?></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400"><?php echo htmlspecialchars($shipment['destination'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if ($latest && $latest['location']): ?>
                    <div class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium uppercase tracking-wider mb-1">Current Location</p>
                        <p class="text-slate-900 dark:text-white font-semibold"><?php echo htmlspecialchars($latest['location']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activity Timeline
                    </h2>

                    <?php if (!empty($timeline)): ?>
                    <div class="relative space-y-6 max-h-96 overflow-y-auto pr-2">
                        <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-slate-700"></div>

                        <?php foreach ($timeline as $index => $update): ?>
                        <div class="relative flex gap-4">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo $index === 0 ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400'; ?>">
                                    <?php if ($index === 0): ?>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    <?php else: ?>
                                        <div class="w-2 h-2 rounded-full bg-current"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex-1 pb-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-semibold text-slate-900 dark:text-white text-sm capitalize">
                                        <?php echo formatStatus($update['status']); ?>
                                    </h4>
                                    <?php if ($index === 0): ?>
                                        <span class="text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 rounded">Latest</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><?php echo formatDateTime($update['timestamp']); ?></p>
                                
                                <?php if ($update['message']): ?>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 bg-slate-50 dark:bg-slate-700/50 p-2 rounded"><?php echo htmlspecialchars($update['message']); ?></p>
                                <?php endif; ?>

                                <div class="mt-2 flex items-center gap-2">
                                    <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full" style="width: <?php echo $update['progress']; ?>%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo $update['progress']; ?>%</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No updates yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 py-6 mt-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Secure Tracking
                </span>
            </div>
        </div>
    </footer>

    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateIcons();
        }

        function updateIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.getElementById('sun-icon').classList.toggle('hidden', !isDark);
            document.getElementById('moon-icon').classList.toggle('hidden', isDark);
        }

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                const copyText = document.getElementById('copyText');
                const original = copyText.textContent;
                copyText.textContent = 'Copied!';
                setTimeout(function() {
                    copyText.textContent = original;
                }, 2000);
            });
        }

        updateIcons();
    </script>
</body>
</html>
