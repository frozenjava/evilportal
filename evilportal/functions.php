<?php

include_once("/pineapple/includes/api/tile_functions.php");

global $rel_dir, $directory;

if (isset($_GET['fixconfig'])) {
  exec('pineapple infusion evilportal fixconfig');
  echo '<center><font color="lime"><br />The configuration has been fixed!<br />You should restart NoDogSplash if it is running.</font></center>';
}

if (isset($_GET['backup'])) {
  $fileName = $_POST['filename'];
}

if (isset($_GET['finalize'])) {
  exec('echo "/etc/init.d/nginx stop && /etc/init.d/nginx start" | at now');
  echo 'Evil Portal is now ready for use!<script type="text/javascript">refresh_small("evilportal", "user");close_popup();draw_large_tile("evilportal", "infusions");</script>';
}

if (isset($_GET['configfile'])) {
  $file = $_GET['configfile'];
  showConfigFile($file);
}

if (isset($_GET['request_active'])) {
  $file = $_GET['request_active'];
  $message = '<br /><center>';
  $message .= '<b>You are about to set <i>' . $file . ' as your active portal</b><br />';
  $message .= 'This will overwrite your current portal!<br /><br />';
  $message .= 'Are you sure you want to continue?<br /><br />';
  $message .= '<a href="#" onclick="$(\'#active_portal\').AJAXifyForm(notify); close_popup();">Yes</a>&nbsp&nbsp&nbsp<a href="#" onclick="close_popup();">No</a>';
  $message .= '<form method="POST" id="active_portal" action="/components/infusions/evilportal/functions.php?set_active=' . $file .'"></form></center>';
  echo $message;
}
    
if (isset($_GET['set_active'])) {
  $file = $_GET['set_active'];
    exec('cp ' . $file . ' /etc/nodogsplash/htdocs/splash.html');
  echo $file . ' is now your active portal!';
}
    
if (isset($_GET['request_delete'])) {
  $file = $_GET['request_delete'];
  $message = '<br/><center>';
  $message .= '<b>You are about to delete <i>' . $file . '</i></b><br/>';
  $message .= 'Are you sure you want to delete this portal?<br /><br />';
  $message .= '<a href="#" onclick="$(\'#delete_portal\').AJAXifyForm(notify); close_popup();">Yes</a>&nbsp&nbsp&nbsp<a href="#" onclick="close_popup();">No</a>';
  $message .= '<form method="POST" id="delete_portal" action="/components/infusions/evilportal/functions.php?delete=' . $file .'"></form></center>';
  echo $message;
}

if (isset($_GET['delete'])) {
  $file = $_GET['delete'];
  if (unlink($file))
    echo 'File has been deleted. <script type="text/javascript">draw_large_tile("evilportal", "infusions");</script>';
  else
    echo 'There was an issue deleting this file';
}

if (isset($_GET['save'])) {

  $file = $_POST['file'];
  $backupName = str_replace("/", "", $_POST['backup_name']);
  $backupPath = $_POST['storage'];

  if ($file != "/etc/nodogsplash/htdocs/splash.html" && $backupName == "" && !strstr($file, "/sd/portals") && !strstr($file, "/root/portals"))
    $headText = "#configured\n";
  else
    $headText = "";

  $f = fopen($file, 'w');
  fwrite($f, $headText . $_POST['data']);
  fclose($f);

  if ($_POST['save_action'] == "exit")
    $javascript = '<script type="text/javascript">close_popup();</script>';
  else
    $javascript = '';

  $message = 'Saved file: ' . $_POST['file'] . ' ' . $javascript;

  if ($backupName != "") {

    if (!stringEndsWith($backup, ".html"))
      $backupName = $backupName . ".html";

    if ($backupPath == "/sd/portals/" && !sd_available()) 
      $message = "You can't save to an SD card if it is not there!";
    else {
      if (!file_exists($backupPath))
        mkdir($backupPath, 0777, true);

      $backup = fopen($backupPath . $backupName, 'w');
      fwrite($backup, $_POST['data']);
      fclose($f); 
      $message = 'Your portal has been saved to a backup!' . $javascript;
    }
  }

  echo $message;
}

if (isset($_GET['live_preview'])) {
  $url = $_GET['live_preview'];
  showLivePreview($url);
}

if (isset($_GET['dev_preview'])) {
  $file = $_GET['dev_preview'];
  showDevPreview($file);
}

if (isset($_GET['request_depends'])) {
  $fromTile = $_GET['request_depends'];
  $message = '<form method="POST" id="install_depends_evilportal" action="/components/infusions/evilportal/functions.php?install_depends=' . $fromTile . '"></form>';
  $message .= '<script type="text/javascript">setTimeout(function(){$("#install_depends_evilportal").AJAXifyForm(notify)}, 2000);</script>';
  $message .= "<br /><center>Dependencies are being installed. This box will automatically go away.<br /><br /><img style='height: 2em; width: 2em;' src='/includes/img/throbber.gif'</center>";
  echo $message;
}

if (isset($_GET['install_depends'])) {
  exec('pineapple infusion evilportal deps');

  $fromTile = $_GET['install_depends'];

  if ($fromTile == "large")
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");close_popup();draw_large_tile("evilportal", "infusions");</script>';
  else
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");close_popup();</script>';

  echo 'Evil Portal has finished installing dependencies! ' . $javascript;
}

if (isset($_GET['configure'])) {
  $fromTile = $_GET['configure'];

  if ($fromTile == "large")
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");close_popup();draw_large_tile("evilportal", "infusions");</script>';
  else
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");close_popup();notify("Evil Portal is now ready to use!", "evilportal", "green");</script>';

  exec('pineapple infusion evilportal config');
  echo 'Evil Portal has been configured! ' . $javascript;
}

if (isset($_GET['start'])) {
  if (checkDepends() && checkConfig()) {
    exec('echo "/etc/init.d/nodogsplash start" | at now && sleep 1s');
    
    $fromTile = $_GET['start'];
    
    if ($fromTile == "large")
      $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");draw_large_tile("evilportal", "infusions");</script>';
    else
      $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");</script>';
    
    echo 'Nodogsplash has been started!' . $javascript;

  } else
    echo 'You have actions that must be preformed first!';
}

if (isset($_GET['stop'])) {
  exec('killall nodogsplash && sleep 1s');

  $fromTile = $_GET['stop'];

  if ($fromTile == "large")
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");draw_large_tile("evilportal", "infusions");</script>';
    else
      $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");</script>';

  echo 'Nodogsplash has been stoped!' . $javascript;
}

if (isset($_GET['enable'])) {
  if (checkDepends() && checkConfig()) {
    exec('echo "/etc/init.d/nodogsplash enable" | at now && sleep 1s');

    $fromTile = $_GET['enable'];

    if ($fromTile == "large")
      $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");draw_large_tile("evilportal", "infusions");</script>';
    else
      $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");</script>';

    echo 'Nodogsplash will now run on startup!' . $javascript;
  } else
    echo 'You have actions that must be preformed first!';
}

if (isset($_GET['disable'])) {
  exec('echo "/etc/init.d/nodogsplash disable" | at now && sleep 1s');

  $fromTile = $_GET['disable'];

  if ($fromTile == "large")
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");draw_large_tile("evilportal", "infusions");</script>';
  else
    $javascript = '<script type="text/javascript">refresh_small("evilportal", "user");</script>';

  echo 'Nodogsplash will no longer run on startup!' . $javascript;
}

// FUNCTIONS BELOW

function stringEndsWith($whole, $end) {
    return (strpos($whole, $end, strlen($whole) - strlen($end)) !== false);
}

function checkRunning() {
  if (exec("ps -aux | grep -v grep | grep -o nodogsplash") == '')
    return false;
  else
    return true;
}

function checkAutoStart() {
  if (exec("ls /etc/rc.d/ | grep nodogsplash") == '')
    return false;
  else
    return true;
}

function checkDepends() {
  $splash = true;
  $sched = true;
  
  if (exec("opkg list-installed | grep nodogsplash") == '')
    $splash = false;

  if (exec("opkg list-installed | grep kmod-sched") == '')
    $sched = false;

  if ($splash == false || $sched == false)
    return false;
  else
    return true;

}

function checkConfig() {
  $nodogsplashFile = "/etc/nodogsplash/nodogsplash.conf";
  //$nginxFile = "/etc/nginx/nginx.conf";
  $nodogsplash = false;
  $nginx = true;

  if (file_exists($nodogsplashFile)) {
    $f = fopen($nodogsplashFile, "r");
    $line = fgets($f);
    if (strstr($line, "#configured"))
      $nodogsplash = true;
    fclose($f);
  }

  /*if (file_exists($nginxFile)) {
    $f = fopen($nginxFile, "r");
    $line = fgets($f);
    if (strstr($line, "#configured"))
      $nginx = true;
    fclose($f);
  }*/

  if ($nodogsplash == false || $nginx == false)
    return false;
  else
    return true;

}

function showDevPreview($file) {
  if ($file == "")
    $file = "/etc/nodogsplash/htdocs/splash.html";
  
  if (file_exists($file)) {
    $f=fopen($file, "r");
    $data=fread($f, filesize($file));
    fclose($f);
    echo '<br />' . $data;
  } else {
    echo '<center><b><font color="red">Please make sure you have installed all the dependencies!</b></font></center>';
  }
}

function showLivePreview($url) {
  if ($url == "")
    $url = "http://172.16.42.1:2050";

  if (exec('ps -aux | grep "nodogsplash" | grep -v "grep"') != "")
    echo '<br /><iframe src="' .  $url . '" height="80%" width="100%"/>';
  else
    echo '<center><b><font color="red">Please start NoDogSplash first!</b></font></center>';
}

function showConfigFile($file) {
  if (file_exists($file)) {
    $f=fopen($file, "r");
    $data=fread($f, filesize($file));
    fclose($f);

    if (sd_available())
      $options = array('<option value="/sd/portals/">SD Card</option>', '<option value="/root/portals/">Internal Storage</option>');
    else
      $options = array('<option value="/root/portals/">Internal Storage</option>');

    $storagebox = '<select id="storage" name="storage">';
    foreach($options as $option) { 
      $storagebox .= $option; 
    }
    $storagebox .= '</select>';

    if ($file != "/etc/nodogsplash/htdocs/splash.html") {
      $buttons = '<button type="button" onclick="save(\'exit\', false)">Save & Close</button><button type="button" onclick="save(\'continue\', false)">Save & Continue</button>';
    } else {
      $buttons = '<button type="button" onclick="save(\'continue\', false)">Save Portal</button>';
      $backup = '<div id="backupTable" style="float:left; text-align:left">' . $storagebox . '<input type="text" id="backname" placeholder="Backup Portal Name"> <button type="button" onclick="save(\'continue\', true)">Backup Portal</button></div>';
    }
    
    echo '
      <script type="text/javascript">
      function save(post_save_action, backup) {
        if (post_save_action == "exit") {
          document.getElementById("post_save_action").value = "exit";
        } else {
          document.getElementById("post_save_action").value = "continue";
        }

        if (backup) {
          document.getElementById("backup_name").value = document.getElementById("backname").value;
          document.getElementById("preform_backup").value = "true";
        }

        $(\'#save\').AJAXifyForm(notify);
        return false;
      }
      </script>

      <center>
      <b>Editing ' . $file . '</b><br /><br />
      
      <form id="save" method="POST" action="/components/infusions/evilportal/functions.php?save">
      ' . $buttons . '
      <br />
      ' . $backup . '
      <textarea name="data" style="width:100%; height:500px;">' . $data . '</textarea>
      <input type="hidden" id="file_name" name="file" value="'.$file.'">
      <input type="hidden" id="backup_name" name="backup_name" value="">
      <input type="hidden" id="preform_backup" name="preform_backup" value="false">
      <input type="hidden" name="save_action" id="post_save_action" value="exit">
      ' . $buttons . '
      </form>
      </center>
    ';
  } else
    echo 'Error finding file: ' . $file . ' . Make sure all dependencies are installed.';
}

function showSavedPortals() {
  $sd_portals = scandir('/sd/portals');
  $internal_portals = scandir('/root/portals');
          
  echo '<center><table style="width:100%; text-align:center;">';

  foreach ($sd_portals as $file) {
    if ($file != "." && $file != "..")
      echo '<tr><td>' . $file . '</td><td><a href="#" onclick="ajaxPopup(\'#view_sd_' . str_replace(".html", "", $file) . '\');">Dev Preview</a></td><td><a href="#" onclick="ajaxPopup(\'#code_sd_' . str_replace(".html", "", $file) . '\');">View Code</a></td><td><a href="#" onclick="ajaxPopup(\'#active_sd_' . str_replace(".html", "", $file) . '\');">Activate</a></td><td><a href="#" onclick="ajaxPopup(\'#delete_sd_' . str_replace(".html", "", $file) . '\');">Delete</a></td></tr>';
  }
          
  foreach ($internal_portals as $file) {
    if ($file != "." && $file != "..")
      echo '<tr><td>' . $file . '</td><td><a href="#" onclick="ajaxPopup(\'#view_int_' . str_replace(".html", "", $file) . '\');">Dev Preview</a></td><td><a href="#" onclick="ajaxPopup(\'#code_int_' . str_replace(".html", "", $file) . '\');">View Code</a></td><td><a href="#" onclick="ajaxPopup(\'#active_int_' . str_replace(".html", "", $file) . '\');">Activate</a></td><td><a href="#" onclick="ajaxPopup(\'#delete_int_' . str_replace(".html", "", $file) . '\');">Delete</a></td></tr>';
  }
  echo '</table></center>';

  foreach ($sd_portals as $file) {
    $dir = "/sd/portals";
    echo '<form method="POST" id="code_sd_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?configfile=' . $dir . '/' . $file . '"></form>';
    echo '<form method="POST" id="active_sd_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?request_active=' . $dir . '/' . $file .'"></form>';
    echo '<form method="POST" id="delete_sd_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?request_delete=' . $dir . '/' . $file .'"></form>';
    echo '<form method="POST" id="view_sd_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?dev_preview='. $dir . '/' . $file .'"></form>';
  }
          
  foreach ($internal_portals as $file) {
    $dir = "/root/portals";
    echo '<form method="POST" id="code_int_' .str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?configfile=' . $dir . '/' . $file . '"></form>';
    echo '<form method="POST" id="active_int_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?request_active=' . $dir . '/' . $file .'"></form>';
    echo '<form method="POST" id="delete_int_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?request_delete=' . $dir . '/' . $file .'"></form>';
    echo '<form method="POST" id="view_int_' . str_replace(".html", "", $file) . '" action="/components/infusions/evilportal/functions.php?dev_preview=' . $dir . '/' . $file .'"></form>';
  }

  if ((count($sd_portals)-2) <= 0 && (count($internal_portals)-2) <= 0)
    echo '<center><i>You have no saved portals to view</i></center>';

}

?>
