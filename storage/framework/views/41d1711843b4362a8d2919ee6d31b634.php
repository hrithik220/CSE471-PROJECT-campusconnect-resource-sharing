<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>CampusShare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<nav class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="<?php echo e(route('map.index')); ?>" class="text-blue-700 font-bold text-xl">
            CampusShare
        </a>
        <div class="flex items-center gap-3 text-sm">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-blue-600">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Register</a>
            <?php else: ?>
                <span class="text-gray-500"><?php echo e(auth()->user()->name); ?></span>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="text-red-500">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if(session('success')): ?>
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm">
        <?php echo e(session('success')); ?>

    </div>
</div>
<?php endif; ?>

<main class="flex-1">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="bg-white border-t mt-12 py-5 text-center text-xs text-gray-400">
    CampusShare - CSE471 Group 11 - Hrithik
</footer>

</body>
</html><?php /**PATH C:\xampp\htdocs\hrithik\resources\views/layouts/app.blade.php ENDPATH**/ ?>