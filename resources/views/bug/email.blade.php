Bug Report <br/>
====================<br/>
Bug ID: {{ $result -> id }}<br/>
Bug Name: {{ $result -> title }}<br/>
Submit Date:   {{ $result -> date }}       <br/> 
Reporter:       {{ $result -> user -> username }}<br/>
User Agent: {{ $result -> ua }}   <br/>
URL:   {{ $result -> url }}     <br/>
<br/>
Description<br/>
===========<br/>
{!! $result -> description !!}<br/>
<br/>
Steps to reproduce<br/>
------------------<br/>
{!! $result -> reproduce_step !!}<br/>
