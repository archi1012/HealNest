<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealNest – Mental Health & Well-being</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#F5F0E8', forest: '#2D5016', midgreen: '#4A7C2F',
                        lightgreen: '#7AAF52', tan: '#C4A96B', earthbrown: '#8B6914',
                    },
                    fontFamily: { heading: ['"Playfair Display"', 'serif'], body: ['Lato', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { background-color: #F5F0E8; font-family: 'Lato', sans-serif; }</style>
</head>
<body>
    
    <nav class="bg-forest text-white px-6 py-4 flex items-center justify-between sticky top-0 z-50 shadow-md">
        <span class="font-heading text-2xl font-bold text-tan">🌿 HealNest</span>
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('login')); ?>" class="text-green-100 hover:text-white text-sm">Login</a>
            <a href="<?php echo e(route('register')); ?>"
               class="bg-tan text-forest px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lightgreen transition-colors">
                Get Started
            </a>
        </div>
    </nav>

    
    <section class="min-h-screen flex items-center px-6 py-20 max-w-6xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center w-full">
            <div>
                <span class="inline-block bg-lightgreen/20 text-midgreen text-xs font-semibold px-3 py-1 rounded-full mb-4">
                    For Ages 15–30
                </span>
                <h1 class="font-heading text-5xl md:text-6xl font-bold text-forest leading-tight mb-6">
                    Nurture Your<br><span class="text-midgreen">Mental Roots</span>
                </h1>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    Track your emotional well-being, complete evidence-based assessments, and connect with
                    counselors — all in one safe, supportive space.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo e(route('register')); ?>"
                       class="bg-forest text-white px-8 py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors shadow-lg">
                        Start Your Journey
                    </a>
                    <a href="#features"
                       class="border-2 border-forest text-forest px-8 py-3 rounded-xl font-semibold hover:bg-forest hover:text-white transition-colors">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <?php $__currentLoopData = [['😊','Daily Mood Tracking','Log how you feel every day'],['📋','PHQ-9 & GAD-7','Evidence-based assessments'],['📈','Progress Charts','Visualize your journey'],['👩‍⚕️','Counselor Support','Professional guidance']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-tan/20 hover:shadow-md transition-shadow">
                    <div class="text-3xl mb-2"><?php echo e($card[0]); ?></div>
                    <h3 class="font-heading font-semibold text-forest text-sm"><?php echo e($card[1]); ?></h3>
                    <p class="text-gray-500 text-xs mt-1"><?php echo e($card[2]); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section id="features" class="bg-white py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-heading text-4xl font-bold text-forest mb-3">Everything You Need</h2>
                <p class="text-gray-500 max-w-xl mx-auto">A comprehensive platform designed with young adults in mind.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php $__currentLoopData = [
                    ['🌱','Mood Logging','Track your daily emotional state on a 1–5 scale with notes and tags.'],
                    ['🧠','Mental Assessments','PHQ-9 and GAD-7 questionnaires with automatic risk scoring.'],
                    ['📊','Visual Progress','Beautiful charts showing your mood trends over time.'],
                    ['🔔','Smart Alerts','Automatic alerts to counselors when risk scores are elevated.'],
                    ['📚','Resources','Curated coping strategies, articles, and crisis helplines.'],
                    ['🔒','Private & Safe','Your data is secure and only shared with your care team.'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-6 rounded-2xl border border-tan/20 hover:border-midgreen transition-colors">
                    <div class="text-4xl mb-3"><?php echo e($f[0]); ?></div>
                    <h3 class="font-heading font-semibold text-forest text-lg mb-2"><?php echo e($f[1]); ?></h3>
                    <p class="text-gray-500 text-sm"><?php echo e($f[2]); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section class="bg-forest text-white py-20 px-6 text-center">
        <h2 class="font-heading text-4xl font-bold mb-4">Ready to Begin?</h2>
        <p class="text-green-200 mb-8 max-w-md mx-auto">Join thousands of young adults taking charge of their mental health.</p>
        <a href="<?php echo e(route('register')); ?>"
           class="bg-tan text-forest px-10 py-4 rounded-xl font-bold text-lg hover:bg-lightgreen transition-colors inline-block">
            Create Free Account
        </a>
    </section>

    <footer class="bg-forest/90 text-green-200 text-center py-6 text-sm">
        © <?php echo e(date('Y')); ?> HealNest. Built with care for mental wellness.
    </footer>
</body>
</html>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/landing.blade.php ENDPATH**/ ?>