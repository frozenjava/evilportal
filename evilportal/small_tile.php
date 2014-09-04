<div style='text-align:right'><a href="#" class="refresh" onclick='refresh_small("evilportal", "user")'></a></div>

<form method="POST" id="configure_evilportal" action="<?php echo $rel_dir; ?>/functions.php?configure=small"></form>
<form method="POST" id="start_evilportal" action="<?php echo $rel_dir; ?>/functions.php?start=small"></form>
<form method="POST" id="stop_evilportal" action="<?php echo $rel_dir; ?>/functions.php?stop=small"></form>
<form method="POST" id="enable_evilportal" action="<?php echo $rel_dir; ?>/functions.php?enable=small"></form>
<form method="POST" id="disable_evilportal" action="<?php echo $rel_dir; ?>/functions.php?disable=small"></form>
<form method="POST" id="live_preview" action="<?php echo $rel_dir; ?>/functions.php?live_preview"></form>
<form method="POST" id="dev_preview" action="<?php echo $rel_dir; ?>/functions.php?dev_preview"></form>
<form method="POST" id="install_depends_evilportal" action="/components/infusions/evilportal/functions.php?install_depends"></form>

<script type="text/javascript">
  function ajaxNotify(id) {
    document.getElementById("spinny").style.visibility = "visible";
    $(id).AJAXifyForm(notify);
    return false;
  }

  function ajaxNotifyAndRefresh(id) {
    document.getElementById("spinny").style.visibility = "visible";
    setTimeout(function(){ $(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); }, 2000);
    return false;
  }

  function installDepends(id) {
    popup('<br /><center>Dependencies are being installed. This box will automatically go away.<br /><br /><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center>');
    setTimeout(function(){$(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); close_popup();}, 2000);
    return false;
  }


  function ajaxPopup(id) {
    $(id).AJAXifyForm(popup);
    return false;
  }

  function configure(id) {
    popup('<br /><center>Configuration changes are being made. This box will automatically go away.<br /><br /><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center>');
    setTimeout(function(){ $(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); close_popup(); }, 2000);
    return false;
  }

</script>

<?php

global $rel_dir, $directory;

include $directory . "/functions.php";

if (require_pineapple_version(2.0)) {
  $message = "";

  if (!checkDepends()) {
    if (online()) {
      echo 'Dependencies <font color="red"><b>Missing.</b></font>&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="installDepends(\'#install_depends_evilportal\');">Install</a></b><br />';
      $message = $message . '<font color="red">Dependencies must be installed.</font><script type="text/javascript">notify("Evil Portal has missing dependencies", "evilportal", "red");</script><br/>';
    } else
      $message = '<font color="red"><b>An internet connection is required to install dependencies!</b></font><script type="text/javascript">notify("Evil Portal needs an internet connection!", "evilportal", "red");</script>';
  } elseif (!checkConfig() && checkDepends()) {
    echo 'Configuration <font color="red"><b>Needed.</b></font>&nbsp|<b><a href="#" onclick="configure(\'#configure_evilportal\');">Configure</a></b><br />';
    $message = $message . '<font color="yellow">Configuration is needed.</font><script type="text/javascript">notify("Evil Portal must be configured", "evilportal", "yellow");</script><br />';
    $message = $message . '<font color="yellow"><i>Open for manual config.</i></font>';
  } elseif (checkConfig() && checkDepends()) {
    if (checkRunning())
      echo 'NoDogSplash <font color="lime"><b>Running.</b></font>&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotifyAndRefresh(\'#stop_evilportal\');">Stop</a></b><br />';
    else
      echo 'NoDogSplash <font color="red"><b>Disabled.</b></font>&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotifyAndRefresh(\'#start_evilportal\');">Start</a></b><br />';

    if (checkAutoStart())
      echo 'Autostart <font color="lime"><b>Enabled.</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotifyAndRefresh(\'#disable_evilportal\');">Disable</a></b><br />';
    else
      echo 'Autostart <font color="red"><b>Disabled.</b></font>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxNotifyAndRefresh(\'#enable_evilportal\');">Enable</a></b><br />';

    echo 'Live Portal Preview&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxPopup(\'#live_preview\');">Show</a></b><br />';
    echo 'Dev Portal Preview&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp<b><a href="#" onclick="ajaxPopup(\'#dev_preview\');">Show</a></b><br />';
  }

  echo '<div id="message"><br /><center><b>' . $message . '</b></center></div>';
  echo '<div id="spinny" style="visibility:hidden"><center><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center></div>';

} else {
 echo '<center><b><font color="red">Evil Portal required firmware version 2.0 or later. Please update your pineapple!</b></font></center>';
}

?>
