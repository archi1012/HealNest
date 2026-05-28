<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href', 'icon', 'label', 'badge' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['href', 'icon', 'label', 'badge' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<a href="<?php echo e($href); ?>"
   class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors relative
          <?php echo e(request()->url() === $href ? 'bg-midgreen text-white' : 'text-green-100 hover:bg-midgreen/50 hover:text-white'); ?>">
    <span class="text-lg flex-shrink-0"><?php echo e($icon); ?></span>
    <span x-show="open" x-cloak><?php echo e($label); ?></span>
    <?php if(! is_null($badge) && $badge !== ''): ?>
        <span class="absolute right-4 top-1/2 -translate-y-1/2 min-w-5 h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?>
</a>
<?php /**PATH /Users/Adit/Documents/GitHub/HealNest/HealNest/resources/views/components/nav-item.blade.php ENDPATH**/ ?>