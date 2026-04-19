<?php $__env->startSection('title', 'My Badges'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🎖 Badge Collection</h1>
    <p class="text-gray-500 text-sm mt-1">
        You've earned <strong><?php echo e(count($earnedSlugs)); ?></strong> out of <?php echo e($badges->count()); ?> badges
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
    <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $earned = in_array($badge->slug, $earnedSlugs); ?>
        <div class="bg-white rounded-2xl shadow-sm border-2 p-6 text-center transition <?php echo e($earned ? 'border-yellow-400 hover:shadow-md' : 'border-gray-100 opacity-50 grayscale'); ?>">
            <div class="text-5xl mb-3"><?php echo e($badge->icon); ?></div>
            <h3 class="font-bold text-gray-800 mb-1" style="<?php echo e($earned ? 'color:'.$badge->color : ''); ?>">
                <?php echo e($badge->name); ?>

            </h3>
            <p class="text-xs text-gray-500 mb-3"><?php echo e($badge->description); ?></p>
            <div class="flex items-center justify-center gap-2">
                <span class="text-xs font-medium px-2 py-1 rounded-full <?php echo e($earned ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500'); ?>">
                    <?php echo e(number_format($badge->required_points)); ?> karma pts
                </span>
            </div>
            <?php if($earned): ?>
                <?php $userBadge = $user->badges->where('slug', $badge->slug)->first(); ?>
                <p class="text-xs text-green-600 mt-2">
                    ✅ Earned <?php echo e($userBadge?->pivot?->awarded_at ? \Carbon\Carbon::parse($userBadge->pivot->awarded_at)->format('d M Y') : ''); ?>

                </p>
            <?php else: ?>
                <p class="text-xs text-gray-400 mt-2">🔒 Locked</p>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="mt-8 text-center">
    <a href="<?php echo e(route('statistics.index')); ?>"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition inline-block">
        📊 View My Statistics
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/leaderboard/badges.blade.php ENDPATH**/ ?>