<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-location-arrow"></i> <span>Locations</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href='{{ backpack_url('country') }}'><i class='fa fa-cube'></i> <span>Countries</span></a></li>
        <li><a href='{{ backpack_url('city') }}'><i class='fa fa-cubes'></i> <span>Cities</span></a></li>
        <li><a href='{{ backpack_url('location') }}'><i class='fa fa-map-marker'></i> <span>Locations</span></a></li>
    </ul>
</li>
<li><a href='{{ backpack_url('tour') }}'><i class='fa fa-globe'></i> <span>Tours</span></a></li>
<li><a href='{{ backpack_url('image') }}'><i class='fa fa-image'></i> <span>Images</span></a></li>
<li><a href='{{ backpack_url('price') }}'><i class='fa fa-money'></i> <span>Prices</span></a></li>
<li><a href='{{ backpack_url('preference') }}'><i class='fa fa-tag'></i> <span>Prefenrences</span></a></li>
<li><a href='{{ backpack_url('master_search_attribute') }}'><i class='fa fa-tag'></i> <span>Master Search Attribute</span></a></li>