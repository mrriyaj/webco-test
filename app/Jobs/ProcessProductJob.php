<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;

class ProcessProductJob implements ShouldQueue
{
    use Queueable;

    protected Product $product;
    protected User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, User $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate some processing time
        sleep(2);

        // Modify the product - let's update the description
        $originalDescription = $this->product->description;
        $processedDescription = $originalDescription . "\n\n[PROCESSED] This product has been processed at " . now()->format('Y-m-d H:i:s');

        $this->product->update([
            'description' => $processedDescription
        ]);

        // Send a database notification to the user with view and edit actions
        FilamentNotification::make()
            ->title('Product Processing Complete')
            ->body("Product '{$this->product->name}' has been successfully processed!")
            ->success()
            ->persistent()
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->markAsRead(),

                Action::make('edit')
                    ->label('Edit')
                    ->url(route('products.edit', $this->product->id))
                    ->openUrlInNewTab(),
            ])
            ->database();

        // Also send a session notification for immediate feedback
        FilamentNotification::make()
            ->title('Job Completed')
            ->body("Product '{$this->product->name}' processing finished!")
            ->success()
            ->send();
    }
}
