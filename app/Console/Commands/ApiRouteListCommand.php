<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;

class ApiRouteListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:route:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists configured API endpoints';

    /**
     * @var Router
     */
    private Router $router;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routes = collect($this->router->getRoutes())->filter(function (Route $route) {
            return Str::startsWith($route->getName(), 'api:');
        })->map(function (Route $route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri'    => '/' . $route->uri(),
                'name'   => $route->getName(),
            ];
        });

        $specialRoutes = [];

        $route = $this->router->getRoutes()->getByName('api:v1:users.read');
        $specialRoutes[] = [
            'method' => '*',
            'uri'    => '/' . str_replace('{record}', 'me', $route->uri()),
            'description'   => 'You can replace ID with \'me\' for the currently authenticated user',
        ];

        $this->tableWithTitle(
            'API Routes',
            ['Method', 'Uri', 'Name'],
            $routes->toArray()
        );

        $this->line('');

        $this->tableWithTitle(
            'Special Routes',
            ['Method', 'Uri', 'Description'],
            $specialRoutes
        );

        return 0;
    }

    protected function tableWithTitle($title, $headers, $rows)
    {
        $table = new Table($this->output);
        $table->setHeaderTitle($title);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render();
    }
}
