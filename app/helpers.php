<?php 

if ( ! function_exists('invalid')) {
	function invalid($key = false)
	{
		return $key ? ' is-invalid' : '';
	}
}

// This function formats balance like 23456 to 20000
// This is only for seeder
if (!function_exists('round_balance')) {
    function round_balance($amount)
    {
        $round = (strlen($amount) - 1) * -1;
        return round($amount, $round);
    }
}

if( ! function_exists('search_options')) {
	function search_options()
	{
		return (new App\Services\QueryStringOptions)->get();
	}
}