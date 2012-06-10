<h4>NumberHelper Filters</h4>
<ul>
	<li>{{ 10000|curr }}</li>
	<li>{{ 10000|size }}</li>
	<li>{{ 10000|pct }}</li>
	<li>{{ 10000|p(3) }}</li>
</ul>	

<h4>TimeHelper Filters</h4>
<ul>
	<li>{{ '2012-06-02 21:09:34'|ago }}</li>
	<li>{{ '2012-06-02 21:09:34'|nice }}</li>
	<li>{{ '2012-06-02 21:09:34'|niceShort }}</li>
</ul>

<h4>TextHelper Filters</h4>
<style>.highlight { background: #faa }</style>
<ul>
	<li>{{ 'This had <a href="#">some link</a>, but it was stripped'|stripLinks }}</li>
	<li>{{ 'Going to autolink http://this.url and that@mail.addr'|autoLink }}</li>
	<li>{{ 'Lorem truncate dolor sit amet, consectetur adipisicing elit.'|truncate(20) }}</li>
	<li>{{ 'Ut enim ad minim veniam, quis nostrud excerptation ullamco laboris nisi ut aliquip ex ea commodo consequat. '|excerpt('excerptation', 10) }}</li>
	<li>{{ 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'|highlight('amet') }}</li>
</ul>

