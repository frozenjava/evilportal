<!--<form method="POST" id="show_nginx" action="/components/infusions/evilportal/functions.php?configfile=/etc/nginx/nginx.conf"></form>-->
<form method="POST" id="show_nodogsplash" action="/components/infusions/evilportal/functions.php?configfile=/etc/nodogsplash/nodogsplash.conf"></form>
<form method="POST" id="configure_evilportal" action="/components/infusions/evilportal/functions.php?configure=large"></form>
<form method="POST" id="finalize_evilportal" action="/components/infusions/evilportal/functions.php?finalize"></form>

<center>
  <h2>Manually Configure EvilPortal</h2>
  <p>Click <a href="#" onclick="configure('#configure_evilportal');"><i>here</i></a> to auto configure Evil Portal!</p>
</center>

<ul>
  <!--<li><b>Configure Nginx</b> <a href="#" onclick="ajaxPopup('#show_nginx');">[Show Config]</a></li>
  <br />
  <ul>
    <li>Click "<i>Show Config</i>" above to modify the configuration file.</li>
    <li>Look for the line that says "<i>listen       80;</i>". Change "<i>80</i>" to "<i>8080</i>" and save the file.</li>
    <li>This changes the webserver at "<i>http://172.16.42.1</i>" to "<i>http://172.16.42.1:8080</i>".</li>
  </ul>

  <br />-->

  <li><b>Configure NoDogSplash</b> <a href="#" onclick="ajaxPopup('#show_nodogsplash');">[Show Config]</a></li>
  <br />
  <ul>
    <li>Click "<i>Show Config</i>" above to modify the configuration file.</li>
    <li>Look for the line that says "<i>FirewallRuleSet users-to-router {</i>" under that look for: "<i>FirewallRule allow tcp port 443</i>".</li>
    <li>Below this you need to add two lines:  "<i>FirewallRule allow tcp port 8080</i>" "<i>FirewallRule allow tcp port 1471</i>".</li>
    <li>If you have change the management port from "<i>1471</i>" to something else you will need to replace "<i>1471</i>" above with your port.</li>
    <li>Look for <i>"# GatewayPort 2050"</i>. Remove the <i>"#"</i> at the begning then save the changes.</li>
  </ul>

  <br />

  <li><b>Finalize Configuration</b></li>
  <br />
  <ul>
    <li>Click <i>"Finalize Configuration"</i> below when are you all done.</li>
    <li>This will restart Nginx and tell Evil Portal you  are ready to move on.</li>
  </ul>
</ul>

<center><button href="#" onclick="configure('#finalize_evilportal');">Finalize Configuration</button></center>
