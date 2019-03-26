?php
include("funcs2.php");

$getgames = mysql_query("SELECT round,speed,maxbuild,type,starts,ends FROM games WHERE starts<$time AND ends>$time ORDER BY round ASC;");
while ($game = mysql_fetch_array($getgames))
{
    $round = $game[round];
    
    $playerinfo = mysql_query("SELECT lastattack,lastattackby,id FROM r".$round."_pimp WHERE status='bot' AND lastattackby>0 AND lastattack>$time-86400");
    while ($player = mysql_fetch_array($playerinfo)){
        

        $id = $player[id];
        $defenderid = $player[lastattackby];
        $pid = $defenderid;
        $tru=$round;
        $yourinfo = mysql_fetch_array(mysql_query("SELECT id,pimp,whore,dealers,bootleggers,hustlers,punks,thug,bodyguards,hitmen,hkp7,shotgun,uzi,ak47,royaljet,heavyjet,hummer,cadillac,alcohol,weed,cocain,networth,status FROM r".$tru."_pimp WHERE id='$id'"));
        $theirinfo = mysql_fetch_array(mysql_query("SELECT id,pimp,whore,dealers,bootleggers,hustlers,punks,thug,bodyguards,hitmen,hkp7,shotgun,uzi,ak47,royaljet,heavyjet,hummer,cadillac,alcohol,weed,cocain,networth,city FROM r".$tru."_pimp WHERE id='$defenderid'"));
        $yourworkers = ($yourinfo[whore]+$yourinfo[dealers]+$yourinfo[bootleggers]+$yourinfo[hustlers]+$yourinfo[punks]);
        $theirworkers = ($theirinfo[whore]+$theirinfo[dealers]+$theirinfo[bootleggers]+$theirinfo[hustlers]+$theirinfo[punks]);
        $yourgoons = ($yourinfo[thug]+$yourinfo[bodyguards]+$yourinfo[hitmen]);
        $theirgoons = ($theirinfo[thug]+$theirinfo[bodyguards]+$theirinfo[hitmen]);
        $yourguns = ($yourinfo[hpk7]+$yourinfo[shotgun]+$yourinfo[uzi]+$yourinfo[ak47]);
        $theirplanes = ($theirinfo[royaljet]+$theirinfo[heavyjet]);
        $yourplanes = ($yourinfo[royaljet]+$yourinfo[heavyjet]);
        $theircars = ($theirinfo[hummer]+$theirinfo[cadillac]);
        $yourcars = ($yourinfo[hummer]+$yourinfo[cadillac]);
        $theirdrugs = ($theirinfo[alcohol]+$theirinfo[weed]+$theirinfo[cocain]);
        $botalreadyattack = mysql_fetch_array(mysql_query("SELECT id,botid,playerid,time,round FROM botattack WHERE ((botid='$id' AND playerid='$defenderid' AND round='$round') || (playerid!='$defenderid' AND round='$round'))"));

            if(($yourgoons > $theirgoons) && ($yourgoons!=0) && ($theirgoons!=0) && ($botalreadyattack[0]=='')){
                
                echo "run attack";
                mysql_query("UPDATE r".$round."_pimp SET city='$theirinfo[city]', online='$time' WHERE id='$id'");

                    include("bot_attack_include.php");
                    mysql_query("INSERT INTO botattack (time,round,botid,playerid) VALUES ('$time','$round','$id','$defenderid');");
                } 

            }
        
    }
    

?>
