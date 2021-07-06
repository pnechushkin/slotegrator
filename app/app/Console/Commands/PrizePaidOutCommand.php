<?php

namespace App\Console\Commands;

use App\Models\Prize;
use DB;
use Illuminate\Console\Command;

class PrizePaidOutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending the prize to a bank account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $quantity = $this->ask('How many payments to send?');
        if (empty($quantity) || $quantity <= 0) {
            $this->error('Quantity must be a positive integer!');
            return;
        }
        if ($this->confirm("Do you wish to continue and send $quantity prizes? [y|N]")) {
            $prizes = DB::table('prizes')->select('id')
                ->orderBy('created_at')
                ->where(['paid_out' => false, 'type' => Prize::MONEY])
                ->limit($quantity)->get();
            if ($prizes->count() == 0) {
                $this->info('No prize for send!');
                return;
            }
            foreach ($prizes as $prize) {
                $paidOut = Prize::paidOut($prize->id);

                if ($paidOut === true) {
                    $this->info("Prize sent!");
                } else {
                    $this->info(var_export($paidOut, 1));
                    $this->error((is_string($paidOut)) ? $paidOut : "Undefined error.");
                }
            }

        }
    }
}
