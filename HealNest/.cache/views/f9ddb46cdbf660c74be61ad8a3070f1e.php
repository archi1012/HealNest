<?php $__env->startSection('title', 'Forgot Password – HealNest'); ?>

<?php $__env->startSection('content'); ?>
<h2 class="font-heading text-2xl font-bold text-forest mb-3 text-center">Reset Your Password</h2>
<p class="text-sm text-gray-500 mb-6 text-center">Enter your email and we’ll send a reset link.</p>

<form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="you@example.com">
    </div>

    <button type="submit" class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors">
        Send Reset Link
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-4">
    Remembered it?
    <a href="<?php echo e(route('login')); ?>" class="text-midgreen font-semibold hover:underline">Back to login</a>
</p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>