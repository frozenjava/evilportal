<?php
$infusion_path = "/components/infusions/evilportal";
?>

<script type="text/javascript" src="<?php echo $infusion_path; ?>/includes/helpers.js"></script>
<style>@import url('<?php echo $infusion_path; ?>/includes/styles.css')</style>

<br />

<?php 

include $directory . "/functions.php";

if (require_pineapple_version(1.4)) {

  if (!checkDepends()) {
    if (online()) {
      echo '
      <center>
        <font color="red">You need to install dependencies before you can use Evil Portal</font><br />
        <form method="POST" id="depends_evilportal" action="' . $infusion_path .'/functions.php?request_depends=large"></form>        
        <a href="#" onclick="ajaxPopup(\'#depends_evilportal\');">Install Dependencies</a>
      </center>
      ';
    } else
      echo '<font color="red">An internet connection is required to install dependencies!</font>';

  } elseif(!checkConfig() && checkDepends()) {

    include $directory . "/includes/content/configure.php";

  } elseif (checkConfig() && checkDepends()) {

    echo '
    <form method="POST" id="start_evilportal" action="' . $infusion_path . '/functions.php?start=large"></form>
    <form method="POST" id="stop_evilportal" action="'. $infusion_path . '/functions.php?stop=large"></form>
    <form method="POST" id="enable_evilportal" action="' . $infusion_path .'/functions.php?enable=large"></form>
    <form method="POST" id="disable_evilportal" action="' . $infusion_path . '/functions.php?disable=large"></form>
    ';

    if (checkRunning())
      echo '&nbsp;NoDogSplash <font color="lime"><b>Running.</b></font>&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotify(\'#stop_evilportal\');">Stop</a></b><br />';
    else
      echo '&nbsp;NoDogSplash <font color="red"><b>Disabled.</b></font>&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotify(\'#start_evilportal\');">Start</a></b><br />';

    if (checkAutoStart())
      echo '&nbsp;Autostart <font color="lime"><b>Enabled.</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotify(\'#disable_evilportal\');">Disable</a></b><br />';
    else
      echo '&nbsp;Autostart <font color="red"><b>Disabled.</b></font>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotify(\'#enable_evilportal\');">Enable</a></b><br />';

    echo '<div id="spinny" style="visibility:hidden"><center><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center></div>';

    echo '
    <ul id="tabs">
      <li><a id="savedportals">Saved Portals</a></li>
      <li><a id="portalcode">Edit Portal</a></li>
      <li><a id="livepreview">Live Portal Preview</a></li>
      <li><a id="devpreview">Dev Portal Preview</a></li>
      <li><a id="configure">Configuration</a></li>
      <li><a id="changelog">Change Log</a></li>
    </ul>
    <div class="tabContainer" />
    ';
  }

}

?>
