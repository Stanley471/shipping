<?php
require_once 'functions.php';

$error = $_GET['error'] ?? null;
$oldTrackingId = $_GET['tracking_id'] ?? '';
$recentSearches = getRecentSearches();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Track your shipment in real-time. Enter your tracking number to see delivery status.">
    <title>Track Your Shipment | <?php echo APP_NAME; ?></title>
    <meta name="description" content="Track your cargo shipment in real-time. Enter your tracking number to see delivery status, location updates, and estimated delivery time.">
    <meta property="og:title" content="Track Your Shipment | <?php echo APP_NAME; ?>">
    <meta property="og:description" content="Track your cargo shipment in real-time.">
    <meta property="og:type" content="website">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            
            <!-- Track Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Track Your Shipment</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Enter your tracking ID to see the latest status</p>
                </div>

                <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-lg text-sm">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="result.php" class="space-y-4" id="trackForm">
                    <div>
                        <label for="tracking_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tracking ID</label>
                        <input type="text" id="tracking_id" name="tracking_id" value="<?php echo htmlspecialchars($oldTrackingId); ?>" 
                            placeholder="e.g., ABC123456"
                            class="block w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors text-center font-mono text-lg uppercase"
                            required autofocus autocomplete="off">
                    </div>

                    <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg id="searchIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg id="loadingIcon" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="btnText">Track Shipment</span>
                    </button>
                </form>

                <?php if (!empty($recentSearches)): ?>
                <!-- Recent Searches -->
                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Recent Searches</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($recentSearches as $search): ?>
                        <a href="result.php?id=<?php echo urlencode($search); ?>" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 text-slate-700 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-400 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-3 h-3 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo htmlspecialchars($search); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Features -->
            <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-sm border border-slate-200 dark:border-slate-700">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Real-time Updates</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-sm border border-slate-200 dark:border-slate-700">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Secure Tracking</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-sm border border-slate-200 dark:border-slate-700">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Mobile Friendly</p>
                </div>
            </div>

            <!-- How It Works -->
            <div class="mt-12 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white text-center mb-8">How It Works</h2>
                <div class="space-y-6">
                    <!-- Step 1 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">1</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Enter Your Tracking ID</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Type in the tracking number provided when your shipment was booked. This is usually found in your confirmation email or receipt.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">2</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Click Track Shipment</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Press the "Track Shipment" button to search our database for your package's current status and location.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">3</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">View Your Results</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">See real-time updates on your shipment's journey including current location, status updates, and estimated delivery time.</p>
                        </div>
                    </div>
                </div>
                <!-- Tips -->
                <div class="mt-8 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-900 dark:text-amber-200">Tracking Tips</p>
                            <ul class="mt-1 text-xs text-amber-800 dark:text-amber-300 space-y-1">
                                <li>• Tracking IDs are typically 5-20 characters (letters and numbers only)</li>
                                <li>• Recent searches are saved for quick access</li>
                                <li>• Updates may take a few hours to appear after pickup</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 py-6">
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

        updateIcons();

        // Form loading state
        document.getElementById('trackForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const searchIcon = document.getElementById('searchIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            const btnText = document.getElementById('btnText');
            
            btn.disabled = true;
            searchIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
            btnText.textContent = 'Tracking...';
        });

        // Auto-format tracking ID (uppercase, alphanumeric only)
        document.getElementById('tracking_id').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    </script>
</body>
</html>
