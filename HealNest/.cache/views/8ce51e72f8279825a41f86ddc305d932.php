<?php $__env->startSection('title', 'Dashboard – HealNest'); ?>
<?php $__env->startSection('page-title', 'My Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Avg Mood (7d)','value' => number_format($avgMood, 1) . '/5','icon' => '😊','sub' => 'Based on last 7 logs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Avg Mood (7d)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($avgMood, 1) . '/5'),'icon' => '😊','sub' => 'Based on last 7 logs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Current Streak','value' => $streak . ' days','icon' => '🔥','sub' => 'Keep it up!']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Current Streak','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($streak . ' days'),'icon' => '🔥','sub' => 'Keep it up!']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Risk Level','icon' => '🧠','value' => $latestAssessment ? ucfirst($latestAssessment->risk_level) : 'N/A','sub' => $latestAssessment ? $latestAssessment->type : 'No assessment yet']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Risk Level','icon' => '🧠','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latestAssessment ? ucfirst($latestAssessment->risk_level) : 'N/A'),'sub' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latestAssessment ? $latestAssessment->type : 'No assessment yet')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Open Alerts','value' => $openAlerts,'icon' => '🔔','sub' => 'Requires attention']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Open Alerts','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($openAlerts),'icon' => '🔔','sub' => 'Requires attention']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="font-heading text-forest font-semibold text-lg">Need a conversation with a counselor?</h3>
            <p class="text-sm text-gray-500">Book an appointment request and let the counselor review it from their dashboard.</p>
        </div>
        <a href="<?php echo e(route('appointments.index')); ?>" class="bg-forest text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
            Book Appointment
        </a>
    </div>

    
    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Mood This Week</h3>
            <?php if($moodData->count()): ?>
                <canvas id="moodChart" height="100"></canvas>
            <?php else: ?>
                <div class="text-center py-10 text-gray-400">
                    <p class="text-4xl mb-2">📭</p>
                    <p>No mood logs yet. <a href="<?php echo e(route('mood.create')); ?>" class="text-midgreen underline">Log your first mood</a></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20">
                <h3 class="font-heading text-forest font-semibold mb-3">Today's Mood</h3>
                <?php if($moodData->count()): ?>
                    <?php if (isset($component)) { $__componentOriginalff8d47f71828b4e5d1aaae647d988b45 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff8d47f71828b4e5d1aaae647d988b45 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mood-bar','data' => ['mood' => (int) $moodData->last()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mood-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['mood' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((int) $moodData->last())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff8d47f71828b4e5d1aaae647d988b45)): ?>
<?php $attributes = $__attributesOriginalff8d47f71828b4e5d1aaae647d988b45; ?>
<?php unset($__attributesOriginalff8d47f71828b4e5d1aaae647d988b45); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff8d47f71828b4e5d1aaae647d988b45)): ?>
<?php $component = $__componentOriginalff8d47f71828b4e5d1aaae647d988b45; ?>
<?php unset($__componentOriginalff8d47f71828b4e5d1aaae647d988b45); ?>
<?php endif; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-400">Not logged yet</p>
                <?php endif; ?>
                <a href="<?php echo e(route('mood.create')); ?>"
                   class="mt-4 block text-center bg-forest text-white py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                    + Log Mood
                </a>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20">
                <h3 class="font-heading text-forest font-semibold mb-3">Quick Assessment</h3>
                <div class="space-y-2">
                    <a href="<?php echo e(route('assessment.index')); ?>"
                       class="block text-center border border-forest text-forest py-2 rounded-lg text-sm hover:bg-forest hover:text-white transition-colors">
                        Take Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($latestAssessment): ?>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
        <h3 class="font-heading text-forest font-semibold text-lg mb-3">Latest Assessment</h3>
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <p class="text-xs text-gray-500">Type</p>
                <p class="font-semibold text-forest"><?php echo e($latestAssessment->type); ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Score</p>
                <p class="font-semibold text-forest"><?php echo e($latestAssessment->score); ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Risk Level</p>
                <?php if (isset($component)) { $__componentOriginaled9a569adc317bf9d80ecd6ef9e3a868 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled9a569adc317bf9d80ecd6ef9e3a868 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.risk-badge','data' => ['level' => $latestAssessment->risk_level]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('risk-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['level' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latestAssessment->risk_level)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled9a569adc317bf9d80ecd6ef9e3a868)): ?>
<?php $attributes = $__attributesOriginaled9a569adc317bf9d80ecd6ef9e3a868; ?>
<?php unset($__attributesOriginaled9a569adc317bf9d80ecd6ef9e3a868); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled9a569adc317bf9d80ecd6ef9e3a868)): ?>
<?php $component = $__componentOriginaled9a569adc317bf9d80ecd6ef9e3a868; ?>
<?php unset($__componentOriginaled9a569adc317bf9d80ecd6ef9e3a868); ?>
<?php endif; ?>
            </div>
            <div>
                <p class="text-xs text-gray-500">Taken</p>
                <p class="text-sm text-gray-600"><?php echo e(\Carbon\Carbon::parse($latestAssessment->taken_at)->diffForHumans()); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    

</div>

<?php if($moodData->count()): ?>
<script>
new Chart(document.getElementById('moodChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($moodLabels->values()); ?>,
        datasets: [{
            label: 'Mood Score',
            data: <?php echo json_encode($moodData->values()); ?>,
            backgroundColor: '#7AAF52',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { min: 0, max: 5, ticks: { stepSize: 1 } }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
<?php endif; ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/dashboard/index.blade.php ENDPATH**/ ?>