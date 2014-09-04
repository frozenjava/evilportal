<?php
#########################
#    User Variables     #
#   Please edit these   #
#########################
$name = 'Evil Portal II';  #Name of tile
$updatable = 'false';  #Should this tile auto-refresh
$version = '2.3';

#########################
#     Handler Code      #
# No need to edit below #
#########################

#Set up handler functions and data
include('/pineapple/includes/api/tile_functions.php');
$directory = realpath(dirname(__FILE__)).'/';
$rel_dir = str_replace('/pineapple', '', $directory);
include('/pineapple/includes/api/handler_helper.php');

?>
