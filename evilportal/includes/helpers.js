$(document).ready(function () {

  $('#tabs li a:not(:first)').addClass('inactive');
  selectTabContent($('#tabs li a:first').attr('id'));
  $('#tabs li a').click(function () {
    var t = $(this).attr('id');
    if ($(this).hasClass('inactive')) {
      $('#tabs li a').addClass('inactive');
      $(this).removeClass('inactive');
      selectTabContent(t);
    }else{
      selectTabContent(t);
    }
  });

});

function selectTabContent(id){
  $.ajaxSetup({async:false});
  $.get("/components/infusions/evilportal/includes/content/"+id+".php", function(data){
    $(".tabContainer").html(data);
  });
  $.ajaxSetup({async:true});
}

function update_log(message){
  if(message == "update"){
    $('#log').AJAXifyForm(update_log);
  }else{
    $('#log_message').text(message);
  }
}

function installDepends(id) {
  popup('<br /><center>Dependencies are being installed. This box will automatically go away.<br /><br /><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center>');
  setTimeout(function(){$(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); draw_large_tile("evilportal", "infusions"); close_popup();}, 2000);
  return false;
}

function configure(id) {
  popup('<br /><center>Configuration changes are being made. This box will automatically go away.<br /><br /><img style="height: 2em; width: 2em;" src="/includes/img/throbber.gif"</center>');
  setTimeout(function(){ $(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); draw_large_tile("evilportal", "infusions"); close_popup(); }, 2000);
  return false;
}

function ajaxPopup(id) {
  $(id).AJAXifyForm(popup);
  return false;
}

function ajaxNotify(id) {
  document.getElementById("spinny").style.visibility = "visible";
  $(id).AJAXifyForm(notify);
  return false;
}

function ajaxNotifyAndRefresh(id) {
  document.getElementById("spinny").style.visibility = "visible";
  setTimeout(function(){ $(id).AJAXifyForm(notify); refresh_small("evilportal", "user"); draw_large_tile("evilportal", "infusions"); }, 2000);
  return false;
}
