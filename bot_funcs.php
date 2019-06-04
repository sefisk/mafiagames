<<?php
include("funcs2.php");
function gameBotHireDus($trn)
	{
            $randthug=(rand(5000,6000)/1000); $rounthug=($randthug); $thug=($thug+$rounthug);
            $thug=($thug*$trn);
            $thug=round($thug);
            $thug=fixinput($thug);
            return $thug;
	}
	
function gameBotHireOps($trn)
	{

	        $randhoe=(rand(7000,8000)/1000); $rounhoe=($randhoe); $hoe=($hoe+$rounhoe);
	        $hoe=($hoe*$trn);
            $hoe=round($hoe);
            $hoe=fixinput($hoe); 
            return $hoe;
	}
	
function gameBotCollect($trn, $type)
	{
	    //Random One
		if($type==1){$collect = (rand(60, 75))/(rand(500,650));}
		if($type==2){$collect = (rand(80, 90))/(rand(325,400));}
		$collect=fixinput(round($collect*$trn));
		$collect = ($trn*$collect);
	}

function gameBotProduce($round, $account, $trn, $units, $type, $happiness)
	{
	    //weed/alcohol=15 cocain/cash=20
	    if($type==3||4){$vint=15;}else{$vint=20;}
	    $rand=rand(1,2);
	    $rndm = (rand(70, 80))/(rand(300,450));
	 	$formula = ($happiness*$rndm);
		$value = fixinput(round((($units/$vint)*$formula)*$trn)); 
		$sql="UPDATE r".$round."_pimp SET ";
	    if(($type==4) && $rand==1){$sql.="weed=weed+$value, ";}
	    if($type==4 && $rand==2){ $sql.="cocain=cocain+$value, ";}
	    if($type==2){ $sql.="alcohol=alcohol+$value, ";}
	    if($type==1||3){ $sql.="money=money+($value*10), "; }
	    $sql.="trn=trn-$trn, online=".time().", bot_hire=".time()." WHERE id=$account";
	    mysql_query($sql) or die(mysql_error());
	}
	
function gameHire($round, $account, $trn, $type)
	{
	    $valueops=gameBotHireOps($trn);
	    $valuedus=gameBotHireDus($trn);
	    $sql="UPDATE r".$round."_pimp SET ";
	    if($type==1){ $sql.="dealers=dealers+$valueops, whore=whore+$valuedus, "; }
	    if($type==2){ $sql.="hitmen=hitmen+$valuedus, punks=punks+$valueops, ak47=ak47+$valuedus, ";}
        if($type==3){ $sql.="hustlers=hustlers+$valueops, bootleggers=bootleggers+$valuedus, ";}
        if($type==4){ $sql.="bodyguards=bodyguards+$valueops, thug=thug+$valuedus, ak47=ak47+($valueops+$valuedus), ";}
	    if($type>0){$sql.="trn=trn-$trn, bot_hire=".time().", "; }else{ $sql.="bot_build=4, "; }
	    $sql.="online=".time()." WHERE id=$account";
	    mysql_query($sql) or die(mysql_error());
	}
	
function joinGame($pimpname, $round, $username, $password, $code, $status, $reserves, $maxbuild, $city, $begins, $team, $flag)
	{
	    mysql_query("INSERT INTO r".$round."_pimp (pimp,user,pass,trn,city,online,code,status,res,team,flag) VALUES ('$pimpname','$username','$password','$maxbuild','$city','$begins','$code','$status','$reserves','$team','$flag');") or die(mysql_error());
		mysql_query("INSERT INTO stats (user,round) VALUES ('$username','$round');") or die(mysql_error());
	}
	

function createCity($round, $name)
    {
        mysql_query("INSERT INTO r".$round."_city (name) VALUES ('$name');");
    }

function createCrew($round, $crewAllow)
    {
        $ccreate = mysql_query("SELECT name,id FROM `crewnames` LIMIT $crewAllow;");
        while ($crew_name = mysql_fetch_array($ccreate)){
        mysql_query("INSERT INTO r".$round."_crew (name,founder,city,members,invites) VALUES ('$crew_name[0]','$crew_name[id]','1','1','11');") or die(mysql_error());
        }
    }
function crewMember($round, $crewid, $userid)
    {
        mysql_query("UPDATE r".$round."_crew SET members=members+1 WHERE id='$crewid'");
        mysql_query("UPDATE r".$round."_pimp SET crew='$crewid', cartelrank='4', cartelpower='4' WHERE id='$userid'");
    }
function crewFounder($round, $crewid, $userid, $foundername)
    {
        mysql_query("UPDATE r".$round."_crew SET founder='$foundername' WHERE id='$crewid'") or die(mysql_error());
        mysql_query("UPDATE r".$round."_pimp SET crew='$crewid', cartelrank='1', cartelpower='1' WHERE id='$userid'") or die(mysql_error());
    }
function joinCrews($round){
        $userid = mysql_fetch_array(mysql_query("SELECT id,pimp FROM r".$round."_pimp ORDER BY id DESC LIMIT 1"));
        $crewid_findFounder = mysql_fetch_array(mysql_query("SELECT id,founder FROM r".$round."_crew WHERE founder='$userid[0]'"));
        $crewid_find = mysql_fetch_array(mysql_query("SELECT id,founder FROM r".$round."_crew WHERE members<10"));
        if($crewid_findFounder[1]==$userid[0]){
            crewFounder($round, $crewid_findFounder[0], $userid[0], $userid[1]);
                
        }else{
            crewMember($round, $crewid_find[0], $userid[0]); 
        }
}    
function creditRewards($username, $code, $reward, $round, $description, $time)
	{
	    mysql_query("INSERT INTO rewards (user,code,credits,round,description,time) VALUES ('$username','$code','$reward','$round', '$description','$time');") or die(mysql_error());
		//mysql_query("UPDATE users SET credits=credits+$reward WHERE code='$code'");
	}
?>
