<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FeaturedProductService;

class UpdateFeaturedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-featured {--limit=8 : Số lượng sản phẩm nổi bật}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật sản phẩm nổi bật dựa trên top sản phẩm bán chạy';

    /**
     * Execute the console command.
     */
    public function handle(FeaturedProductService $service)
    {
        $limit = (int) $this->option('limit');
        
        $this->info('🔄 Đang cập nhật sản phẩm nổi bật...');
        
        $result = $service->updateFeaturedProducts($limit);
        
        $this->info("✅ Đã cập nhật {$result['count']} sản phẩm nổi bật:");
        
        foreach ($result['products'] as $product) {
            $this->line("   • {$product['name']}");
        }
        
        return Command::SUCCESS;
    }
}
