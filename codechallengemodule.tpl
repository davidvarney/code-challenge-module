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
    <h4 class="title_block">{$title}</h4>
    <div class="block_content list-block">
        <ul>
            <li class="clearfix">
                <div class="ccm-city-name">
                    {$city_name}
                </div>
                <div class="ccm-image-block">
                    <img src="{$image_url}" class="img-responsive ccm-image">
                </div>
                <div class="ccm-temp-block">
                    <span class="ccm-current-temp">{$current_temp}&#8457;</span>
                </div>
            </li>
            <li class="clearfix">Condition: {$main_condition}</li>
            <li class="clearfix">High: {$high_temp}&#8457;</li>
            <li class="clearfix">Low: {$low_temp}&#8457;</li>
            <li class="clearfix">Humidity: {$humidity}&#37;</li>
            <li class="clearfix">Wind: {$wind_speed}mph</li>
        </ul>
    </div>
</div>
<!-- /Block RSS module-->
