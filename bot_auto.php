<?php
include("bot_funcs.php");
$getgames = mysql_query("SELECT round,speed,maxbuild,type,starts,ends FROM games WHERE starts<$time AND ends>$time ORDER BY round ASC;");
while ($game = mysql_fetch_array($getgames))
{
    $playerinfo = mysql_query("SELECT id,code,trn,bot_build FROM r".$game[0]."_pimp WHERE status='bot' AND bot_hire<($time-(rand(5,9)*3600)) AND trn>0");
    while ($player = mysql_fetch_array($playerinfo)){
		if(($player[bot_build] > 0) && ($game[starts]+(($game[ends]-$game[starts])/3)<$time)){
		    gameBotProduce($game[round], $player[id], $player[trn], $player[thug], $player[bot_build], 80);
		}else{
		    gameHire($game[round], $player[id], $player[trn], $player[bot_build]);
		}
    }
}
?>
