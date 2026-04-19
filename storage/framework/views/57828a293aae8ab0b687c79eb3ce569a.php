<?php $__env->startSection('title','Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Welcome back, <?php echo e(auth()->user()->name); ?> 👋</h1>
    <p class="text-slate-500 mt-1">Here's what's happening on campus today.</p>
</div>


<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['my_resources']); ?></p>
        <p class="text-sm text-slate-500">My Resources</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['active_borrowing']); ?></p>
        <p class="text-sm text-slate-500">Borrowing</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['active_lending']); ?></p>
        <p class="text-sm text-slate-500">Lending</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['pending_requests']); ?></p>
        <p class="text-sm text-slate-500">Pending Requests</p>
    </div>
</div>


<?php if($overdueItems->count()): ?>
<div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-8">
    <h3 class="font-bold text-red-800 mb-3 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        ⚠ Overdue Items (<?php echo e($overdueItems->count()); ?>)
    </h3>
    <div class="space-y-2">
        <?php $__currentLoopData = $overdueItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center justify-between bg-white rounded-xl p-3">
            <span class="font-medium text-slate-700"><?php echo e($item->resource->title); ?></span>
            <span class="text-red-600 text-sm font-semibold">Due: <?php echo e($item->due_date->format('M d, Y')); ?></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-slate-800">Recently Available Resources</h2>
        <a href="<?php echo e(route('resources.index')); ?>" class="text-blue-600 text-sm font-semibold hover:underline">View All →</a>
    </div>
    <?php if($recentResources->count()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $recentResources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('resources.show', $resource)); ?>" class="block border border-slate-100 rounded-xl p-4 card-hover hover:border-blue-200">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <?php if($resource->photo): ?>
                    <img src="<?php echo e(asset('storage/'.$resource->photo)); ?>" class="w-12 h-12 object-cover rounded-xl">
                    <?php else: ?>
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 truncate"><?php echo e($resource->title); ?></p>
                    <p class="text-xs text-slate-500"><?php echo e($resource->owner->name); ?></p>
                    <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full <?php echo e($resource->type === 'free_lending' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'); ?>">
                        <?php echo e($resource->type === 'free_lending' ? 'Free' : 'Exchange'); ?>

                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <p class="text-slate-400 text-center py-8">No resources available yet. <a href="<?php echo e(route('resources.create')); ?>" class="text-blue-600 font-semibold">Post one!</a></p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/dashboard.blade.php ENDPATH**/ ?>