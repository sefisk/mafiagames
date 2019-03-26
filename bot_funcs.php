<?php
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
	
function gameBotCollect1($trn)
	{
	    //Random One
		$collect = (rand(60, 75))/(rand(500,650));
		$collect=fixinput(round($collect*$trn));
		$collect = ($trn*$collect);
	}
	
function gameBotCollect2($trn)
	{
	    //Random 2
		$collect = (rand(80, 90))/(rand(325,400));
		$collect=fixinput(round($collect*$trn));
		$collect = ($trn*$collect);
	}

function gameBotProduce1($trn, $units)
	{
	    //Thugs produce cocain
	    $happiness=80;
		$rndm = (rand(70, 80))/(rand(300,450));
	 	$formula = ($happiness*$rndm);
		$produce = fixinput(round((($units/20)*$formula)*$trn));
		return $produce;
	}
	
function gameBotProduce2($trn, $units)
	{
	    //Thugs produce weed
	    $happiness=80;
		$rndm = (rand(70, 80))/(rand(300,450));
	 	$formula = ($happiness*$rndm);
		$produce = fixinput(round((($units/15)*$formula)*$trn)); 
		return $produce;
	}
function gameBotProduce3($trn, $units)
	{
	    //Bootleggers produce alcohol
	    $happiness=80;
		$rndm = (rand(70, 80))/(rand(300,450));
	 	$formula = ($happiness*$rndm);
		$produce = fixinput(round((($units/15)*$formula)*$trn));
		return $produce;
	}
function gameBotProduce4($trn, $units)
	{
	    //hustlers produce cash
	    $happiness=80;
		$rndm = (rand(70, 80))/(rand(300,450));
	 	$formula = ($happiness*$rndm);
		$produce = fixinput(round(((($units/20)*$formula)*$trn)*10));
		return $produce;
	}
	
function InsertBots($pimpname, $round, $username, $password, $code, $status, $reserves, $maxbuild, $city, $begins, $team, $flag)
	{
	    mysql_query("INSERT INTO r".$round."_pimp (pimp,user,pass,trn,city,online,code,status,res,team,flag) VALUES ('$pimpname','$username','$password','$maxbuild','$city','$begins','$code','$status','$reserves','$team','$flag');");
		mysql_query("INSERT INTO stats (user,round) VALUES ('$username','$round');");
	}
	
function createCity($round, $name)
    {
        mysql_query("INSERT INTO r".$round."_city (name) VALUES ('$name');");
    }

function createCrew($round, $cartel_name, $crewowner)
    {
         mysql_query("INSERT INTO r".$round."_crew (name,founder,city,members,invites) VALUES ('$cartel_name','$crewowner','1','1','11');") or die(mysql_error());
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
    
function creditRewards($username, $code, $reward, $round, $description, $time)
	{
	    mysql_query("INSERT INTO rewards (user,code,credits,round,description,time) VALUES ('$username','$code','$reward','$round', '$description','$time');");
		mysql_query("UPDATE users SET credits=credits+$reward WHERE code='$code'");
	}
?>
