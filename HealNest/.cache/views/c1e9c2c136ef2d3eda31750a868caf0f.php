<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'HealNest'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#F5F0E8', forest: '#2D5016', midgreen: '#4A7C2F',
                        lightgreen: '#7AAF52', tan: '#C4A96B', earthbrown: '#8B6914',
                    },
                    fontFamily: {
                        heading: ['"Playfair Display"', 'serif'],
                        body: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>body { background-color: #F5F0E8; font-family: 'Lato', sans-serif; }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?php echo e(route('home')); ?>" class="inline-block">
                <h1 class="font-heading text-3xl font-bold text-forest">🌿 HealNest</h1>
                <p class="text-earthbrown text-sm mt-1">Mental Health & Well-being Platform</p>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-tan/20 p-8">
            <?php if(session('success')): ?>
                <div class="mb-4 p-3 bg-lightgreen/20 border border-lightgreen rounded-lg text-forest text-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/layouts/auth.blade.php ENDPATH**/ ?>