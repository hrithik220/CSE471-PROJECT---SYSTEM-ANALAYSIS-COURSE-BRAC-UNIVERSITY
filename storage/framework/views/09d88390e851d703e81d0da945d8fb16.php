<?php $__env->startSection('title', 'Report a Resource'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🚨 Report a Resource</h1>
        <p class="text-gray-500 text-sm mt-1">Report lost, damaged, or unreturned items</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <form method="POST" action="<?php echo e(route('reports.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Borrow ID <span class="text-red-500">*</span></label>
                <input type="number" name="transaction_id"
                       value="<?php echo e(old('transaction_id', $transaction?->id)); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Enter the borrow ID" required>
                <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($transaction): ?>
                    <p class="text-xs text-indigo-600 mt-1">
                        📦 Item: <strong><?php echo e($transaction->resource->title); ?></strong>
                        | Borrower: <strong><?php echo e($transaction->user->name); ?></strong>
                    </p>
                <?php endif; ?>
            </div>

            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <?php $__currentLoopData = ['lost' => ['🔍','Lost','Item cannot be found'], 'damaged' => ['💥','Damaged','Item returned with damage'], 'unreturned' => ['⏳','Unreturned','Item not returned on time']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="report_type" value="<?php echo e($val); ?>"
                                   class="sr-only peer" <?php echo e(old('report_type') === $val ? 'checked' : ''); ?>>
                            <div class="border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 rounded-xl p-3 text-center transition hover:border-indigo-300">
                                <div class="text-2xl"><?php echo e($info[0]); ?></div>
                                <div class="font-semibold text-sm text-gray-800"><?php echo e($info[1]); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($info[2]); ?></div>
                            </div>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['report_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Describe what happened in detail (minimum 20 characters)..." required><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Evidence (optional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-400 transition">
                    <input type="file" name="evidence" id="evidence" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                           onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'No file chosen'">
                    <label for="evidence" class="cursor-pointer">
                        <div class="text-3xl mb-2">📎</div>
                        <p class="text-sm text-gray-600">Click to upload photo or PDF</p>
                        <p id="file-label" class="text-xs text-gray-400 mt-1">Max 5MB — JPG, PNG, PDF</p>
                    </label>
                </div>
                <?php $__errorArgs = ['evidence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl transition">
                    🚨 Submit Report
                </button>
                <a href="<?php echo e(route('reports.my')); ?>"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campusshare_final\resources\views/reports/create.blade.php ENDPATH**/ ?>