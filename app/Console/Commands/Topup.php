<?php

namespace App\Console\Commands;

use App\Service\PassportUser;
use Illuminate\Console\Command;

class Topup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport_user:topup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户充值到账脚本';

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

        $passportUser = new PassportUser();
        $result = $passportUser->handleTopupAmount();
        $this->info($result['msg']);
    }
}
