<?php

namespace Botble\Contact\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Contact\Models\Contact;
use Botble\Contact\Repositories\Caches\ContactCacheDecorator;
use Botble\Contact\Repositories\Eloquent\ContactRepository;
use Botble\Support\Services\Cache\Cache;
use Event;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactCacheDecorator(new ContactRepository(new Contact()), new Cache($this->app['cache'], ContactRepository::class));
            });
        } else {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactRepository(new Contact());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setIsInConsole($this->app->runningInConsole())
            ->setNamespace('plugins/contact')
            ->loadAndPublishConfigurations(['email', 'permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations();

        $this->app->register(HookServiceProvider::class);

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-contact',
                'priority' => 120,
                'parent_id' => null,
                'name' => trans('plugins.contact::contact.menu'),
                'icon' => 'fa fa-envelope-o',
                'url' => route('contacts.list'),
                'permissions' => ['contacts.list'],
            ]);
        });
    }
}
