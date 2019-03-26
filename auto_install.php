<?
include("bot_funcs.php");
$find_round = mysql_fetch_array(mysql_query("SELECT round,ends FROM `games` WHERE type='public' ORDER BY round DESC LIMIT 1"));
$last_mini = mysql_fetch_array(mysql_query("SELECT round,starts FROM `games` WHERE type='public' AND mini_installed='no' ORDER BY round DESC LIMIT 1"));
$time = time();



//What type of mini round
//$mini_type = rand(1,3);
$mini_type = 2;
//Detect the last mini installed
//Installs 2 days after main round starts on 3 days after
if(($time > $last_mini[1]+259200) AND ($last_mini[0]>0)){
    //Basic install needed
    $respacks = 'off';
    $winnings = 'auto';
    $reserves = '0';
    $addon = '10000';
    $lsres = '250';
    $crewmax='3';
    $begins = $last_mini[1]+345600;
    $ends = ($begins+172800);
    $city1 = 'Chicago';
    $city2 = 'Las Vegas';
    $city3 = 'New York';
    $flag = 0;
    $team = 0;

//Different types of mini
if($mini_type == 1){
    //Killer Mini
    $type = 'mini';
    $gstatus = 'killer';
    $protection = 'off';
    $speed = '500';
    $maxbuild = '25000';
    $gamename = 'Round #';
    $sjpot = '20';
    $bankdeposits = '3';
    
}elseif($mini_type == 2){
    //Regular Mini
    $type = 'mini';
    $gstatus = 'normal';
    $protection = 'off';
    $speed = '500';
    $maxbuild = '25000';
    $gamename = 'Round #';
    $sjpot = '20';
    $bankdeposits = '3';

}elseif($mini_type == 3){
    //Capture the Flag
    $type = 'mini';
    $gstatus = 'capture';
    $protection = 'half';
    $speed = '500';
    $maxbuild = '35000';
    $gamename = 'Round #';
    $sjpot = '0';
    $bankdeposits = '3';
    $flag = 1;
    
}else{
    
}
    
}

if($time > $find_round[1]-86400){
    $type = 'public';
    $gstatus = 'normal';
    $speed = '50';
    $lsres = 0;
    $maxbuild = '5000';
    $reserves = '2500';
    $addon = '10000000';
    $crewmax='10';
    $begins = $find_round[ends];
    $ends = ($find_round[ends]+691200);
    $winnings = 'auto';
    $gamename = 'Round #';
    $respacks = 'off';
    $sjpot = '50';
    $bankdeposits = '3';
    $protection = 'off';
    $city1 = 'Chicago';
    $city2 = 'Las Vegas';
    $city3 = 'London';
    $city4 = 'New York';
    $city5 = 'Tokyo';
    $city6 = 'Los Angeles';
    //Kill Flag
    $flag = 0;
    $team = 0;
    
}

if((($time > $last_mini[1]+259200) AND ($last_mini[0]>0)) || ($time > $find_round[1]-86400)){
    mysql_query("INSERT INTO `games` (type,speed,maxbuild,reserves,credits,crewmax,starts,ends,jackpot,gamename,respacks,sjpot,bankdeposits,status,protection,reslimit) VALUES ('$type','$speed','$maxbuild','$reserves','$addon','$crewmax','$begins','$ends','$winnings','$gamename','$respacks','$sjpot','$bankdeposits','$gstatus','$protection','$lsres');") or die(mysql_error());
    $round = mysql_fetch_array(mysql_query("SELECT round FROM `games` ORDER BY round DESC LIMIT 1"));
    mkdir("./images/avi/$round[0]", 0755);
    //enter new round tables in database automatically
    include("noadmin/install2.php");
    //Insert Admins to game
    echo "hello";
        $get_user = mysql_query("SELECT status,code,username,subscribe,password,pimpname FROM `users` WHERE status='admin';");
        while ($user = mysql_fetch_array($get_user)){
            $city = rand(1,3);
            InsertBots($user[pimpname], $round[0], $user[username], $user[password], $user[code], $user[status], $reserves, $maxbuild, $city, $begins, $team, $flag);
            echo "hello";
        }
        echo "hello2";
        //Insert Bots to game
        $allowedBots = 150;
        $crewAllow = round($allowedBots/$crewmax);
        $ccreate = mysql_query("SELECT name,id FROM `crewnames` LIMIT $crewAllow;");
        while ($crew_name = mysql_fetch_array($ccreate)){
            createCrew($round[0], $crew_name[0], $crew_name[id]);
            
        } 
        
        $get_user = mysql_query("SELECT status,code,username,subscribe,password,pimpname FROM `users` WHERE status='bot' LIMIT $allowedBots;");
        while ($user = mysql_fetch_array($get_user)){
            $team = 0; $flag = 0; $city = rand(1,3);
            InsertBots($user[pimpname], $round[0], $user[username], $user[password], $user[code], $user[status], $reserves, $maxbuild, $city, $begins, $team, $flag);
            $userid = mysql_fetch_array(mysql_query("SELECT id,pimp FROM r".$round[0]."_pimp ORDER BY id DESC LIMIT 1"));
            $crewid_findFounder = mysql_fetch_array(mysql_query("SELECT id,founder FROM r".$round[0]."_crew WHERE founder='$userid[0]'"));
            $crewid_find = mysql_fetch_array(mysql_query("SELECT id,founder FROM r".$round[0]."_crew WHERE members<10"));
            if($crewid_findFounder[1]==$userid[0]){
                crewFounder($round[0], $crewid_findFounder[0], $userid[0], $userid[1]);
                echo "$userid[1]";
                
            }else{
                crewMember($round[0], $crewid_find[0], $userid[0]); 
                
            }
            
        }
		
		
		//Inserting Cities in
		if($city1) { createCity($round[0], $city1); }
		if($city2) { createCity($round[0], $city2); }
		if($city3) { createCity($round[0], $city3); }
		if($city4) { createCity($round[0], $city4); }
		if($city5) { createCity($round[0], $city5); }
		if($city6) { createCity($round[0], $city6); }

		//mysql_query("UPDATE games SET gamename='Round $round[0]' WHERE round='$round[0]'");
		$msg="Round $round[0] has been installed.";
		email("Round #$round[0] Installed","\nDear  Admin,\n\nRound #$round[0] has been installed.","wayne619@live.com");
		//Post on the news board
		$subject = "Round Installed";
		$news = "Round Type: $type<br>Round Number: #$round[0]<br>Turns per 10 min: $speed<br>Max Turns: $maxbuild<br>Reserve Turns: $reserves<br>Cartel Max: $crewmax<br>Purchased/Won Turns Total Addon: $addon<br>Protection: $protection<br>Units Jackpot: $$sjpot<br>Units Percentage: 50%";
		mysql_query("INSERT INTO news (news,posted,subject) VALUES ('$news','$time','$subject');");
		mysql_query("UPDATE games SET mini_installed='yes' WHERE round='$find_round[0]'");
		echo "Round installed $round[0] - $type";
		}
?>
