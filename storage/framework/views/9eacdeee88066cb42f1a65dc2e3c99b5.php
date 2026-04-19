<?php $__env->startSection('title','Post Resource'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Post a Resource</h1>
        <p class="text-slate-500 text-sm mt-1">Share something with your campus community</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        <?php if($errors->any()): ?>
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('resources.store')); ?>" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Title *</label>
                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Calculus Textbook 8th Edition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Category *</label>
                    <select name="category" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select category</option>
                        <?php $__currentLoopData = ['Books','Electronics','Tools','Sports','Clothing','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(old('category') === $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type *</label>
                    <select name="type" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select type</option>
                        <option value="free_lending" <?php echo e(old('type') === 'free_lending' ? 'selected' : ''); ?>>Free Lending</option>
                        <option value="exchange" <?php echo e(old('type') === 'exchange' ? 'selected' : ''); ?>>Exchange-based</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Condition *</label>
                    <select name="condition" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select condition</option>
                        <?php $__currentLoopData = ['excellent','good','fair','poor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php echo e(old('condition') === $c ? 'selected' : ''); ?>><?php echo e(ucfirst($c)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Availability Start *</label>
                    <input type="date" name="availability_start" value="<?php echo e(old('availability_start')); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Availability End *</label>
                    <input type="date" name="availability_end" value="<?php echo e(old('availability_end')); ?>" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description *</label>
                    <textarea name="description" rows="3" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        placeholder="Describe the item, its condition, and any special notes..."><?php echo e(old('description')); ?></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pickup Location</label>
                    <input type="text" name="location_name" value="<?php echo e(old('location_name')); ?>" id="location_name"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                        placeholder="e.g. Library Block A, Room 204">
                    <div id="map-pick" class="w-full h-48 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden"></div>
                    <p class="text-xs text-slate-400 mt-1">Click on the map to set location</p>
                    <input type="hidden" name="location_lat" id="location_lat" value="<?php echo e(old('location_lat')); ?>">
                    <input type="hidden" name="location_lng" id="location_lng" value="<?php echo e(old('location_lng')); ?>">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Photo</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-slate-400 mt-1">JPEG, PNG or GIF — max 2MB</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Post Resource
                </button>
                <a href="<?php echo e(route('resources.index')); ?>" class="text-slate-600 hover:bg-slate-100 px-6 py-2.5 rounded-xl transition-colors font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map-pick').setView([23.8103, 90.4125], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let marker;
map.on('click', function(e) {
    if (marker) marker.remove();
    marker = L.marker(e.latlng).addTo(map);
    document.getElementById('location_lat').value = e.latlng.lat.toFixed(7);
    document.getElementById('location_lng').value = e.latlng.lng.toFixed(7);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/resources/create.blade.php ENDPATH**/ ?>