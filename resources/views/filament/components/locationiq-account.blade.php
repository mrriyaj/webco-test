<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
    <div class="flex items-start space-x-3">
        <x-heroicon-o-user-circle class="w-5 h-5 text-green-500 mt-0.5" />
        <div class="space-y-2">
            <h4 class="text-sm font-medium text-green-800 dark:text-green-200">
                Account Usage Information
            </h4>

            @if (isset($info['status']) && $info['status'] === 'ok')
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if (isset($info['balance']))
                        <div>
                            <span class="font-medium text-green-700 dark:text-green-300">Balance:</span>
                            <span class="text-green-600 dark:text-green-400">{{ $info['balance'] }}</span>
                        </div>
                    @endif

                    @if (isset($info['bonus']))
                        <div>
                            <span class="font-medium text-green-700 dark:text-green-300">Bonus:</span>
                            <span class="text-green-600 dark:text-green-400">{{ $info['bonus'] }}</span>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-green-700 dark:text-green-300">
                    Unable to retrieve account information at this time.
                </p>
            @endif
        </div>
    </div>
</div>
