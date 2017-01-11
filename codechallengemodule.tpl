{*
/**
 * The template file for our module
 *
 * @author David Varney <davidvarney@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @param $title
 * @param $image_url
 * @param $current_temp
 * @param $high_temp
 * @param $low_temp
 * @param $humidity
 * @param $wind_speed
 * @param $city_name
 * @param $main_condition
 */
*}
<!-- Code-Challenge-Module module-->
<div id="codechallengemodule_block_left" class="block">
    <div class="title_block">
        <h3>{$title}</h3>
        {$city_name}
        <br />
        <div>
            <img src="{$image_url}" class="replace-2x img-responsive">
            <br />
            <span style="text-align:center;">{$main_condition}</span>
        </div>
        <div style="size:large;">{$current_temp}&#8457;</div>
    </div>
    <div class="block_content list-block">
        <ul>
            <li class="clearfix">High: {$high_temp}&#8457;</li>
            <li class="clearfix">Low: {$low_temp}&#8457;</li>
            <li class="clearfix">Humidity: {$humidity}&#37;</li>
            <li class="clearfix">Wind: {$wind_speed}mph</li>
        </ul>
    </div>
</div>
<!-- /Block RSS module-->
