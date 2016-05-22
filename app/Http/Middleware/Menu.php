<?php

namespace App\Http\Middleware;

use Closure;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(($user = $request->user())) {
            \Menu::make('navigation', function ($menu) use ($user) {
                $menu->add('Home');
                $menu->home->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-home"></i></div>');


                $menu->add('Datoteke', 'files');
                $menu->datoteke->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-file"></i></div>');

                if($user->isAdmin()) {
                    $menu->add('Nastavnici', 'professors');
                    $menu->nastavnici->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-user"></i></div>');


                    $menu->add('Učenici', 'students');
                    $menu->ucenici->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-education"></i></div>');


                    $menu->add('Smjerovi', 'courses');
                    $menu->smjerovi->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-send"></i></div>');


                    $menu->add('Predmeti', 'subjects');
                    $menu->predmeti->prepend('<div class="navigation__icon"><i class="glyphicon glyphicon-book"></i></div>');
                }
            });
        }

        return $next($request);
    }
}
