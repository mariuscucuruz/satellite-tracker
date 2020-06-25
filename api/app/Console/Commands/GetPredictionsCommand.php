<?php
namespace App\Console\Commands;

use App\Http\Controllers\SubscriberController;
use App\N2yoClient;
use App\Subscriber;
use App\Satellite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Exception;

class GetPredictionsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "sat:predictions";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get predictions for all subscriptions.";

    /**
     * Http Client performing the actual API calls.
     *
     * @var Http
     */
    private $apiClient;

    /**
     * Default value for repetitions.
     *
     * @var int
     */
    private $howManyDays = 10;

    /**
     * Constructor instantiates Http client.
     */
    public function __construct()
    {
        parent::__construct();

        $this->apiClient = new N2yoClient();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dd(Satellite::withTrashed()->get());
        $subscribeObj = new Subscriber();

        $getSubscribedSatellites = DB::table('subscribers')
            ->select($subscribeObj->getFillable())
            ->get();

        foreach (Subscriber::withTrashed()->cursor() as $subscription) {
            dd($subscription);
        }

        return;
    }


}
