<?php $__env->startSection('title','Edit Resource'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('resources.show', $resource)); ?>" class="text-slate-500 hover:text-slate-700 text-sm flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Edit Resource</h1>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        <?php if($errors->any()): ?>
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('resources.update', $resource)); ?>" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Title *</label>
                    <input type="text" name="title" value="<?php echo e(old('title', $resource->title)); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Category *</label>
                    <select name="category" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php $__currentLoopData = ['Books','Electronics','Tools','Sports','Clothing','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(old('category', $resource->category) === $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type *</label>
                    <select name="type" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="free_lending" <?php echo e(old('type', $resource->type) === 'free_lending' ? 'selected' : ''); ?>>Free Lending</option>
                        <option value="exchange" <?php echo e(old('type', $resource->type) === 'exchange' ? 'selected' : ''); ?>>Exchange</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Condition *</label>
                    <select name="condition" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php $__currentLoopData = ['excellent','good','fair','poor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php echo e(old('condition', $resource->condition) === $c ? 'selected' : ''); ?>><?php echo e(ucfirst($c)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status *</label>
                    <select name="status" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="available" <?php echo e(old('status', $resource->status) === 'available' ? 'selected' : ''); ?>>Available</option>
                        <option value="unavailable" <?php echo e(old('status', $resource->status) === 'unavailable' ? 'selected' : ''); ?>>Unavailable</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Availability Start *</label>
                    <input type="date" name="availability_start" value="<?php echo e(old('availability_start', $resource->availability_start->toDateString())); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Availability End *</label>
                    <input type="date" name="availability_end" value="<?php echo e(old('availability_end', $resource->availability_end->toDateString())); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description *</label>
                    <textarea name="description" rows="3" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?php echo e(old('description', $resource->description)); ?></textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Location Name</label>
                    <input type="text" name="location_name" value="<?php echo e(old('location_name', $resource->location_name)); ?>"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Replace Photo</label>
                    <?php if($resource->photo): ?>
                    <img src="<?php echo e(asset('storage/'.$resource->photo)); ?>" class="w-24 h-24 object-cover rounded-xl mb-2">
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">Save Changes</button>
                <a href="<?php echo e(route('resources.show', $resource)); ?>" class="text-slate-600 hover:bg-slate-100 px-6 py-2.5 rounded-xl transition-colors font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/resources/edit.blade.php ENDPATH**/ ?>