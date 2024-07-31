<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventXSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        $excludeTags = [
            'p', 'b', 'i', 'br', 'table', 'td', 'tr', 'th', 'thead',
            'tbody', 'tfoot', 'a', 'img', 'div', 'span', 'strong',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ];
        array_walk_recursive($input, function (&$input) use ($excludeTags) {
            $input = strip_tags($input, $excludeTags);
        });
        $request->merge($input);

        return $next($request);
    }
}
