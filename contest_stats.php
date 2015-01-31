<?php 
	$url = "http://codeforces.com/api/contest.standings";

	//set these variables	
	$handles = array('shubham1100');
	$id=509; //contest id as in the contest url


	$req = "";
	$cnt = count($handles);
	for($x=0;$x<$cnt;$x++){
		$req = $req.$handles[$x];
		if($x != $cnt-1)$req = $req.";";
	}
	
	$req = urlencode($req);
	$url = $url."?contestId=$id&handles=".$req;
	$response = file_get_contents($url);
	$res = json_decode($response,true);
	$table =array();
	//echo $url;
	if($res['status'] == "OK"){
		$res1 = $res['result'];
		$contest = $res1['contest'];
		$probs = $res1['problems'];
		$rows = $res1['rows'];
		//echo "-------------------------".count($rows);
		for($y=0;$y<count($rows);$y++){
			//echo $y." ";
			$row = $rows[$y];
			$user = $row['party']['members'][0]['handle'];
			$stats = array();
			$stats['rank'] = $row['rank'];
			$stats['points'] = $row['points'];
			$stats['penalty'] = $row['penalty'];
			$stats['hacks']= array('success'=>$row['successfulHackCount'],'fail'=>$row['unsuccessfulHackCount']);
			$score = $row['problemResults'];
			$probStats = array();
			for($x=0;$x<count($score);$x++){
				$probidx = $probs[$x]['index'];
				$probStats[$probidx]=array('score'=>$score[$x]['points'],'fail'=>$score[$x]['rejectedAttemptCount'],
											'best'=>$score[$x]['bestSubmissionTimeSeconds']);
			}
			$stats['problemStats'] = $probStats;
			$table[$user] = $stats;
		}
			echo "Problems : ";
		for($x=0;$x<count($probs);$x++){
			echo $probs[$x]['index']." (".$probs[$x]['points'].") ";
		}
		echo "\n";
		//var_dump($table);
		foreach($table as $user => $stats){
			echo "\n\n".$user."\n"."--------------\n";
			foreach($stats as $attr=>$val){
				echo $attr." : ";
				if($attr == 'hacks'){
					echo "\n   successful : ".$val['success']."\n   failed : ".$val['fail']."\n";
				}
				elseif($attr =='problemStats'){
					echo "\n";
					foreach($val as $idx=>$ftw){
						echo "\n   Problem $idx : \n";
						echo '     score : '.$ftw['score']."; ";
						echo 'failed attempts : '.$ftw['fail']."; ";
						echo 'best time : '.$ftw['best']." ";
					}
				}
				else{
					echo $val."\n";
				}
			}

		}
	
	}
?>